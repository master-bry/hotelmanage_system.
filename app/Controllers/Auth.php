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
            $this->session->setTempdata('pending_email', $email, 3600);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please verify your email first',
                'redirect' => base_url('auth/verify')
            ]);
        }

        if ($userType === 'staff' && $user['is_staff'] != 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Staff access required. Please use staff login.'
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

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'is_staff' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->insert($userData)) {
            $userId = $this->userModel->getInsertID();
            $user = $this->userModel->find($userId);
            $brevo = service('brevo');

            $htmlContent = view('verification', [
                'userName' => $userData['username'],
                'verificationCode' => $user['verification_code'],
                'year' => date('Y')
            ]);

            $result = $brevo->sendEmail(
                $userData['email'],
                'Verify Your Email Address',
                $htmlContent
            );

            if (!$result['success']) {
                log_message('error', 'Failed to send verification email to ' . $userData['email'] . ': ' . $result['error']);
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

            if ($this->userModel->verifyUser($email, $code)) {
                $user = $this->userModel->where('email', $email)->first();
                $this->session->removeTempdata('pending_email');
                $this->session->set([
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'is_staff' => $user['is_staff'],
                    'logged_in' => true
                ]);

                $this->session->setFlashdata('success', 'Email verified successfully');
                return redirect()->to($user['is_staff'] ? base_url('admin') : base_url('home'));
            }

            $this->session->setFlashdata('error', 'Invalid verification code');
            return redirect()->to('auth/verify');
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

        $resendKey = 'resend_' . md5($email);
        $resendCount = $this->session->getTempdata($resendKey) ?? 0;
        if ($resendCount >= 3) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Too many resend attempts. Please try again later.'
            ]);
        }

        $user = $this->userModel->where('email', $email)->first();
        if (!$user || $user['is_verified'] == 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email is already verified or invalid.'
            ]);
        }

        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->userModel->update($user['id'], [
            'verification_code' => $verificationCode,
            'verification_expires' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
        ]);

        $brevo = service('brevo');
        $htmlContent = view('verification', [
            'userName' => $user['username'],
            'verificationCode' => $verificationCode,
            'year' => date('Y')
        ]);

        $result = $brevo->sendEmail($email, 'Verify Your Email Address', $htmlContent);

        if (!$result['success']) {
            log_message('error', 'Failed to resend verification email to ' . $email . ': ' . $result['error']);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to resend verification email'
            ]);
        }

        $this->session->setTempdata($resendKey, $resendCount + 1, 3600);
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