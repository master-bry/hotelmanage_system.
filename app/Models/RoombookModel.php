<?php
namespace App\Models;

use CodeIgniter\Model;

class RoombookModel extends Model
{
    protected $table = 'roombook';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Name', 'Email', 'Country', 'Phone', 'RoomType', 'Bed', 'NoofRoom', 'Meal', 'cin', 'cout', 'nodays', 'stat'];
    protected $useTimestamps = false;
    protected $validationRules = [
        'Name' => 'required|min_length[3]',
        'Email' => 'required|valid_email',
        'RoomType' => 'required|in_list[Superior Room,Deluxe Room,Guest House,Single Room]',
        'cin' => 'required|valid_date',
        'cout' => 'required|valid_date',
    ];
}