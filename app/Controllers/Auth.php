<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use CodeIgniter\Email\Email;

class Auth extends Controller
{
    protected $session;
    protected $userModel;
    protected $email;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = new UserModel();
        $this->email = \Config\Services::email();
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
            'Email' => 'required|valid_email|is_unique[signup.Email]',
            'Password' => 'required|min_length[6]',
            'CPassword' => 'required|matches[Password]',
        ];
        if (!$this->validate($rules)) {
            $this->session->setFlashdata('error', implode(', ', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $data = [
            'Email' => $this->request->getPost('Email'),
            'Password' => password_hash($this->request->getPost('Password'), PASSWORD_DEFAULT),
        ];
        if ($this->userModel->insert($data)) {
            $this->session->set('usermail', $data['Email']);
            $this->session->set('isStaff', 0);

            // Send confirmation email
            $this->email->setFrom($this->email->fromEmail, $this->email->fromName);
            $this->email->setTo($data['Email']);
            $this->email->setSubject('Welcome to SkyBird Hotel');
            $this->email->setMessage('Thank you for registering with SkyBird Hotel. Your account has been created successfully!');
            if (!$this->email->send()) {
                log_message('error', 'Email sending failed: ' . $this->email->printDebugger());
            }

            return redirect()->to(base_url('home'));
        }

        $this->session->setFlashdata('error', 'Failed to create account');
        return redirect()->back()->withInput();
    }

    public function getSignupForm()
    {
        return view('signup_form_partial');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }
}