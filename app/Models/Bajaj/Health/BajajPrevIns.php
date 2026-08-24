<?php

namespace App\Models\Bajaj;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BajajPrevIns extends Model
{
    use HasFactory;
    protected $table = "bajaj_health_pre_ins";
    protected $connection = "mysql_health";
}
