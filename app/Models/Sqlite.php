<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sqlite extends Model
{
    use HasFactory;
    protected $connection = 'sqlite';
}
