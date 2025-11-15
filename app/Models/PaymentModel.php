<?php
namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'booking_id', 'room_total', 'bed_total', 'meal_total', 
        'final_total', 'payment_status', 'created_at'
    ];
    protected $useTimestamps = false;

    public function calculateTotals($bookingData)
    {
        $roomModel = new \App\Models\RoomModel();
        
        // Room rates
        $roomRates = [
            'Superior Room' => 100000,
            'Deluxe Room' => 80000,
            'Guest House' => 50000,
            'Single Room' => 30000,
        ];

        // Bed rates
        $bedRates = [
            'Single' => 5000,
            'Double' => 10000,
            'None' => 0,
        ];

        // Meal rates
        $mealRates = [
            'Room only' => 0,
            'Breakfast' => 5000,
            'Half Board' => 10000,
            'Full Board' => 15000,
        ];

        $roomTotal = ($roomRates[$bookingData['room_type']] ?? 0) * $bookingData['room_count'] * $bookingData['days'];
        $bedTotal = ($bedRates[$bookingData['bed_type']] ?? 0) * $bookingData['room_count'] * $bookingData['days'];
        $mealTotal = ($mealRates[$bookingData['meal_plan']] ?? 0) * $bookingData['room_count'] * $bookingData['days'];
        $finalTotal = $roomTotal + $bedTotal + $mealTotal;

        return [
            
            'room_total' => $roomTotal,
            'bed_total' => $bedTotal,
            'meal_total' => $mealTotal,
            'final_total' => $finalTotal,
        ];
    }
    public function getPaymentByBooking($bookingId)
    {
        return $this->where('booking_id', $bookingId)->first();
    }
}