<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username', 'email', 'password', 'is_staff', 'is_verified',
        'verification_code', 'verification_expires', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $beforeInsert = ['hashPassword', 'generateVerificationCode'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']);
        }
        return $data;
    }

    protected function generateVerificationCode(array $data)
    {
        $data['data']['verification_code'] = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $data['data']['verification_expires'] = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        $data['data']['is_verified'] = 0;
        
        return $data;
    }

    public function verifyUser($email, $code)
    {
        $user = $this->where('email', $email)
                    ->where('verification_code', $code)
                    ->where('verification_expires >', date('Y-m-d H:i:s'))
                    ->first();

        if ($user) {
            return $this->update($user['id'], [
                'is_verified' => 1,
                'verification_code' => null,
                'verification_expires' => null
            ]);
        }

        return false;
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function isVerified($email)
    {
        $user = $this->where('email', $email)->first();
        return $user ? $user['is_verified'] == 1 : false;
    }
}