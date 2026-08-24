<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotorPayment extends Model
{
    use HasFactory;
    protected $table="motor_payment";
    protected $connection = "mysql_motor";
}
