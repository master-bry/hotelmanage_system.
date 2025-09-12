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
            return redirect()->to(base_url('auth'));
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
            $this->session->set('isStaff', $user['isStaff'] ?? 0);
            $redirectUrl = ($user['isStaff'] ?? 0) ? base_url('admin') : base_url('/');
            return redirect()->to($redirectUrl);
        }

        $this->session->setFlashdata('error', 'Invalid email or password');
        return redirect()->back()->withInput();
    }

    public function signup()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('auth'));
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
            'isStaff' => 0, // Default to user (non-staff)
        ];
        if ($this->userModel->insert($data)) {
            $this->session->set('usermail', $data['Email']);
            $this->session->set('isStaff', 0);
            $this->session->setFlashdata('success', 'Account created successfully. Welcome!');
            return redirect()->to(base_url('/'));
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