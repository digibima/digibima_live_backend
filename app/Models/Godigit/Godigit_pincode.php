<?php 

namespace App\Models\Godigit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Godigit_pincode extends Model
{
    use HasFactory;
    protected $table = 'godigit_pincode';
    protected $connection = 'mysql_motor';
}