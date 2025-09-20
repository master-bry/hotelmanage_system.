<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Email', 'Password', 'is_staff', 'created_at', 'updated_at'];
    protected $useTimestamps = false;
    
    // Check user login (both regular users and staff)
public function checkLogin($email, $password)
{
    $user = $this->where('Email', $email)->first();
    
    if ($user && password_verify($password, $user['Password'])) {
        return $user;
    }
    
    return false;
}
    
    // Get user by email
    public function getByEmail($email)
    {
        return $this->where('Email', $email)->first();
    }
    
    // Check if email exists
    public function emailExists($email)
    {
        return $this->where('Email', $email)->countAllResults() > 0;
    }
}