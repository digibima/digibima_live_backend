<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $table = "addons";
    protected $connection = "mysql_motor";

    public function aliases()
    {
        return $this->hasMany(AddonAlias::class, 'addon_id');
    }
}