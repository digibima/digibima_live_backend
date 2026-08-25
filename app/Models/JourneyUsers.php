<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourneyUsers extends Model
{
    use HasFactory;

    protected $table = 'journey__users';
    protected $connection = "mysql_health";
    // public function getPedAttribute()
    // {
    //     return $this->decryptAttribute('mobile');
    // }

    protected $fillable = [
       'proposalid',
    'insureid',
    'name',
    'age',
    'dob',
    'height',
    'inch',
    'weight',
    'relation',
    'gender',
    'appointee_name',
    'appointee_relation'
    ];
}
