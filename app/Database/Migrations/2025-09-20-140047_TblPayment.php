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
                'constraint' => 30,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'Name' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'Email' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'RoomType' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'Bed' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'NoofRoom' => [
                'type' => 'INT',
                'constraint' => 30,
            ],
            'cin' => [
                'type' => 'DATE',
            ],
            'cout' => [
                'type' => 'DATE',
            ],
            'noofdays' => [
                'type' => 'INT',
                'constraint' => 30,
            ],
            'roomtotal' => [
                'type' => 'DOUBLE',
                'constraint' => '8,2',
            ],
            'bedtotal' => [
                'type' => 'DOUBLE',
                'constraint' => '8,2',
            ],
            'meal' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'mealtotal' => [
                'type' => 'DOUBLE',
                'constraint' => '8,2',
            ],
            'finaltotal' => [
                'type' => 'DOUBLE',
                'constraint' => '8,2',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('payment');
    }

    public function down()
    {
        $this->forge->dropTable('payment');
    }
}
