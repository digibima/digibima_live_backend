<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarePincode extends Model
{
    use HasFactory;
    protected $table = "care_pincodes";
    protected $connection = "mysql_health";
}
