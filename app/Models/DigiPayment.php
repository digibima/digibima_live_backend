<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigiPayment extends Model
{
    use HasFactory;
    protected $table = "digibima_payment";
    protected $connection = "mysql_master";
    
}
