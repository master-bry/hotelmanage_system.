<?php
namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Name', 'RoomType', 'Bed', 'cin', 'cout', 'noofdays', 'NoofRoom', 'meal', 'roomtotal', 'bedtotal', 'mealtotal', 'finaltotal'];
}