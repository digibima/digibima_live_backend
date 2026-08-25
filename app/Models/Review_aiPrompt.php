<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review_aiPrompt  extends Model
{
    use HasFactory;
    protected $table = 'Review_aiPrompt';
    protected $connection = 'mysql_health';
}
