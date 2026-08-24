<?php

namespace App\Models\Bajaj;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OccupationMaster extends Model
{
    use HasFactory;
    protected $table = "bajaj_occupation_master";
    protected $connection = "mysql_health";
}