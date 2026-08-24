<?php 

namespace App\Models\Godigit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Godigit_Prev_Ins extends Model
{
    use HasFactory;
    protected $table = 'godigit_pre_insurer_master';
    protected $connection = 'mysql_motor';
}