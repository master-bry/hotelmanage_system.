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
        helper(['form', 'url']);
    }

    public function index()
    {
        if ($this->session->has('usermail')) {
            return $this->session->get('isStaff') ? redirect()->to(base_url('admin')) : redirect()->to(base_url('home'));
        }
        return view('auth', ['title' => 'SKY Hotel - Login/Signup']);
    }

    public function ajaxLogin()
    {
        // Allow both AJAX and regular POST requests
        if ($this->request->getMethod() !== 'post') {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(405)->setJSON([
                    'success' => false, 
                    'error' => 'Method not allowed. Use POST.'
                ]);
            }
            return redirect()->to('/');
        }

        $email = $this->request->getPost('Email');
        $password = $this->request->getPost('Password');
        $loginType = $this->request->getPost('login_type');

        // Basic validation
        if (empty($email) || empty($password)) {
            $error = 'Email and password are required';
            return $this->request->isAJAX() 
                ? $this->response->setJSON(['success' => false, 'error' => $error])
                : redirect()->back()->with('error', $error);
        }

        $user = $this->userModel->checkLogin($email, $password);
        if (!$user) {
            $error = 'Invalid email or password';
            return $this->request->isAJAX() 
                ? $this->response->setJSON(['success' => false, 'error' => $error])
                : redirect()->back()->with('error', $error);
        }

        // Check login type
        if (($loginType === 'staff' && $user['is_staff'] != 1) || 
            ($loginType === 'user' && $user['is_staff'] == 1)) {
            $error = 'Invalid login type for this account';
            return $this->request->isAJAX() 
                ? $this->response->setJSON(['success' => false, 'error' => $error])
                : redirect()->back()->with('error', $error);
        }

        // Set session
        $this->session->set([
            'usermail' => $user['Email'],
            'user_id' => $user['id'],
            'isStaff' => $user['is_staff']
        ]);

        $redirectUrl = $user['is_staff'] ? base_url('admin') : base_url('home');
        
        return $this->request->isAJAX() 
            ? $this->response->setJSON(['success' => true, 'redirect' => $redirectUrl])
            : redirect()->to($redirectUrl);
    }

    public function ajaxSignup()
    {
        // Allow both AJAX and regular POST requests
        if ($this->request->getMethod() !== 'post') {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(405)->setJSON([
                    'success' => false, 
                    'error' => 'Method not allowed. Use POST.'
                ]);
            }
            return redirect()->to('/');
        }

        $data = [
            'Username' => $this->request->getPost('Username'),
            'Email' => $this->request->getPost('Email'),
            'Password' => password_hash($this->request->getPost('Password'), PASSWORD_BCRYPT),
            'is_staff' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Basic validation
        if (empty($data['Username']) || empty($data['Email']) || empty($this->request->getPost('Password'))) {
            $error = 'All fields are required';
            return $this->request->isAJAX() 
                ? $this->response->setJSON(['success' => false, 'error' => $error])
                : redirect()->back()->with('error', $error);
        }

        // Check if email exists
        if ($this->userModel->emailExists($data['Email'])) {
            $error = 'Email already exists';
            return $this->request->isAJAX() 
                ? $this->response->setJSON(['success' => false, 'error' => $error])
                : redirect()->back()->with('error', $error);
        }

        if ($this->userModel->insert($data)) {
            $this->session->set([
                'usermail' => $data['Email'],
                'user_id' => $this->userModel->getInsertID(),
                'isStaff' => 0
            ]);

            $redirectUrl = base_url('home');
            
            return $this->request->isAJAX() 
                ? $this->response->setJSON(['success' => true, 'redirect' => $redirectUrl])
                : redirect()->to($redirectUrl);
        }

        $error = 'Failed to create account';
        return $this->request->isAJAX() 
            ? $this->response->setJSON(['success' => false, 'error' => $error])
            : redirect()->back()->with('error', $error);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }
}