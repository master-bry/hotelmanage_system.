<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'signup';
    protected $primaryKey = 'UserID'; // Changed from 'id' to 'UserID'
    protected $allowedFields = ['Username', 'Email', 'Password'];
    protected $useTimestamps = false;
    protected $validationRules = [
        'Email' => 'required|valid_email|is_unique[signup.Email,UserID,{UserID}]',
    ];
    
    // Check staff login from emp_login table
    public function checkStaffLogin($email, $password)
    {
        $staff = $this->db->table('emp_login')
            ->where('Emp_Email', $email)
            ->where('Emp_Password', $password)
            ->get()
            ->getRowArray();
            
        return $staff;
    }
    
    // Insert into signup table
    public function insertSignup($data)
    {
        return $this->db->table('signup')->insert($data);
    }
    
    // Get inserted ID
    public function getInsertID()
    {
        return $this->db->insertID();
    }
}