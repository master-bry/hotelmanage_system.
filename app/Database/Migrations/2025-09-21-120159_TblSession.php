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
                'null' => false,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => false,
            ],
            'timestamp' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'data' => [
                'type' => 'BLOB',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('timestamp');
        $this->forge->createTable('ci_sessions');
    }

    public function down()
    {
        $this->forge->dropTable('ci_sessions');
    }
}
