<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UlterTable extends Migration
{
    public function up()
    {
        $fields = [
            'is_verified' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'is_staff',
            ],
            'verification_code' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
                'null' => true,
                'after' => 'is_verified',
            ],
            'verification_expires' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'verification_code',
            ],
        ];
        $this->forge->addColumn('users', $fields);

        $this->db->query('CREATE INDEX idx_email ON users(email)');
        $this->db->query('CREATE INDEX idx_verification_code ON users(verification_code)');

        // Set is_verified = 1 for all users
        $this->db->query('UPDATE users SET is_verified = 1');
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'is_verified');
        $this->forge->dropColumn('users', 'verification_code');
        $this->forge->dropColumn('users', 'verification_expires');
        $this->db->query('DROP INDEX idx_email ON users');
        $this->db->query('DROP INDEX idx_verification_code ON users');
        
    }
}
