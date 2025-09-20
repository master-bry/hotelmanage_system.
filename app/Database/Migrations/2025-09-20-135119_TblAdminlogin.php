<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblAdminlogin extends Migration
{
public function up()
{
    $this->forge->addField([
        'id' => [
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => true,
            'auto_increment' => true,
        ],
        'Email' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
            'unique' => true,
        ],
        'Password' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
        ],
        'verification_code' => [ // Add this field
            'type' => 'VARCHAR',
            'constraint' => 6,
            'null' => true,
        ],
        'is_verified' => [ // Add this field
            'type' => 'TINYINT',
            'constraint' => 1,
            'default' => 0,
        ],
        'is_staff' => [
            'type' => 'TINYINT',
            'constraint' => 1,
            'default' => 0,
            'comment' => '0=Regular user, 1=Staff/Admin',
        ],
        'created_at' => [
            'type' => 'DATETIME',
            'null' => true,
        ],
        'updated_at' => [
            'type' => 'DATETIME',
            'null' => true,
        ],
    ]);
    $this->forge->addPrimaryKey('id');
    $this->forge->createTable('users');
}

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
