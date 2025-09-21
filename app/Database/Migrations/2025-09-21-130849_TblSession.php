<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblSession extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'timestamp' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'data' => [
                'type' => 'BLOB',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('ci_sessions');
    }

    public function down()
    {
        $this->forge->dropTable('ci_sessions');
    }
}
