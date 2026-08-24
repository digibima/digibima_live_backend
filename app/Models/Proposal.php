<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;
    protected $connection = "mysql_health";
    protected $fillable = [
        'id',
        'name',
        'email',
        'mobile',
        'pincode',
        'gender',
    ];
}
