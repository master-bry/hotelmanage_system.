<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'signup';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Username', 'Email', 'Password', 'isStaff']; // Added isStaff for role determination
    protected $useTimestamps = false;
    protected $validationRules = [
        'Email' => 'required|valid_email|is_unique[signup.Email,id,{id}]',
    ];
}