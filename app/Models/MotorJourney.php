<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotorJourney extends Model
{
    use HasFactory;
    protected $table="motor_journey";
    protected $connection = "mysql_motor";
}
