<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentReview extends Model
{
    use HasFactory;
    protected $table = "documentreview";
    protected $connection = "mysql_master";
}
