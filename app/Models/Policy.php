<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Policy extends Model
{
    use HasFactory;

    protected $table = 'policy';
    protected $connection = 'mysql_master';
    public $timestamps = false;

    public function getFileFieldAttribute($value)
    {
        if ($value) {
            return Storage::disk('public')->url($value);
        }
        return '';
    }
}
