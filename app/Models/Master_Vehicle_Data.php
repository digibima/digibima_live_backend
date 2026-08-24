<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_Vehicle_Data extends Model
{
    use HasFactory;
    protected $table = 'master_vehicle_data';
    protected $connection = 'mysql_motor';
    protected $guarded = [];
}
