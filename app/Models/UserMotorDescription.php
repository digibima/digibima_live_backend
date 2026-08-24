<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMotorDescription extends Model
{
    use HasFactory;
    protected $table = "motor_user_descrption";
    protected $connection = "mysql_motor";
}
