<?php

namespace App\Models\Adityabirla;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdityaBirlaPincode extends Model
{
    use HasFactory;
    protected $table = "AdityaBirla_pincodes";
    protected $connection = "mysql_health";
}
