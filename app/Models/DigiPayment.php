<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DigiPayment extends Model
{
    use HasFactory;

    protected $table = 'digibima_payment';
    protected $connection = 'mysql_master';
    protected $guarded = [];

    public function getPolicyPdfPathAttribute($value)
    {
        if ($value && $this->upload == 1) {
            return Storage::disk('public')->url($value);
        }
        return $value;
    }
}
