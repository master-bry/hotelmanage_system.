<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblRooms extends Migration
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
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
            ],
            'bedding' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['available', 'occupied', 'maintenance'],
                'default' => 'available',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('rooms', true);
    }

    public function down()
    {
        $this->forge->dropTable('rooms', true);
    }
}
