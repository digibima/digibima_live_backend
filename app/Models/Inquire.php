<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Inquire extends Model
{
    use HasApiTokens, Notifiable;
    //use HasFactory;
    protected $table = "inquiries";
    protected $connection = "mysql_master";
}
