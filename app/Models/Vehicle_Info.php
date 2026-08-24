<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle_Info extends Model
{
    use HasFactory;
    protected $table = 'shriram_vehicle_master';
    protected $connection = 'mysql_motor';
}
