<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthUserDescription extends Model
{
    use HasFactory;
    protected $table = "health_user_descrption";
    protected $connection = "mysql_health";
}
