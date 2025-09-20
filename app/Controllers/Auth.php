<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

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
        if ($this->session->has('usermail')) {
            if ($this->session->get('isStaff')) {
                return redirect()->to(base_url('admin'));
            } else {
                return redirect()->to(base_url('home'));
            }
        }

        log_message('debug', 'Rendering auth view');
        return view('auth', ['title' => 'SKY Hotel - Login/Signup']);
    }

    public function ajaxLogin()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }

        log_message('debug', 'AJAX login attempt: ' . print_r($this->request->getPost(), true));
        $email = $this->request->getPost('Email');
        $password = $this->request->getPost('Password');
        $loginType = $this->request->getPost('login_type');

        $rules = [
            'Email' => 'required|valid_email',
            'Password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Login validation failed: Invalid email or password format from IP: ' . $this->request->getIPAddress());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Invalid email or password format'
            ]);
        }

        $user = $this->userModel->checkLogin($email, $password);

        if ($user) {
            log_message('debug', 'User found: ' . $user['Email']);
            if (($loginType === 'staff' && $user['is_staff'] != 1) || 
                ($loginType === 'user' && $user['is_staff'] == 1)) {
                log_message('error', 'Login type mismatch for ' . $user['Email'] . ' from IP: ' . $this->request->getIPAddress());
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Invalid login type'
                ]);
            }

            if (!$user['is_verified']) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Please verify your email first',
                    'redirect' => base_url('auth/verify')
                ]);
            }

            $this->session->set([
                'usermail' => $user['Email'],
                'user_id' => $user['id'],
                'isStaff' => $user['is_staff']
            ]);
            $redirect = $user['is_staff'] ? base_url('admin') : base_url('home');
            log_message('debug', 'Login successful for ' . $user['Email'] . ', redirecting to ' . $redirect);
            return $this->response->setJSON([
                'success' => true,
                'redirect' => $redirect
            ]);
        }

        log_message('error', 'Login failed for email: ' . $email . ' from IP: ' . $this->request->getIPAddress());
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Invalid email or password'
        ]);
    }

    public function ajaxSignup()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }

        log_message('debug', 'Signup attempt: ' . print_r($this->request->getPost(), true));
        $rules = [
            'Username' => 'required|min_length[3]',
            'Email' => 'required|valid_email|is_unique[users.Email]',
            'Password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
            'CPassword' => 'required|matches[Password]',
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Signup validation failed: ' . implode(', ', $this->validator->getErrors()) . ' from IP: ' . $this->request->getIPAddress());
            return $this->response->setJSON([
                'success' => false,
                'error' => implode(', ', $this->validator->getErrors())
            ]);
        }

        $verificationCode = bin2hex(random_bytes(16));
        $data = [
            'Username' => $this->request->getPost('Username'),
            'Email' => $this->request->getPost('Email'),
            'Password' => password_hash($this->request->getPost('Password'), PASSWORD_BCRYPT),
            'is_staff' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'verification_code' => $verificationCode
        ];

        log_message('debug', 'Data to insert: ' . print_r($data, true));
        if ($this->userModel->insert($data)) {
            log_message('debug', 'User inserted successfully for email: ' . $data['Email']);
            $this->session->set([
                'usermail' => $data['Email'],
                'user_id' => $this->userModel->getInsertID(),
                'isStaff' => 0
            ]);

            $this->email->setTo($data['Email']);
            $this->email->setSubject('Verify Your Email');
            $this->email->setMessage("Please use this code to verify your email: $verificationCode");
            if (!$this->email->send()) {
                log_message('error', 'Failed to send verification email to ' . $data['Email'] . ': ' . $this->email->printDebugger());
            } else {
                log_message('debug', 'Verification email sent successfully to: ' . $data['Email']);
            }

            log_message('debug', 'Signup successful, redirecting to verify');
            return $this->response->setJSON([
                'success' => true,
                'redirect' => base_url('auth/verify')
            ]);
        }

        log_message('error', 'Failed to insert user for email: ' . $data['Email'] . ' from IP: ' . $this->request->getIPAddress());
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Failed to create account'
        ]);
    }

    public function verify()
    {
        if ($this->request->getMethod() === 'post') {
            $code = $this->request->getPost('verification_code');
            $email = $this->session->get('usermail');
            $user = $this->userModel->where('Email', $email)->first();

            if ($user && $user['verification_code'] === $code) {
                $this->userModel->update($user['id'], ['is_verified' => 1, 'verification_code' => null]);
                $this->session->setFlashdata('success', 'Email verified successfully!');
                return redirect()->to(base_url('home'));
            } else {
                $this->session->setFlashdata('error', 'Invalid verification code');
                return redirect()->back();
            }
        }

        return view('verify', ['title' => 'Verify Your Email']);
    }

    public function logout()
    {
        log_message('debug', 'User logged out from IP: ' . $this->request->getIPAddress());
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }
}