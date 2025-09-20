<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblRoombooking extends Migration
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
            ],
            'Email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'Country' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'Phone' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'RoomType' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'Bed' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'NoofRoom' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'Meal' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'cin' => [
                'type' => 'DATE',
            ],
            'cout' => [
                'type' => 'DATE',
            ],
            'nodays' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'stat' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'NotConfirm',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('roombook');
    }

    public function down()
    {
        $this->forge->dropTable('roombook');
    }
}
