<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarePayment extends Model
{
    use HasFactory;
    protected $table = "care_payment";
    protected $connection = "mysql_health";
}
