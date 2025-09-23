<?php
namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table = 'staff';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'position', 'department', 'created_at'];
    protected $useTimestamps = false;

    public function getStaffByDepartment($department = null)
    {
        if ($department) {
            return $this->where('department', $department)->findAll();
        }
        return $this->findAll();
    }
}