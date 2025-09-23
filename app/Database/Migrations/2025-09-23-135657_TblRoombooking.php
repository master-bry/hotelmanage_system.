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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'room_type' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
            ],
            'bed_type' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
            ],
            'room_count' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 1,
            ],
            'meal_plan' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'Room only',
            ],
            'check_in' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'check_out' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'days' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'cancelled'],
                'default' => 'pending',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('room_bookings', true);
    }

    public function down()
    {
        $this->forge->dropTable('room_bookings', true);
    }
}
