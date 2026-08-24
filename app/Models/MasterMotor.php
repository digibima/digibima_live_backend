<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//use Laravel\Sanctum\HasApiTokens;

class MasterMotor extends Model 
{
    use HasFactory;
    protected $table = 'master_motor';
    protected $connection = 'mysql_motor';
}
