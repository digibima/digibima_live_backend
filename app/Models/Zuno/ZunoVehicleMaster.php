<?php

namespace App\Models\Zuno;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZunoVehicleMaster extends Model  
{
    use HasFactory;
    protected $table = "bajaj_vehicle_master";
    protected $connection = "mysql_motor";
}
