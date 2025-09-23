<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use CodeIgniter\Email\Email;

class Auth extends Controller
{
    protected $userModel;
    protected $session;
    protected $email;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        $this->email = \Config\Services::email();
        helper(['form', 'url']);
    }

    public function index()
    {
        if ($this->session->get('user_id')) {
            log_message('debug', 'Found residual session: ' . print_r($this->session->get(), true));
            $this->session->destroy();
            setcookie('skybird_session', '', time() - 3600, '/');
        }

        $data = [
            'title' => 'SkyBird Hotel - Login',
            'csrf_token' => csrf_hash()
        ];

        return view('auth', $data);
    }

    public function login()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

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

        $user = $this->userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid email or password'
            ]);
        }

        $isVerified = isset($user['is_verified']) ? $user['is_verified'] : 1;
        if ($isVerified == 0) {
            // Store email in session for resend option
            $this->session->setTempdata('pending_email', $email, 3600);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please verify your email first',
                'redirect' => base_url('auth/verify')
            ]);
        }

        if (($userType === 'staff' && $user['is_staff'] != 1) || 
            ($userType === 'user' && $user['is_staff'] == 1)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid user type'
            ]);
        }

        $sessionData = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'is_staff' => $user['is_staff'],
            'logged_in' => true
        ];

        $this->session->set($sessionData);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => $user['is_staff'] ? base_url('admin') : base_url('home')
        ]);
    }

    public function register()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-zA-Z])(?=.*\d).*$/]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please check your input',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $verificationCode = sprintf("%06d", mt_rand(100000, 999999));

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'is_staff' => 0,
            'is_verified' => 0,
            'verification_code' => $verificationCode,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->insert($userData)) {
            $userId = $this->userModel->getInsertID();

            $this->email->setFrom('no-reply@skybird.com', 'SkyBird Hotel');
            $this->email->setTo($userData['email']);
            $this->email->setSubject('Verify Your Email Address');
            $this->email->setMessage("Your verification code is: <b>$verificationCode</b><br>Please enter this code to verify your email.");
            
            if (!$this->email->send()) {
                log_message('error', 'Failed to send verification email to ' . $userData['email']);
                $this->userModel->delete($userId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to send verification email'
                ]);
            }

            $this->session->setTempdata('pending_email', $userData['email'], 3600);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Please check your email for the verification code',
                'redirect' => base_url('auth/verify')
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Registration failed. Please try again.'
        ]);
    }

    public function verify()
    {
        if ($this->request->is('post')) {
            $rules = [
                'verification_code' => 'required|exact_length[6]|numeric'
            ];

            if (!$this->validate($rules)) {
                $this->session->setFlashdata('error', 'Invalid verification code');
                return redirect()->to('auth/verify');
            }

            $code = $this->request->getPost('verification_code');
            $email = $this->session->getTempdata('pending_email');

            if (!$email) {
                $this->session->setFlashdata('error', 'Verification session expired');
                return redirect()->to('/');
            }

            $user = $this->userModel->where('email', $email)
                                   ->where('verification_code', $code)
                                   ->first();

            if (!$user) {
                $this->session->setFlashdata('error', 'Invalid verification code');
                return redirect()->to('auth/verify');
            }

            $this->userModel->update($user['id'], [
                'is_verified' => 1,
                'verification_code' => null
            ]);

            $this->session->removeTempdata('pending_email');
            $this->session->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'is_staff' => $user['is_staff'],
                'logged_in' => true
            ]);

            $this->session->setFlashdata('success', 'Email verified successfully');
            return redirect()->to('home');
        }

        $data = [
            'title' => 'Verify Your Email',
            'csrf_token' => csrf_hash()
        ];
        return view('email', $data);
    }

    public function resend()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $email = $this->session->getTempdata('pending_email');

        if (!$email) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Verification session expired. Please try logging in again.'
            ]);
        }

        $user = $this->userModel->where('email', $email)->first();

        if (!$user || $user['is_verified'] == 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email is already verified or invalid.'
            ]);
        }

        $verificationCode = sprintf("%06d", mt_rand(100000, 999999));

        $this->userModel->update($user['id'], [
            'verification_code' => $verificationCode
        ]);

        $this->email->setFrom('no-reply@skybird.com', 'SkyBird Hotel');
        $this->email->setTo($email);
        $this->email->setSubject('Verify Your Email Address');
        $this->email->setMessage("Your new verification code is: <b>$verificationCode</b><br>Please enter this code to verify your email.");

        if (!$this->email->send()) {
            log_message('error', 'Failed to resend verification email to ' . $email);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to resend verification email'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Verification code resent. Please check your email.'
        ]);
    }

    public function logout()
    {
        $this->session->destroy();
        setcookie('skybird_session', '', time() - 3600, '/');
        return redirect()->to('/')->with('success', 'Logged out successfully');
    }
}