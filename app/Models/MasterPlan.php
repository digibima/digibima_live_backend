<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

//use Laravel\Sanctum\HasApiTokens;

class MasterPlan extends Model
{
    use HasFactory;
   protected $table = 'master_plan';
   protected $connection = 'mysql_master';
}
