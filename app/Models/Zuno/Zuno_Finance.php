<?php 

namespace App\Models\Zuno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zuno_Finance extends Model
{
    use HasFactory;
    protected $table = 'zuno_finance_master';
    protected $connection = 'mysql_motor';
}