<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblStaff extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'position' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('staff', true);
    }

    public function down()
    {
        $this->forge->dropTable('staff', true);
    }
}
