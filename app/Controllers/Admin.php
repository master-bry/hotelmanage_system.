<?php
namespace App\Controllers;

use App\Models\RoomModel;
use App\Models\RoombookModel;
use App\Models\PaymentModel;
use App\Models\StaffModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    protected $session;
    protected $roomModel;
    protected $roomBookModel;
    protected $paymentModel;
    protected $staffModel;
    protected $userModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->roomModel = new RoomModel();
        $this->roomBookModel = new RoombookModel();
        $this->paymentModel = new PaymentModel();
        $this->staffModel = new StaffModel();
        $this->userModel = new UserModel();

        // Check if user is staff
        if (!$this->session->get('is_staff')) {
            return redirect()->to('home')->with('error', 'Access denied. Staff privileges required.');
        }
    }

    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        // Get counts for dashboard
       $roombookCount = $this->roomBookModel->countAll();
       $roombookCount = ($roombookCount !== false) ? $roombookCount : 0;

       $staffCount = $this->staffModel->countAll();
       $staffCount = ($staffCount !== false) ? $staffCount : 0;

      $roomCount = $this->roomModel->countAll();
      $roomCount = ($roomCount !== false) ? $roomCount : 0;
        
        // Get room type counts
        $roomTypeCounts = $this->roomBookModel
            ->select('room_type, COUNT(*) as count')
            ->groupBy('room_type')
            ->findAll();
        
        $chartData = [];
        foreach ($roomTypeCounts as $roomType) {
            $chartData[$roomType['room_type']] = $roomType['count'];
        }

        // Get profit data (last 7 days)
        $profitData = $this->paymentModel
            ->select('DATE(created_at) as date, SUM(final_total) as profit')
            ->where('created_at >=', date('Y-m-d', strtotime('-7 days')))
            ->groupBy('DATE(created_at)')
            ->orderBy('date', 'ASC')
            ->findAll();

        $totalProfit = $this->paymentModel
            ->selectSum('final_total')
            ->first()['final_total'] ?? 0;

        $data = [
            'title' => 'Admin Dashboard - SkyBird Hotel',
            'roombookCount' => $roombookCount,
            'staffCount' => $staffCount,
            'roomCount' => $roomCount,
            'totalProfit' => $totalProfit,
            'chartData' => $chartData,
            'profitData' => $profitData,
            'chartroom1' => $chartData['Superior Room'] ?? 0,
            'chartroom2' => $chartData['Deluxe Room'] ?? 0,
            'chartroom3' => $chartData['Guest House'] ?? 0,
            'chartroom4' => $chartData['Single Room'] ?? 0,
        ];

        return view('admin/dashboard', $data);
    }

    public function getChartData()
    {
        $roomTypeCounts = $this->roomBookModel
            ->select('room_type, COUNT(*) as count')
            ->groupBy('room_type')
            ->findAll();

        $profitData = $this->paymentModel
            ->select('DATE(created_at) as date, SUM(final_total) as profit')
            ->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
            ->groupBy('DATE(created_at)')
            ->orderBy('date', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'roomData' => [
                'Superior Room' => $this->getRoomTypeCount($roomTypeCounts, 'Superior Room'),
                'Deluxe Room' => $this->getRoomTypeCount($roomTypeCounts, 'Deluxe Room'),
                'Guest House' => $this->getRoomTypeCount($roomTypeCounts, 'Guest House'),
                'Single Room' => $this->getRoomTypeCount($roomTypeCounts, 'Single Room'),
            ],
            'profitData' => $profitData,
        ]);
    }

    private function getRoomTypeCount($counts, $roomType)
    {
        foreach ($counts as $count) {
            if ($count['room_type'] === $roomType) {
                return $count['count'];
            }
        }
        return 0;
    }

    public function bookings()
    {
        $data = [
            'title' => 'Room Bookings - Admin',
            'bookings' => $this->roomBookModel->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('admin/bookings', $data);
    }

    public function addBooking()
    {
        if ($this->request->getMethod() === 'post') {
            $bookingData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'country' => $this->request->getPost('country'),
                'room_type' => $this->request->getPost('room_type'),
                'bed_type' => $this->request->getPost('bed_type'),
                'room_count' => $this->request->getPost('room_count'),
                'meal_plan' => $this->request->getPost('meal_plan'),
                'check_in' => $this->request->getPost('check_in'),
                'check_out' => $this->request->getPost('check_out'),
                'days' => $this->calculateDays(
                    $this->request->getPost('check_in'),
                    $this->request->getPost('check_out')
                ),
                'status' => 'pending',
            ];

            if ($this->roomBookModel->insert($bookingData)) {
                return redirect()->to('admin/bookings')->with('success', 'Booking added successfully');
            } else {
                return redirect()->to('admin/bookings')->with('error', 'Failed to add booking');
            }
        }
    }

    public function confirmBooking($id)
    {
        $booking = $this->roomBookModel->find($id);
        if ($booking) {
            // Update booking status
            $this->roomBookModel->update($id, ['status' => 'confirmed']);
            
            // Create payment record
            $totals = $this->paymentModel->calculateTotals($booking);
            $paymentData = [
                'booking_id' => $id,
                'room_total' => $totals['room_total'],
                'bed_total' => $totals['bed_total'],
                'meal_total' => $totals['meal_total'],
                'final_total' => $totals['final_total'],
                'payment_status' => 'pending',
            ];
            
            $this->paymentModel->insert($paymentData);
            
            return redirect()->to('admin/bookings')->with('success', 'Booking confirmed successfully');
        }
        
        return redirect()->to('admin/bookings')->with('error', 'Booking not found');
    }

    public function deleteBooking($id)
    {
        if ($this->roomBookModel->delete($id)) {
            return redirect()->to('admin/bookings')->with('success', 'Booking deleted successfully');
        }
        return redirect()->to('admin/bookings')->with('error', 'Failed to delete booking');
    }

    public function editBooking($id)
    {
        $data = [
            'title' => 'Edit Booking - Admin',
            'booking' => $this->roomBookModel->find($id),
        ];
        return view('admin/edit_booking', $data);
    }

    public function updateBooking($id)
    {
        if ($this->request->getMethod() === 'post') {
            $bookingData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'country' => $this->request->getPost('country'),
                'room_type' => $this->request->getPost('room_type'),
                'bed_type' => $this->request->getPost('bed_type'),
                'room_count' => $this->request->getPost('room_count'),
                'meal_plan' => $this->request->getPost('meal_plan'),
                'check_in' => $this->request->getPost('check_in'),
                'check_out' => $this->request->getPost('check_out'),
                'days' => $this->calculateDays(
                    $this->request->getPost('check_in'),
                    $this->request->getPost('check_out')
                ),
            ];

            if ($this->roomBookModel->update($id, $bookingData)) {
                return redirect()->to('admin/bookings')->with('success', 'Booking updated successfully');
            } else {
                return redirect()->to('admin/bookings')->with('error', 'Failed to update booking');
            }
        }
    }

    public function payments()
    {
        $payments = $this->paymentModel
            ->select('payments.*, room_bookings.name, room_bookings.email')
            ->join('room_bookings', 'room_bookings.id = payments.booking_id')
            ->orderBy('payments.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Payments - Admin',
            'payments' => $payments,
        ];
        return view('admin/payments', $data);
    }

    public function generateInvoice($id)
    {
        $payment = $this->paymentModel
            ->select('payments.*, room_bookings.*')
            ->join('room_bookings', 'room_bookings.id = payments.booking_id')
            ->where('payments.id', $id)
            ->first();

        if (!$payment) {
            return redirect()->to('admin/payments')->with('error', 'Payment not found');
        }

        $data = [
            'title' => 'Invoice - SkyBird Hotel',
            'payment' => $payment,
        ];
        return view('admin/invoice', $data);
    }

    public function rooms()
    {
        $data = [
            'title' => 'Rooms - Admin',
            'rooms' => $this->roomModel->findAll(),
        ];
        return view('admin/rooms', $data);
    }

    public function addRoom()
    {
        if ($this->request->getMethod() === 'post') {
            $roomData = [
                'type' => $this->request->getPost('type'),
                'bedding' => $this->request->getPost('bedding'),
                'price' => $this->request->getPost('price'),
                'status' => 'available',
            ];

            if ($this->roomModel->insert($roomData)) {
                return redirect()->to('admin/rooms')->with('success', 'Room added successfully');
            } else {
                return redirect()->to('admin/rooms')->with('error', 'Failed to add room');
            }
        }
    }

    public function deleteRoom($id)
    {
        if ($this->roomModel->delete($id)) {
            return redirect()->to('admin/rooms')->with('success', 'Room deleted successfully');
        }
        return redirect()->to('admin/rooms')->with('error', 'Failed to delete room');
    }

    public function staff()
    {
        $data = [
            'title' => 'Staff - Admin',
            'staff' => $this->staffModel->findAll(),
        ];
        return view('admin/staff', $data);
    }

    public function addStaff()
    {
        if ($this->request->getMethod() === 'post') {
            $staffData = [
                'name' => $this->request->getPost('name'),
                'position' => $this->request->getPost('position'),
                'department' => $this->request->getPost('department'),
            ];

            if ($this->staffModel->insert($staffData)) {
                return redirect()->to('admin/staff')->with('success', 'Staff added successfully');
            } else {
                return redirect()->to('admin/staff')->with('error', 'Failed to add staff');
            }
        }
    }

    public function deleteStaff($id)
    {
        if ($this->staffModel->delete($id)) {
            return redirect()->to('admin/staff')->with('success', 'Staff deleted successfully');
        }
        return redirect()->to('admin/staff')->with('error', 'Failed to delete staff');
    }

    private function calculateDays($checkIn, $checkOut)
    {
        $start = new \DateTime($checkIn);
        $end = new \DateTime($checkOut);
        return $start->diff($end)->days;
    }
}