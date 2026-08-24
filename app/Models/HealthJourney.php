<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthJourney extends Model
{
    use HasFactory;
    protected $table="health_journey";
    protected $connection = "mysql_health";
}
