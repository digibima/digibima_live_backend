<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RCMaster extends Model
{
    use HasFactory;
    protected $table = "rc_master";
    protected $connection = "mysql_master";
}
