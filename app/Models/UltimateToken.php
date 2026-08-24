<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UltimateToken extends Model
{
    use HasFactory;
    protected $table = "ultimate_tokens";
    protected $connection = "mysql_health";
}