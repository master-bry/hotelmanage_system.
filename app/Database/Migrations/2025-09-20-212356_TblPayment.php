<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblPayment extends Migration
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
            'Name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'RoomType' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'Bed' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'cin' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'cout' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'noofdays' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'NoofRoom' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'meal' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'roomtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'bedtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'mealtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'finaltotal' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('payment');
    }

    public function down()
    {
        $this->forge->dropTable('payment');
    }
}
