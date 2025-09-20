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
    
    // Check if user is logged in and is staff
    if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
        return redirect()->to(base_url('/'));
    }
}

    public function index()
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $data = [
            'title' => 'SKY Hotel - Admin',
        ];
        return view('admin/index', $data);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }

    public function dashboard()
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $roombookCount = $this->roombookModel->countAll();
        $staffCount = $this->staffModel->countAll();
        $roomCount = $this->roomModel->countAll();
        $chartroom1 = $this->roombookModel->where('RoomType', 'Superior Room')->countAllResults();
        $chartroom2 = $this->roombookModel->where('RoomType', 'Deluxe Room')->countAllResults();
        $chartroom3 = $this->roombookModel->where('RoomType', 'Guest House')->countAllResults();
        $chartroom4 = $this->roombookModel->where('RoomType', 'Single Room')->countAllResults();

        $payments = $this->paymentModel->findAll();
        $chart_data = '';
        $tot = 0;
        foreach ($payments as $row) {
            $chart_data .= "{ date:'{$row['cout']}', profit:" . ($row['finaltotal'] * 10 / 100) . "}, ";
            $tot += $row['finaltotal'] * 10 / 100;
        }
        $chart_data = substr($chart_data, 0, -2);

        $data = [
            'title' => 'SKY Hotel - Dashboard',
            'roombookCount' => $roombookCount,
            'staffCount' => $staffCount,
            'roomCount' => $roomCount,
            'chartroom1' => $chartroom1,
            'chartroom2' => $chartroom2,
            'chartroom3' => $chartroom3,
            'chartroom4' => $chartroom4,
            'chart_data' => $chart_data,
            'totalProfit' => $tot,
        ];
        return view('admin/dashboard', $data);
    }

    public function roombook()
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $roombookData = $this->roombookModel->findAll();
        $data = [
            'title' => 'SKY Hotel - Room Booking',
            'roombookData' => $roombookData,
        ];
        return view('admin/roombook', $data);
    }

    public function payment()
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $paymentData = $this->paymentModel->findAll();
        $data = [
            'title' => 'SKY Hotel - Payment',
            'paymentData' => $paymentData,
        ];
        return view('admin/payment', $data);
    }

    public function room()
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $roomData = $this->roomModel->findAll();
        $data = [
            'title' => 'SKY Hotel - Rooms',
            'roomData' => $roomData,
        ];
        return view('admin/room', $data);
    }

    public function staff()
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $staffData = $this->staffModel->findAll();
        $data = [
            'title' => 'SKY Hotel - Staff',
            'staffData' => $staffData,
        ];
        return view('admin/staff', $data);
    }

    public function addroom()
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'type' => $this->request->getPost('troom'),
                'bedding' => $this->request->getPost('bed'),
            ];
            if ($this->roomModel->insert($data)) {
                return redirect()->to(base_url('admin/room'));
            }
        }
        return redirect()->to(base_url('admin/room'));
    }

    public function roomdelete($id)
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $this->roomModel->delete($id);
        return redirect()->to(base_url('admin/room'));
    }

    public function roombookdelete($id)
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $this->roombookModel->delete($id);
        return redirect()->to(base_url('admin/roombook'));
    }

    public function paymantdelete($id)
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $this->paymentModel->delete($id);
        return redirect()->to(base_url('admin/payment'));
    }

    public function invoiceprint($id)
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $booking = $this->paymentModel->find($id);
        if (!$booking) {
            return redirect()->to(base_url('admin/payment'));
        }

        $type_of_room = 0;
        if ($booking['RoomType'] == "Superior Room") {
            $type_of_room = 320;
        } else if ($booking['RoomType'] == "Deluxe Room") {
            $type_of_room = 220;
        } else if ($booking['RoomType'] == "Guest House") {
            $type_of_room = 180;
        } else if ($booking['RoomType'] == "Single Room") {
            $type_of_room = 150;
        }

        $type_of_bed = 0;
        if ($booking['Bed'] == "Single") {
            $type_of_bed = $type_of_room * 1 / 100;
        } else if ($booking['Bed'] == "Double") {
            $type_of_bed = $type_of_room * 2 / 100;
        } else if ($booking['Bed'] == "Triple") {
            $type_of_bed = $type_of_room * 3 / 100;
        } else if ($booking['Bed'] == "Quad") {
            $type_of_bed = $type_of_room * 4 / 100;
        } else if ($booking['Bed'] == "None") {
            $type_of_bed = $type_of_room * 0 / 100;
        }

        $type_of_meal = 0;
        if ($booking['meal'] == "Room only") {
            $type_of_meal = $type_of_bed * 0;
        } else if ($booking['meal'] == "Breakfast") {
            $type_of_meal = $type_of_bed * 2;
        } else if ($booking['meal'] == "Half Board") {
            $type_of_meal = $type_of_bed * 3;
        } else if ($booking['meal'] == "Full Board") {
            $type_of_meal = $type_of_bed * 4;
        }

        $data = [
            'title' => 'Invoice',
            'booking' => $booking,
            'type_of_room' => $type_of_room,
            'type_of_bed' => $type_of_bed,
            'type_of_meal' => $type_of_meal,
            'ttot' => $booking['roomtotal'],
            'btot' => $booking['bedtotal'],
            'mepr' => $booking['mealtotal'],
            'fintot' => $booking['finaltotal'],
        ];
        return view('admin/invoiceprint', $data);
    }

    public function exportdata()
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $roombook_record = $this->roombookModel->findAll();
        if ($this->request->getPost('exportexcel')) {
            $filename = "bluebird_roombook_data_" . date('Ymd') . ".xls";
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            $show_column = false;
            if (!empty($roombook_record)) {
                foreach ($roombook_record as $record) {
                    if (!$show_column) {
                        echo implode("\t", array_keys($record)) . "\n";
                        $show_column = true;
                    }
                    echo implode("\t", array_values($record)) . "\n";
                }
            }
            exit;
        }
        return redirect()->to(base_url('admin/roombook'));
    }

    public function roomconfirm($id)
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $booking = $this->roombookModel->find($id);
        if (!$booking) {
            return redirect()->to(base_url('admin/roombook'));
        }

        if ($booking['stat'] == "NotConfirm") {
            $data = ['stat' => 'Confirm'];
            $this->roombookModel->update($id, $data);

            $type_of_room = 0;
            if ($booking['RoomType'] == "Superior Room") {
                $type_of_room = 3000;
            } else if ($booking['RoomType'] == "Deluxe Room") {
                $type_of_room = 2000;
            } else if ($booking['RoomType'] == "Guest House") {
                $type_of_room = 1500;
            } else if ($booking['RoomType'] == "Single Room") {
                $type_of_room = 1000;
            }

            $type_of_bed = 0;
            if ($booking['Bed'] == "Single") {
                $type_of_bed = $type_of_room * 1 / 100;
            } else if ($booking['Bed'] == "Double") {
                $type_of_bed = $type_of_room * 2 / 100;
            } else if ($booking['Bed'] == "Triple") {
                $type_of_bed = $type_of_room * 3 / 100;
            } else if ($booking['Bed'] == "Quad") {
                $type_of_bed = $type_of_room * 4 / 100;
            } else if ($booking['Bed'] == "None") {
                $type_of_bed = $type_of_room * 0 / 100;
            }

            $type_of_meal = 0;
            if ($booking['Meal'] == "Room only") {
                $type_of_meal = $type_of_bed * 0;
            } else if ($booking['Meal'] == "Breakfast") {
                $type_of_meal = $type_of_bed * 2;
            } else if ($booking['Meal'] == "Half Board") {
                $type_of_meal = $type_of_bed * 3;
            } else if ($booking['Meal'] == "Full Board") {
                $type_of_meal = $type_of_bed * 4;
            }

            $ttot = $type_of_room * $booking['nodays'] * $booking['NoofRoom'];
            $mepr = $type_of_meal * $booking['nodays'];
            $btot = $type_of_bed * $booking['nodays'];
            $fintot = $ttot + $mepr + $btot;

            $paymentData = [
                'Name' => $booking['Name'],
                'Email' => $booking['Email'],
                'RoomType' => $booking['RoomType'],
                'Bed' => $booking['Bed'],
                'NoofRoom' => $booking['NoofRoom'],
                'cin' => $booking['cin'],
                'cout' => $booking['cout'],
                'noofdays' => $booking['nodays'],
                'roomtotal' => $ttot,
                'bedtotal' => $btot,
                'meal' => $booking['Meal'],
                'mealtotal' => $mepr,
                'finaltotal' => $fintot,
            ];

            $this->paymentModel->insert($paymentData);
            return redirect()->to(base_url('admin/roombook'));
        } else {
            $this->session->setFlashdata('error', 'Guest Already Confirmed');
            return redirect()->to(base_url('admin/roombook'));
        }
    }

    public function roombookedit($id)
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $booking = $this->roombookModel->find($id);
        if (!$booking) {
            return redirect()->to(base_url('admin/roombook'));
        }

        $data = [
            'title' => 'SKY Hotel - Edit Room Booking',
            'booking' => $booking,
        ];
        return view('admin/roombookedit', $data);
    }

    public function roombookupdate($id)
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

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

            $this->roombookModel->update($id, $data);
            return redirect()->to(base_url('admin/roombook'));
        }
        return redirect()->to(base_url('admin/roombook'));
    }

    public function staffdelete($id)
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        $this->staffModel->delete($id);
        return redirect()->to(base_url('admin/staff'));
    }

    public function addstaff()
    {
        // Check if user is logged in and is staff
        if (!$this->session->has('usermail') || !$this->session->get('isStaff')) {
            return redirect()->to(base_url('/'));
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'name' => $this->request->getPost('staffname'),
                'work' => $this->request->getPost('staffwork'),
            ];
            if ($this->staffModel->insert($data)) {
                return redirect()->to(base_url('admin/staff'));
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