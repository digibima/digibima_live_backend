<?php

namespace App\Models\Bajaj;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtoMaster extends Model  
{
    use HasFactory;
    protected $table = "bajaj_rto";
    protected $connection = "mysql_motor";
}
