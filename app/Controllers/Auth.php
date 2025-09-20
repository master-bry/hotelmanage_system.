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
        $this->userModel = new UserModel();
        $this->roombookModel = new RoombookModel();
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

        $data = ['title' => 'SKY Hotel - Login/Signup'];
        return view('auth', $data);
    }

    // AJAX Login method
    public function ajaxLogin()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }

        $email = $this->request->getPost('Email');
        $password = $this->request->getPost('Password');

        $rules = [
            'Email' => 'required|valid_email',
            'Password' => 'required|min_length[6]',
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Invalid email or password format'
            ]);
        }

        $user = $this->userModel->checkLogin($email, $password);
        
        if ($user) {
            $this->session->set('usermail', $user['Email']);
            $this->session->set('user_id', $user['id']);
            $this->session->set('isStaff', $user['is_staff']);
            
            return $this->response->setJSON([
                'success' => true,
                'redirect' => $user['is_staff'] == 1 ? base_url('admin') : base_url('home')
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'error' => 'Invalid email or password'
        ]);
    }

    // AJAX Signup method
    public function ajaxSignup()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }

        $rules = [
            'Email' => 'required|valid_email|is_unique[users.Email]',
            'Password' => 'required|min_length[6]',
            'CPassword' => 'required|matches[Password]',
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => implode(', ', $this->validator->getErrors())
            ]);
        }

        $data = [
            'Email' => $this->request->getPost('Email'),
            'Password' => password_hash($this->request->getPost('Password'), PASSWORD_BCRYPT),
            'is_staff' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        if ($this->userModel->insert($data)) {
            $this->session->set('usermail', $data['Email']);
            $this->session->set('user_id', $this->userModel->getInsertID());
            $this->session->set('isStaff', 0);
            
            return $this->response->setJSON([
                'success' => true,
                'redirect' => base_url('home')
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'error' => 'Failed to create account'
        ]);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }
}