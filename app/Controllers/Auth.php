<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class Auth extends Controller
{
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'SKY Hotel - Login/Signup',
        ];
        return view('auth', $data);
    }

    public function doLogin()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('/'));
        }

        $email = $this->request->getPost('Email');
        $password = $this->request->getPost('Password');

        $rules = [
            'Email' => 'required|valid_email',
            'Password' => 'required|min_length[6]',
        ];
        if (!$this->validate($rules)) {
            $this->session->setFlashdata('error', 'Invalid email or password format');
            return redirect()->back()->withInput();
        }

        $user = $this->userModel->where('Email', $email)->first();
        if ($user && password_verify($password, $user['Password'])) {
            $this->session->set('usermail', $email);
            $isStaff = ($email === 'admin@skybird.com');
            $this->session->set('isStaff', $isStaff);
            return redirect()->to(base_url('home'));
        }

        $this->session->setFlashdata('error', 'Invalid email or password');
        return redirect()->back()->withInput();
    }

    public function signup()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('/'));
        }

        $rules = [
            'Username' => 'required|min_length[3]|max_length[50]',
            'Email' => 'required|valid_email|is_unique[signup.Email]',
            'Password' => 'required|min_length[6]',
            'CPassword' => 'required|matches[Password]',
        ];
        if (!$this->validate($rules)) {
            $this->session->setFlashdata('error', implode(', ', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $data = [
            'Username' => $this->request->getPost('Username'),
            'Email' => $this->request->getPost('Email'),
            'Password' => password_hash($this->request->getPost('Password'), PASSWORD_DEFAULT),
        ];
        if ($this->userModel->insert($data)) {
            $this->session->set('usermail', $data['Email']);
            $this->session->set('isStaff', 0);
            return redirect()->to(base_url('home'));
        }

        $this->session->setFlashdata('error', 'Failed to create account');
        return redirect()->back()->withInput();
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }
}