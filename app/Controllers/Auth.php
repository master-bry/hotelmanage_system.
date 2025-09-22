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
            return $this->session->get('isStaff') ? redirect()->to(base_url('admin')) : redirect()->to(base_url('home'));
        }
        return view('auth', ['title' => 'SKY Hotel - Login/Signup']);
    }

    public function ajaxLogin()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }

        $email = $this->request->getPost('Email');
        $password = $this->request->getPost('Password');
        $loginType = $this->request->getPost('login_type');

        $rules = [
            'Email' => 'required|valid_email',
            'Password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Invalid email or password'
            ]);
        }

        $user = $this->userModel->checkLogin($email, $password);
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Invalid email or password'
            ]);
        }

        if (($loginType === 'staff' && $user['is_staff'] != 1) || 
            ($loginType === 'user' && $user['is_staff'] == 1)) {
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

        return $this->response->setJSON([
            'success' => true,
            'redirect' => $user['is_staff'] ? base_url('admin') : base_url('home')
        ]);
    }

    public function ajaxSignup()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }

        $rules = [
            'Username' => 'required|min_length[3]',
            'Email' => 'required|valid_email|is_unique[users.Email]',
            'Password' => 'required|min_length[8]|regex_match[/^(?=.*[a-zA-Z])(?=.*\d).*$/]',
            'CPassword' => 'required|matches[Password]',
        ];

        if (!$this->validate($rules)) {
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

        if ($this->userModel->insert($data)) {
            $this->session->set([
                'usermail' => $data['Email'],
                'user_id' => $this->userModel->getInsertID(),
                'isStaff' => 0
            ]);

            $this->email->setTo($data['Email']);
            $this->email->setSubject('Verify Your Email');
            $this->email->setMessage("Please use this code to verify your email: $verificationCode");
            if (!$this->email->send()) {
                log_message('error', 'Failed to send verification email to ' . $data['Email']);
            }

            return $this->response->setJSON([
                'success' => true,
                'redirect' => base_url('auth/verify')
            ]);
        }

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
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }
}