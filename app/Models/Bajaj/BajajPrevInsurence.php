<?php

namespace App\Models\Bajaj;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BajajPrevInsurence extends Model  
{
    use HasFactory;
    protected $table = "bajaj_pre_insurer_master";
    protected $connection = "mysql_motor";
}
