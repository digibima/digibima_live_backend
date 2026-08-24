<?php 

namespace App\Models\Godigit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Godigit_Vehicle_Master extends Model
{
    use HasFactory;
    protected $table = 'godigit_vehicle_master';
    protected $connection = 'mysql_motor';
}