<?php
namespace App\Libraries;

use CodeIgniter\Email\Email;

class EmailService
{
    protected $email;
    protected $config;

    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->config = config('Email');
    }

    public function sendVerificationEmail($userEmail, $userName, $verificationCode)
    {
        $data = [
            'userName' => $userName,
            'verificationCode' => $verificationCode,
            'year' => date('Y')
        ];

        $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
        $this->email->setTo($userEmail);
        $this->email->setSubject('Email Verification - SkyBird Hotel');
        
        $message = view('emails/verification', $data);
        $this->email->setMessage($message);

        if ($this->email->send()) {
            return true;
        } else {
            log_message('error', 'Email sending failed: ' . $this->email->printDebugger(['headers']));
            return false;
        }
    }

    public function sendWelcomeEmail($userEmail, $userName)
    {
        $data = [
            'userName' => $userName,
            'year' => date('Y')
        ];

        $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
        $this->email->setTo($userEmail);
        $this->email->setSubject('Welcome to SkyBird Hotel!');
        
        $message = view('emails/welcome', $data);
        $this->email->setMessage($message);

        return $this->email->send();
    }
}