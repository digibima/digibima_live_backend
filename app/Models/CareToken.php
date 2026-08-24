<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareToken extends Model
{
    use HasFactory;
    protected $table = "care_tokens";
    protected $connection = "mysql_health";
}
