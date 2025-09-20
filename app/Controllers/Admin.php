<?php
namespace App\Controllers;

use App\Models\RoomModel;
use App\Models\RoombookModel;
use App\Models\PaymentModel;
use App\Models\StaffModel;

class Admin extends BaseController
{
    protected $session;
    protected $roomModel;
    protected $roombookModel;
    protected $paymentModel;
    protected $staffModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->roomModel = new RoomModel();
        $this->roombookModel = new RoombookModel();
        $this->paymentModel = new PaymentModel();
        $this->staffModel = new StaffModel();
    }

    public function index()
    {
        $data = [
            'title' => 'SKY Hotel - Admin',
        ];
        return view('admin/index', $data);
    }

    public function dashboard()
    {
        $cache = \Config\Services::cache();
        $cacheKey = 'admin_dashboard_data';
        if (!($data = $cache->get($cacheKey))) {
            $roombookCounts = $this->roombookModel->select('RoomType, COUNT(*) as count')
                ->groupBy('RoomType')
                ->findAll();
            $counts = array_column($roombookCounts, 'count', 'RoomType');
            $data = [
                'title' => 'SKY Hotel - Admin Dashboard',
                'roombookCount' => $this->roombookModel->countAll(),
                'staffCount' => $this->staffModel->countAll(),
                'roomCount' => $this->roomModel->countAll(),
                'chartroom1' => $counts['Superior Room'] ?? 0,
                'chartroom2' => $counts['Deluxe Room'] ?? 0,
                'chartroom3' => $counts['Guest House'] ?? 0,
                'chartroom4' => $counts['Single Room'] ?? 0,
                'totalProfit' => $this->paymentModel->selectSum('finaltotal')->first()['finaltotal'] ?? 0,
                'chart_data' => $this->paymentModel->select('DATE(cin) as date, SUM(finaltotal) as profit')
                    ->groupBy('DATE(cin)')
                    ->findAll(),
            ];
            $cache->save($cacheKey, $data, 300); // Cache for 5 minutes
        }
        return view('admin/dashboard', $data);
    }

    public function getChartData()
    {
        $chartroom1 = $this->roombookModel->where('RoomType', 'Superior Room')->countAllResults();
        $chartroom2 = $this->roombookModel->where('RoomType', 'Deluxe Room')->countAllResults();
        $chartroom3 = $this->roombookModel->where('RoomType', 'Guest House')->countAllResults();
        $chartroom4 = $this->roombookModel->where('RoomType', 'Single Room')->countAllResults();
        $profitData = $this->paymentModel->select('DATE(cin) as date, SUM(finaltotal) as profit')
            ->groupBy('DATE(cin)')
            ->findAll();

        return $this->response->setJSON([
            'roomData' => [$chartroom1, $chartroom2, $chartroom3, $chartroom4],
            'profitData' => $profitData,
        ]);
    }

    public function roombook()
    {
        $data = [
            'title' => 'Room Booking - Admin',
            'roombookData' => $this->roombookModel->findAll(),
        ];
        return view('admin/roombook', $data);
    }

    public function payment()
    {
        $data = [
            'title' => 'Payment - Admin',
            'paymentData' => $this->paymentModel->findAll(),
        ];
        return view('admin/payment', $data);
    }

    public function room()
    {
        $data = [
            'title' => 'Rooms - Admin',
            'roomData' => $this->roomModel->findAll(),
        ];
        return view('admin/room', $data);
    }

    public function staff()
    {
        $data = [
            'title' => 'Staff - Admin',
            'staffData' => $this->staffModel->findAll(),
        ];
        return view('admin/staff', $data);
    }

    public function addroom()
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'type' => $this->request->getPost('troom'),
                'bedding' => $this->request->getPost('bed'),
            ];
            if ($this->roomModel->insert($data)) {
                $this->session->setFlashdata('success', 'Room added successfully');
            } else {
                $this->session->setFlashdata('error', 'Failed to add room');
            }
        }
        return redirect()->to(base_url('admin/room'));
    }

    public function roomdelete($id)
    {
        $this->roomModel->delete($id);
        $this->session->setFlashdata('success', 'Room deleted successfully');
        return redirect()->to(base_url('admin/room'));
    }

    public function roombookdelete($id)
    {
        $this->roombookModel->delete($id);
        $this->session->setFlashdata('success', 'Booking deleted successfully');
        return redirect()->to(base_url('admin/roombook'));
    }

    public function paymantdelete($id)
    {
        $this->paymentModel->delete($id);
        $this->session->setFlashdata('success', 'Payment record deleted successfully');
        return redirect()->to(base_url('admin/payment'));
    }

    public function invoiceprint($id)
    {
        $booking = $this->roombookModel->find($id);
        if (!$booking) {
            $this->session->setFlashdata('error', 'Booking not found');
            return redirect()->to(base_url('admin/payment'));
        }

        $rates = [
            'Superior Room' => 100000,
            'Deluxe Room' => 80000,
            'Guest House' => 50000,
            'Single Room' => 30000,
            'Single' => 5000,
            'Double' => 10000,
            'None' => 0,
            'Room only' => 0,
            'Breakfast' => 5000,
            'Half Board' => 10000,
            'Full Board' => 15000,
        ];

        $data = [
            'title' => 'Invoice - SkyBird Hotel',
            'booking' => $booking,
            'type_of_room' => $rates[$booking['RoomType']],
            'type_of_bed' => $rates[$booking['Bed']],
            'type_of_meal' => $rates[$booking['Meal']],
            'rtot' => $rates[$booking['RoomType']] * $booking['NoofRoom'] * $booking['nodays'],
            'btot' => $rates[$booking['Bed']] * $booking['NoofRoom'] * $booking['nodays'],
            'mepr' => $rates[$booking['Meal']] * $booking['NoofRoom'] * $booking['nodays'],
            'fintot' => ($rates[$booking['RoomType']] + $rates[$booking['Bed']] + $rates[$booking['Meal']]) * $booking['NoofRoom'] * $booking['nodays'],
        ];

        return view('admin/invoiceprint', $data);
    }

    public function exportdata()
    {
        // Implement export logic if needed
        $this->session->setFlashdata('error', 'Export feature not implemented');
        return redirect()->to(base_url('admin'));
    }

    public function roomconfirm($id)
    {
        $booking = $this->roombookModel->find($id);
        if ($booking) {
            $this->roombookModel->update($id, ['stat' => 'Confirm']);
            $totals = $this->paymentModel->calculateTotals($booking);
            $totals['Name'] = $booking['Name'];
            $totals['RoomType'] = $booking['RoomType'];
            $totals['Bed'] = $booking['Bed'];
            $totals['cin'] = $booking['cin'];
            $totals['cout'] = $booking['cout'];
            $totals['noofdays'] = $booking['nodays'];
            $totals['NoofRoom'] = $booking['NoofRoom'];
            $totals['meal'] = $booking['Meal'];
            $this->paymentModel->insert($totals);
            $this->session->setFlashdata('success', 'Booking confirmed successfully');
        } else {
            $this->session->setFlashdata('error', 'Booking not found');
        }
        return redirect()->to(base_url('admin/roombook'));
    }

    public function roombookedit($id)
    {
        $data = [
            'title' => 'Edit Room Booking - Admin',
            'booking' => $this->roombookModel->find($id),
        ];
        return view('admin/roombookedit', $data);
    }

    public function roombookupdate($id)
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'Name' => $this->request->getPost('Name'),
                'Email' => $this->request->getPost('Email'),
                'Country' => $this->request->getPost('Country'),
                'Phone' => $this->request->getPost('Phone'),
                'RoomType' => $this->request->getPost('RoomType'),
                'Bed' => $this->request->getPost('Bed'),
                'NoofRoom' => $this->request->getPost('NoofRoom'),
                'Meal' => $this->request->getPost('Meal'),
                'cin' => $this->request->getPost('cin'),
                'cout' => $this->request->getPost('cout'),
                'nodays' => $this->calculateNoOfDays($this->request->getPost('cin'), $this->request->getPost('cout')),
            ];

            if ($this->roombookModel->update($id, $data)) {
                $this->session->setFlashdata('success', 'Booking updated successfully');
            } else {
                $this->session->setFlashdata('error', 'Failed to update booking');
            }
        }
        return redirect()->to(base_url('admin/roombook'));
    }

    public function staffdelete($id)
    {
        $this->staffModel->delete($id);
        $this->session->setFlashdata('success', 'Staff deleted successfully');
        return redirect()->to(base_url('admin/staff'));
    }

    public function addstaff()
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'name' => $this->request->getPost('staffname'),
                'work' => $this->request->getPost('staffwork'),
            ];
            if ($this->staffModel->insert($data)) {
                $this->session->setFlashdata('success', 'Staff added successfully');
            } else {
                $this->session->setFlashdata('error', 'Failed to add staff');
            }
        }
        return redirect()->to(base_url('admin/staff'));
    }

    private function calculateNoOfDays($cin, $cout)
    {
        $start = new \DateTime($cin);
        $end = new \DateTime($cout);
        return $start->diff($end)->days;
    }
}