<?php

namespace App\Models\Shriram;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shriram_RTO_Master extends Model
{
    use HasFactory;
    protected $table = 'shriram_rto_master';
    protected $connection = 'mysql_motor';
}
