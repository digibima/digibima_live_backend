<?php

namespace App\Models\Ultimatecare;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UltimateInsurerMaster extends Model
{
    use HasFactory;
    protected $table = "ultimate_pre_insurer_master";
    protected $connection = "mysql_health";
}
