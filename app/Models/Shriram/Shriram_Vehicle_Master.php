<?php

namespace App\Models\Shriram;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shriram_Vehicle_Master extends Model
{
    use HasFactory;
    protected $table = 'shriram_vehicle_master';
    protected $connection = 'mysql_motor';
}
