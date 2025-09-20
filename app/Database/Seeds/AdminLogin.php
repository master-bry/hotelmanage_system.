<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminLogin extends Seeder
{
    public function run()
    {
        $data = [
            'Email'    => 'admin@skybirdhotel.co.tz',
            'Password' => password_hash('Admin@123', PASSWORD_BCRYPT),
            'is_staff' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($data);
    }
}
