<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\{MasterVendor, MasterPlan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Session, Auth, DB, Validator};
class VendorController
{
    public function index()
    {
        try {
            $aVendorlist = [];
            $vendors = MasterVendor::pluck('vendorname')->unique()->toArray();
            $plandata = MasterPlan::get(['id', 'name'])->toArray();
            $data = [
                'plans' => $plandata,
                'vendors' => $vendors,
            ];
            //dd($data);
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
        //return view('admin.dashboard.managevendor',compact('data'));
    }



    public function AddManagevendor(Request $request)
    {
        try {
            $arequest = $request->data;
            $sPlan = $arequest['vendorname'];
            $select = $arequest['selectplan'];
            $latestVid = MasterVendor::max('vid') + 1;
            $vendor = new MasterVendor();
            $vendor->vid = $latestVid;
            $vendor->vendorname = $sPlan;
            $vendor->type = $select;
            $vendor->created_at = now();
            $vendor->updated_at = now();
            $vendor->save();
            // $bResponse = $vendor->save();
            //dd($bResponse);

            return response()->json([
                'status' => true,
                'success' => 'vendor Addes Succesfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }

    }
    public function UpdateManagevendor(Request $request)
    {
        //dd($request);
        $arequest = $request->data;
        $vendorname = $arequest['vendorname'];
        $prevendor = $arequest['prevendor'];
        $bResponse = MasterVendor::where('vendorname', $prevendor)->update(['vendorname' => $vendorname]);
        if ($bResponse) {
            //Session::flash('success', 'Vendor Updated Successfully');
            return response()->json([
                'status' => true,
                'success' => 'vendor Updated Succesfully'
            ]);
        } else {
            //Session::flash('error', 'Vendor Update Failed');
            return response()->json([
                'status' => false,
                'error' => 'vendor Update Failed'
            ]);
        }
        //return redirect()->route('admin.vendor');

    }

    public function AddNewSaveVendor(Request $request)
    {
        try {
            $arequest = $request->data;
            $latestVid = MasterVendor::max('vid') + 1;
            $product = new MasterVendor();
            $product->vid = $latestVid;
            $product->productname = $arequest['productname'];
            $product->vendorname = $arequest['vendorname'];
            $product->type = $arequest['planname'];
            $product->created_at = now();
            $product->updated_at = now();
            $product->save();

            return response()->json([
                'status' => true,
                'success' => 'Plan added successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }


    }
    public function editVendor(Request $request)
    {
        try {
            $arequest = $request->data;
            $latestVid = MasterVendor::max('vid') + 1;
            $product = MasterVendor::find($arequest['productId']);
            $product->vid = $latestVid;
            $product->productname = $arequest['productname'];
            $product->vendorname = $arequest['vendorname'];
            $product->type = $arequest['planname'];
            $product->created_at = now();
            $product->updated_at = now();
            $product->save();

            return response()->json([
                'status' => true,
                'message' => 'Products added successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }

    }
    public function deleteVendor(Request $request)
    {
        return false;
        $productId = $request->id;
        $product = MasterVendor::find($productId);
        $product->delete();
        return response()->json([
            'status' => '1',
            'message' => 'Products deleted successfully.'
        ]);

    }
    public function AddNewAddon(Request $request)
    {
        dd($request);

    }

    public function AddNewSaveAddon(Request $request)
    {
        dd($request);

    }


    public function userdata(Request $request)
    {
        return response()->json([
            'status' => true,
        ]);

    }

}
