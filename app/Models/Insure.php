<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insure extends Model
{
    use HasFactory;
   protected $table = 'insures';
   protected $connection = "mysql_health";
}
