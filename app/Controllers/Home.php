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
        
        // Check if user is logged in
        if (!$this->session->has('usermail')) {
            return redirect()->to(base_url('/'));
        }
    }

    public function index()
    {
        $data = [
            'title' => 'SkyBird Hotel - Welcome',
        ];
        return view('home', $data);
    }

    public function book()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('/'));
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'room_type' => 'required|in_list[Superior Room,Deluxe Room,Guest House,Single Room]',
            'check_in' => 'required|valid_date',
            'check_out' => 'required|valid_date',
        ];
        if (!$this->validate($rules)) {
            $this->session->setFlashdata('error', implode(', ', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        // Calculate nodays
        $cin = $this->request->getPost('check_in');
        $cout = $this->request->getPost('check_out');
        $nodays = (new \DateTime($cin))->diff(new \DateTime($cout))->days;

        $data = [
            'Name' => $this->request->getPost('name'),
            'Email' => $this->request->getPost('email'),
            'Country' => '', // Optional, can be added later
            'Phone' => '', // Optional
            'RoomType' => $this->request->getPost('room_type'),
            'Bed' => 'None', // Default, can be added to form later
            'NoofRoom' => 1, // Default
            'Meal' => 'Room only', // Default
            'cin' => $cin,
            'cout' => $cout,
            'nodays' => $nodays,
            'stat' => 'NotConfirm',
        ];
        if ($this->roombookModel->insert($data)) {
            $this->session->setFlashdata('success', 'Booking request submitted successfully');
        } else {
            $this->session->setFlashdata('error', 'Failed to submit booking request');
        }
        return redirect()->to(base_url('home'));
    }
}