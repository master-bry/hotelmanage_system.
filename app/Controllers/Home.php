<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RoombookModel;

class Home extends Controller
{
    protected $session;
    protected $roombookModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->roombookModel = new RoombookModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'SkyBird Hotel - Welcome',
            'user' => [
                'name' => $this->session->get('username'),
                'email' => $this->session->get('email')
            ]
        ];

        return view('home', $data);
    }

    public function book()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('home');
        }

        $bookingData = [
            'user_id' => $this->session->get('user_id'),
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
            'status' => 'pending'
        ];

        if ($this->roombookModel->insert($bookingData)) {
            $this->session->setFlashdata('success', 'Booking request submitted successfully!');
        } else {
            $this->session->setFlashdata('error', 'Failed to submit booking. Please try again.');
        }

        return redirect()->to('home');
    }

    private function calculateDays($checkIn, $checkOut)
    {
        $start = new \DateTime($checkIn);
        $end = new \DateTime($checkOut);
        return $start->diff($end)->days;
    }
}