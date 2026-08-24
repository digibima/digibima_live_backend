<?php

namespace App\Models\Bajaj;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BajajPincode extends Model
{
    use HasFactory;
    protected $table = "bajaj_pincodes";
    protected $connection = "mysql_health";
}
