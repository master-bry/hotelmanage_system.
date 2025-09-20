<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblRoom extends Migration
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
                'constraint' => '100',
            ],
            'bedding' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('room');
    }

    public function down()
    {
        $this->forge->dropTable('room');
    }
}
