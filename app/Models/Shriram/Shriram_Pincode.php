<?php

namespace App\Models\Shriram;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shriram_Pincode extends Model
{
    use HasFactory;
    protected $table = 'shriram_pincode';
    protected $connection = 'mysql_motor';
}
