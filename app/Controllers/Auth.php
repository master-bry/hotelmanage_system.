<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class Auth extends Controller
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
    }

    public function index()
    {
        // Redirect if already logged in
        if ($this->session->get('user_id')) {
            return $this->session->get('is_staff') 
                ? redirect()->to('admin') 
                : redirect()->to('home');
        }

        $data = [
            'title' => 'SkyBird Hotel - Login',
            'csrf_token' => csrf_hash()
        ];

        return view('auth', $data);
    }

    public function login()
    {
        // Only allow POST requests
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        // Validation rules
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
            'user_type' => 'required|in_list[user,staff]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please check your input',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $userType = $this->request->getPost('user_type');

        // Check credentials
        $user = $this->userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid email or password'
            ]);
        }

        // Check user type
        $isStaff = $user['is_staff'] == 1;
        if (($userType === 'staff' && !$isStaff) || ($userType === 'user' && $isStaff)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid login type for this account'
            ]);
        }

        // Set session
        $sessionData = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'is_staff' => $isStaff,
            'logged_in' => true
        ];

        $this->session->set($sessionData);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => $isStaff ? base_url('admin') : base_url('home')
        ]);
    }

    public function register()
    {
        // Only allow POST requests
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        // Validation rules
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please check your input',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_staff' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->insert($userData)) {
            // Auto-login after registration
            $userId = $this->userModel->getInsertID();
            
            $sessionData = [
                'user_id' => $userId,
                'username' => $userData['username'],
                'email' => $userData['email'],
                'is_staff' => 0,
                'logged_in' => true
            ];

            $this->session->set($sessionData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Registration successful',
                'redirect' => base_url('home')
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Registration failed. Please try again.'
        ]);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }
}