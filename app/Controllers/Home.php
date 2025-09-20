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

        $data = [
            'Name' => $this->request->getPost('name'),
            'Email' => $this->request->getPost('email'),
            'Country' => '',
            'Phone' => '',
            'RoomType' => $this->request->getPost('room_type'),
            'Bed' => 'None',
            'NoofRoom' => 1,
            'Meal' => 'Room only',
            'cin' => $this->request->getPost('check_in'),
            'cout' => $this->request->getPost('check_out'),
            'nodays' => (new \DateTime($this->request->getPost('check_in')))->diff(new \DateTime($this->request->getPost('check_out')))->days,
            'stat' => 'NotConfirm',
        ];

        if ($this->roombookModel->save($data)) {
            $this->session->setFlashdata('success', 'Booking request submitted successfully');
        } else {
            $this->session->setFlashdata('error', implode(', ', $this->roombookModel->errors()));
        }
        return redirect()->to(base_url('home'));
    }
}