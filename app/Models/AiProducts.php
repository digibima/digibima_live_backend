<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiProducts extends Model
{
    use HasFactory;
    protected $table = 'prompt_aiproducts';
    protected $connection = 'mysql_master';
}
