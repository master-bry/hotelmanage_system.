<?php
namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Name', 'RoomType', 'Bed', 'cin', 'cout', 'noofdays', 'NoofRoom', 'meal', 'roomtotal', 'bedtotal', 'mealtotal', 'finaltotal'];

    public function calculateTotals($booking)
    {
        $rates = [
            'Superior Room' => 100000,
            'Deluxe Room' => 80000,
            'Guest House' => 50000,
            'Single Room' => 30000,
            'Single' => 5000,
            'Double' => 10000,
            'None' => 0,
            'Room only' => 0,
            'Breakfast' => 5000,
            'Half Board' => 10000,
            'Full Board' => 15000,
        ];

        $roomtotal = $rates[$booking['RoomType']] * $booking['NoofRoom'] * $booking['nodays'];
        $bedtotal = $rates[$booking['Bed']] * $booking['NoofRoom'] * $booking['nodays'];
        $mealtotal = $rates[$booking['Meal']] * $booking['NoofRoom'] * $booking['nodays'];
        $finaltotal = $roomtotal + $bedtotal + $mealtotal;

        return [
            'roomtotal' => $roomtotal,
            'bedtotal' => $bedtotal,
            'mealtotal' => $mealtotal,
            'finaltotal' => $finaltotal,
        ];
    }
}