<?php

namespace App\Models\Adityabirla;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdityaBirlaOccupation extends Model
{
    use HasFactory;
    protected $table = "Adityabirla_occupation";
    protected $connection = "mysql_health";
}
