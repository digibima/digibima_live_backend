<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonAlias extends Model
{
    use HasFactory;

    protected $table = "addon_aliases";
    protected $connection = "mysql_motor";

    public function addon()
    {
        return $this->belongsTo(Addon::class, 'addon_id');
    }
}
