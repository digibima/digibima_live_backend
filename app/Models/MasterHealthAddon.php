<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//use Laravel\Sanctum\HasApiTokens;

class MasterHealthAddon extends Model 
{
    use HasFactory;
    protected $table = 'master_health_addons';
    protected $connection = 'mysql_health';
}
