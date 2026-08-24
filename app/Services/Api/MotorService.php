<?php
namespace App\Services\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache, Http};
// use App\Http\Controllers\Api\front\motor\Vendor\shriram\Bike\ShriramBikeController;
use App\Http\Controllers\Api\front\motor\Car\CarController;

class MotorService
{
    public function VahanApi($rcno)
    {
        if (isset($rcno)) {
            $response = Http::withHeaders([
                'x-api-key' => 'digi###irt@$!',
                'Accept' => 'application/json',
            ])->post('https://api.digibima.in/python/vahan/getvahan', [
                'rc_number' => $rcno,
            ]);
        } else {
            return [];
        }
        $data = $response->json();
        if ($data['status']) {
            return $data['data'];
        }
        return [];
    }
}
