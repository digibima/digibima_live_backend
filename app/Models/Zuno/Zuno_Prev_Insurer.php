<?php 

namespace App\Models\Zuno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zuno_Prev_Insurer extends Model
{
    use HasFactory;
    protected $table = 'zuno_pre_insurer_master';
    protected $connection = 'mysql_motor';
}