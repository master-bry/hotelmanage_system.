<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\RoombookModel;


class Auth extends Controller
{
    protected $session;
    protected $userModel;
    protected $roombookModel;

    public function __construct()
{
    $this->session = \Config\Services::session();
    $this->roombookModel = new RoombookModel();
    
    // Check if user is logged in and is NOT staff
    if (!$this->session->has('usermail') || $this->session->get('isStaff') == 1) {
        return redirect()->to(base_url('/'));
    }
}

    public function index()
    {
        // If already logged in, redirect appropriately
        if ($this->session->has('usermail')) {
            if ($this->session->get('isStaff')) {
                return redirect()->to(base_url('admin'));
            } else {
                return redirect()->to(base_url('home'));
            }
        }

        $data = [
            'title' => 'SKY Hotel - Login/Signup',
        ];
        return view('auth', $data);
    }

    public function getSignupForm()
    {
        $html = '
            <h2>Sign Up</h2>
            <form action="' . base_url('auth/signup') . '" method="POST">
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
                <button type="submit" name="signup_submit" class="auth_btn">Sign Up</button>
                <div class="footer_line">
                    <h6>Already have an account? <span class="page_move_btn">Log In</span></h6>
                </div>
            </form>
        ';
        
        return $this->response->setBody($html);
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

        // Check login using the new users table
        $user = $this->userModel->checkLogin($email, $password);
        
        if ($user) {
            $this->session->set('usermail', $user['Email']);
            $this->session->set('user_id', $user['id']);
            $this->session->set('isStaff', $user['is_staff']);
            
            if ($user['is_staff'] == 1) {
                return redirect()->to(base_url('admin'));
            } else {
                return redirect()->to(base_url('home'));
            }
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
            'Email' => 'required|valid_email|is_unique[users.Email]',
            'Password' => 'required|min_length[6]',
            'CPassword' => 'required|matches[Password]',
        ];
        
        if (!$this->validate($rules)) {
            $this->session->setFlashdata('error', implode(', ', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $data = [
            'Email' => $this->request->getPost('Email'),
            'Password' => password_hash($this->request->getPost('Password'), PASSWORD_BCRYPT),
            'is_staff' => 0, // Regular user
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        if ($this->userModel->insert($data)) {
            $this->session->set('usermail', $data['Email']);
            $this->session->set('user_id', $this->userModel->getInsertID());
            $this->session->set('isStaff', 0);
            
            $this->session->setFlashdata('success', 'Account created successfully!');
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