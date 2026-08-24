<?php

namespace App\Models\Zuno;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zuno_RTO_Master extends Model
{
    use HasFactory;
    protected $table = 'rto_master_zuno';
    protected $connection = 'mysql_motor';
}
