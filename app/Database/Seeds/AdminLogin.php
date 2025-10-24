<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminLogin extends Seeder
{
    public function run()
    {
        $data = [

            [
                'username' => 'Admin User',
                'email' => 'admin@skybird.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'is_staff' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'John Doe',
                'email' => 'john@test.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'is_staff' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
