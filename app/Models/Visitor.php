<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class Visitor extends Model
{
    use HasFactory;
    protected $table = "visitors";
    protected $connection = "mysql_master";
    public $timestamps = false;

}
