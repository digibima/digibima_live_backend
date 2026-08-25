<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

class TeleSales extends Model
{
    protected $connection = "mysql_master";
    protected $table = "telesales";
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'date' => 'datetime',
    ];
}
