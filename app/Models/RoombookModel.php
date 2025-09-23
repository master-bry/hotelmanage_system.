<?php
namespace App\Models;

use CodeIgniter\Model;

class RoomBookModel extends Model
{
    protected $table = 'room_bookings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'name', 'email', 'phone', 'country', 'room_type', 
        'bed_type', 'room_count', 'meal_plan', 'check_in', 'check_out', 
        'days', 'status', 'created_at'
    ];
    protected $useTimestamps = false;

    protected $validationRules = [
        'name' => 'required|min_length[3]',
        'email' => 'required|valid_email',
        'room_type' => 'required',
        'bed_type' => 'required',
        'check_in' => 'required|valid_date',
        'check_out' => 'required|valid_date',
    ];

    public function getUserBookings($userId)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function getPendingBookings()
    {
        return $this->where('status', 'pending')
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function confirmBooking($bookingId)
    {
        return $this->update($bookingId, ['status' => 'confirmed']);
    }
}