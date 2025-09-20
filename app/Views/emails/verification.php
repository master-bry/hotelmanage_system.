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

        log_message('debug', 'Signup attempt: ' . print_r($this->request->getPost(), true));
        
        $rules = [
            'Email' => 'required|valid_email|is_unique[signup.Email]',
            'Password' => 'required|min_length[6]',
            'CPassword' => 'required|matches[Password]',
        ];
        if (!$this->validate($rules)) {
            log_message('error', 'Validation failed: ' . implode(', ', $this->validator->getErrors()));
            $this->session->setFlashdata('error', implode(', ', $this->validator->getErrors()));
            return $this->response->setJSON(['success' => false, 'message' => implode(', ', $this->validator->getErrors())]);
        }

        $data = [
            'Email' => $this->request->getPost('Email'),
            'Password' => password_hash($this->request->getPost('Password'), PASSWORD_DEFAULT),
        ];
        log_message('debug', 'Data to insert: ' . print_r($data, true));
        if ($this->userModel->insert($data)) {
            log_message('debug', 'User inserted successfully for email: ' . $data['Email']);
            $this->session->set('usermail', $data['Email']);
            $this->session->set('isStaff', 0);

            $this->email->setFrom($this->email->fromEmail, $this->email->fromName);
            $this->email->setTo($data['Email']);
            $this->email->setSubject('Welcome to SkyBird Hotel');
            $this->email->setMessage('Thank you for registering with SkyBird Hotel. Your account has been created successfully!');
            if (!$this->email->send()) {
                log_message('error', 'Email sending failed: ' . $this->email->printDebugger());
            } else {
                log_message('debug', 'Email sent successfully to: ' . $data['Email']);
            }

            return $this->response->setJSON(['success' => true, 'redirect' => base_url('home')]);
        }

        log_message('error', 'Failed to insert user');
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to create account']);
    }

    public function getSignupForm()
    {
        log_message('debug', 'Fetching signup form via AJAX');
        $html = '<div class="authsection user_signup">
                    <h2>Sign Up</h2>
                    <form id="signupForm" action="' . base_url('signup') . '" method="POST">
                        <input type="hidden" name="' . csrf_token() . '" value="' . csrf_hash() . '">
                        <div class="form-floating">
                            <input type="email" class="form-control" name="Email" placeholder=" " required>
                            <label for="Email">Email</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" name="Password" placeholder=" " required>
                            <label for="Password">Password</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" name="CPassword" placeholder=" " required>
                            <label for="CPassword">Confirm Password</label>
                        </div>
                        <button type="submit" class="auth_btn">Sign Up</button>
                        <div class="footer_line">
                            <h6>Already have an account? <span class="page_move_btn">Log In</span></h6>
                        </div>
                    </form>
                </div>';
        return $this->response->setJSON(['html' => $html]);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }
}