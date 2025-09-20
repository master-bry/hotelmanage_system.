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
            'Password' => 'required|min_length[4]',
        ];
        if (!$this->validate($rules)) {
            $this->session->setFlashdata('error', 'Invalid email or password format');
            return redirect()->back()->withInput();
        }

        // Check in signup table (regular users)
        $user = $this->userModel->where('Email', $email)->first();
        if ($user && $password === $user['Password']) { // Simple comparison for existing data
            $this->session->set('usermail', $email);
            $this->session->set('user_id', $user['UserID']);
            $this->session->set('isStaff', 0);
            return redirect()->to(base_url('home'));
        }

        // Check in emp_login table (staff/admin)
        $staff = $this->userModel->checkStaffLogin($email, $password);
        if ($staff) {
            $this->session->set('usermail', $email);
            $this->session->set('user_id', $staff['empid']);
            $this->session->set('isStaff', 1);
            return redirect()->to(base_url('admin'));
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
            'Password' => 'required|min_length[4]',
            'CPassword' => 'required|matches[Password]',
        ];
        if (!$this->validate($rules)) {
            $this->session->setFlashdata('error', implode(', ', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $data = [
            'Email' => $this->request->getPost('Email'),
            'Password' => $this->request->getPost('Password'), // Store plain text for compatibility
            'Username' => explode('@', $this->request->getPost('Email'))[0], // Generate username from email
        ];
        
        if ($this->userModel->insertSignup($data)) {
            $this->session->set('usermail', $data['Email']);
            $this->session->set('user_id', $this->userModel->getInsertID());
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