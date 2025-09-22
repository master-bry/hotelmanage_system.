<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Email', 'Password', 'Username', 'is_staff', 'created_at', 'updated_at', 'verification_code', 'is_verified'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Note: Ensure an index on Email for faster lookups:
    // CREATE INDEX idx_email ON users(Email);

    public function checkLogin($email, $password)
    {
        $user = $this->where('Email', $email)->first();
        if ($user && password_verify($password, $user['Password'])) {
            return $user;
        }
        return false;
    }

    public function getByEmail($email)
    {
        return $this->where('Email', $email)->first();
    }

    public function emailExists($email)
    {
        return $this->where('Email', $email)->countAllResults() > 0;
    }
}