<?php

namespace App\Models\Bajaj;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BajajVehicleMaster extends Model  
{
    use HasFactory;
    protected $table = "bajaj_vehicle_master";
    protected $connection = "mysql_motor";
}
