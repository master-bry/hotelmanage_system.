<?php
namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $allowedFields = ['type', 'bedding', 'price', 'status', 'created_at'];
    protected $useTimestamps = false;

    public function getAvailableRooms()
    {
        return $this->where('status', 'available')->findAll();
    }

    public function getRoomPrice($type, $bedding)
    {
        $room = $this->where('type', $type)
                    ->where('bedding', $bedding)
                    ->first();
        return $room ? $room['price'] : 0;
    }
}