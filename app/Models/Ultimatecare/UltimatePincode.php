<?php

namespace App\Models\Ultimatecare;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UltimatePincode extends Model
{
    use HasFactory;
    protected $table = "ultimatecare_pincodes";
    protected $connection = "mysql_health";
}
