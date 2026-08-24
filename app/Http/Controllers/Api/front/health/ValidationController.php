<?php
namespace App\Http\Controllers\Api\front\motor\Car;
use App\Http\Controllers\Api\{SystemController};
use Illuminate\Http\Request;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master, Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, User, MasterVendor, VendorMotor, MasterMotor};
use App\Services\Api\{ShriramService};
use Illuminate\Support\Facades\{Auth};

class ValidationController
{
    public static function ValidateStaticData($vid,$value="", $userId)
    {
        $bFlag = True;
        $aVendor = MasterVendor::where('vid', $vid)->first();
        $aVendorAddon = empty($aVendor->caraddons) ? '' : json_decode($aVendor->caraddons, true);
        $aVendorPlan = empty($aVendor->car_plans) ? '' : json_decode($aVendor->car_plans, true);
        $aDataModel = DataModel::where('userid',$userId)->first();
        $aAddon = empty($aDataModel->caraddon) ? '' : json_decode($aDataModel->caraddon, true);
        $nPlan = empty($aDataModel->car_plans) ? '' : $aDataModel->car_plans;
        //dd($nPlan,$aVendorPlan);
        // if(empty($aAddon)||empty($aDataModel->caraddon))
        // {
        //     $bFlag = True;
        // }
        if (!empty($aDataModel->caraddon)) {
            foreach ($aAddon as $addon) {
                if (!in_array($addon, $aVendorAddon)) {
                    $bFlag = False;
                    return $bFlag;
                }
            }
        }

        // if(empty($aPlan)||empty($aDataModel->car_plan))
        // {
        //     $bFlag = True;
        // }
        if (!empty($nPlan)) {
            if (!in_array($nPlan, $aVendorPlan)) {
                $bFlag = False;
                return $bFlag;
            }
        }
        return $bFlag;
    }

    // public static function ValidateCacheData($vid,$value)
    // {
    //     $bFlag = True;
    //     $aVendor = MasterVendor::where('vid', $vid)->first();
    //     $aVendorAddon = empty($aVendor->caraddons) ? '' : json_decode($aVendor->caraddons, true);
    //     $aVendorPlan = empty($aVendor->car_plans) ? '' : json_decode($aVendor->car_plans, true);
    //     $aDataModel = DataModel::where('userid', Auth::id())->first();
    //     $aAddon = empty($aDataModel->caraddon) ? '' : json_decode($aDataModel->caraddon, true);
    //     $nPlan = empty($aDataModel->car_plans) ? '' : $aDataModel->car_plans;
    //     $nIDV = empty($aDataModel->idv) ? '' : $aDataModel->idv;
    //     if (!empty($aDataModel->caraddon)) {
    //         foreach ($aAddon as $addon) {
    //             if (!in_array($addon, $aVendorAddon)) {
    //                 $bFlag = False;
    //                 return $bFlag;
    //             }
    //         }
    //     }

    //     if (!empty($nPlan)) {
    //         if (!in_array($nPlan, $aVendorPlan)) {
    //             $bFlag = False;
    //             return $bFlag;
    //         }
    //     }
    //     // if (!empty($nPlan)) {
    //     //     if (!in_array($nPlan, $aVendorPlan)) {
    //     //         $bFlag = False;
    //     //         return $bFlag;
    //     //     }
    //     // }
    //     return $bFlag;
    // }
}
