<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Laravel\Scout\Searchable;

class Shriram extends Model
{
    use HasFactory;

    protected $table = 'shriram_vehicle_master';
    protected $connection = 'mysql_motor';

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    // public function toSearchableArray()
    // {
    //     return [
    //         'id' => $this->id,
    //         'VID' => $this->VID,
    //         'MODEL_DESCRIPTION' => $this->MODEL_DESCRIPTION,
    //         'PRODUCT_CODE' => $this->PRODUCT_CODE,
    //         'MANUFACTURER' => $this->MANUFACTURER,
    //         'VEHICLE_TYPE' => $this->VEHICLE_TYPE,
    //     ];
    // }
}
