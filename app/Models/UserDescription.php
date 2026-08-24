<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDescription extends Model
{
    use HasFactory;
    protected $table = "user_descrption";
    protected $connection = "mysql_master";
}
