<?php 

namespace App\Models\Zuno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zuno_Pincode extends Model
{
    use HasFactory;
    protected $table = 'zuno_pincode';
    protected $connection = 'mysql_motor';
}