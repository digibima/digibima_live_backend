<?php

namespace App\Models\Bajaj;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtaMaster extends Model  
{
    use HasFactory;
    protected $table = "bajaj_rta_master";
    protected $connection = "mysql_motor";
}
