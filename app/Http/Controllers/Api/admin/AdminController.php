<?php

namespace App\Http\Controllers\Api\admin;
use App\Http\Controllers\Api\{Controller, SystemController};
use Illuminate\Http\Request;
use App\Models\{MasterVendor, MasterPlan, PersonalAccessToken, User, CareToken, DigiPayment, NotificationModel, MasterAPI, Claim, Sites, MasterMotor, MasterHealthAddon};
use Illuminate\Support\Facades\{Session, Auth, DB, Storage};
use App\Http\Controllers\Api\admin\VendorController;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;
class AdminController extends VendorController
{
    public function Login(Request $request)
    {
        try {
            $arequest = $request->data;

            $mobile = encodeMobile($arequest['mobile']);

            $admin = User::where('mobile', $mobile)->where('role', 'admin')->first();

            if (!$admin) {
                return response()->json([
                    'status' => false,
                    'message' => 'Admin not registered or not authorized',
                ]);
            }

            $headerToken = $request->header('Authorization');
            $existingToken = null;
            if ($headerToken) {
                $existingToken = PersonalAccessToken::findToken($headerToken);
            }
            // Generate a new token if needed
            if (!$existingToken || $existingToken->tokenable_id !== $admin->id) {
                $newToken = $admin->createToken('token')->plainTextToken;
            } else {
                $newToken = $headerToken;
            }
            return response()->json([
                'status' => true,
                'message' => 'Logged in successfully',
                'token' => $newToken,

            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function Dashboard(Request $request)
    {
        try {
            $userId = $request->userid;

            $admin = User::where('id', $userId)->where('role', 'admin')->first();

            $users = User::orderBy('created_at', 'desc')
                ->take(15)
                ->get()->map(function ($item) {
                    return [
                        'userId' => $item->id,
                        'name' => $item->name,
                        'email' => $item->email,
                        'date' => $item->created_at,
                    ];
                });


            $totalClaims = Claim::count();

            $policyCount = DigiPayment::whereNotNull('policy')
                ->count();

            $payment = DigiPayment::leftJoin('master_vendor as mv', 'digibima_payment.vid', '=', 'mv.vid')
                ->whereNotNull('digibima_payment.policy')
                ->where('digibima_payment.policy', '!=', '0')
                ->orderBy('digibima_payment.created_at', 'desc')
                ->limit(15)
                ->select(
                    'digibima_payment.id as id',
                    'digibima_payment.created_at',
                    'digibima_payment.is_paid',
                    'mv.type as type',
                    'mv.productname as vendor',
                    'digibima_payment.vid',
                    'digibima_payment.policy as policyno',
                    'digibima_payment.proposal as proposalno',
                    'digibima_payment.premium as price',
                    'digibima_payment.proposar_name as name',
                    'digibima_payment.vehicle_type as vehicle_type'
                )
                ->get()
                ->map(function ($item) {
                    return [
                        'notificationId' => $item->id,
                        'paid' => $item->is_paid,
                        'type' => $item->type,
                        'vendor' => $item->vendor,
                        'policyno' => $item->policyno,
                        'proposalno' => $item->proposalno,
                        'price' => $item->price,
                        'proposar_name' => $item->name,
                        'vehicle_type' => $item->vehicle_type,
                    ];
                });
            $visitors = Redis::get('visitor:total');
            $data = [
                'policyCount' => $policyCount,
                'admin' => $admin->name,
                'userdata' => $users,
                'visitors' => $visitors ?? 0,
                'payment' => $payment,
                'totalClaims' => $totalClaims,
                'activepolicies' => $totalClaims,
                'expiredpolicies' => $policyCount
            ];
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
    }

    // public function Logout()
    // {
    //     if (Auth::check()) {
    //         Auth::logout();
    //         Session::flush();
    //     }
    //     Session::flash('success', 'Logout Succesfully');
    //     return redirect()->route('admin.root');
    // }


    public function Manageplan()
    {
        try {
            // $plan = MasterPlan::get(['id', 'name']);
            $plan = MasterPlan::whereNull('is_delete')
                ->get(['id', 'name']);
            $data = ['plan' => $plan->toArray()];
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
    }

    public function ManageSaveplan(Request $request)
    {
        try {
            $arequest = $request->data;
            $plan = new MasterPlan();
            $plan->name = $arequest['planname'];
            $plan->created_at = now();
            $plan->updated_at = now();
            $plan->save();
            // $bResponse = $plan->save();

            return response()->json([
                'status' => true,
                'success' => 'Plan Addes Succesfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }
    public function ManageUpdateplan(Request $request)
    {
        try {
            $arequest = $request->data;
            //$sPlan = $request->editplanName;
            $planId = $arequest['id'];
            $oPlan = MasterPlan::find($planId);
            $oPlan->name = $arequest['editplanName'];
            $oPlan->updated_at = now();
            $oPlan->save();
            // $bResponse = $oPlan->save();
            return response()->json([
                'status' => true,
                'msg' => 'Plan update Succesfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }

    }
    public function deletePlan(Request $request)
    {
        // return response()->json([
        //     'status' => false,
        //     'message' => 'Products not deleted.'
        // ]);
        try {
            $arequest = $request->data;
            $planId = $arequest['id'];
            // $planId = $request->userid;
            $plan = MasterPlan::find($planId);
            $plan->is_delete = now();
            $plan->updated_at = now();
            $plan->save();
            //$plan->delete();
            return response()->json([
                'status' => true,
                'message' => 'Plan deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }

        //return response()->json(['message' => 'Plan deleted successfully.']);
    }

    public function getAddons(Request $request)
    {
        try {
            $addons = null;
            $type = $request->type;
            if ($type == "health") {
                $addons = MasterHealthAddon::whereNotNull('addon')
                    ->select('id', 'addon')
                    ->get();
            } elseif ($type == "motor") {
                $addons = MasterMotor::whereNotNull('addon')
                    ->select('id', 'addon')
                    ->get();
            } else {
                return response()->json(['status' => false, 'addons' => []]);
            }
            return response()->json(['status' => true, 'addons' => $addons]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }
    public function Manageproduct()
    {
        try {

            $data = MasterVendor::whereNull('is_delete')->select('id', 'productname', 'vendorname', 'type')->paginate(50);
            $vendors = MasterVendor::whereNull('is_delete')->select('id', 'vendorname')->paginate(50);
            $plans = MasterPlan::whereNull('is_delete')->select('id', 'name')->paginate(50);
            // $masterMotor = MasterMotor::whereNotNull('addon')
            //     ->where('is_car', 1)
            //     ->get();
            return response()->json([
                'status' => true,
                'data' => $data,
                'vendors' => $vendors,
                'plans' => $plans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }


    public function EditFooter(Request $request)
    {
        //$userId = $request->userid;
        try {
            if ($request->method() == 'GET') {
                $data = Sites::where('id', '1')->first();
                return response()->json([
                    'status' => true,
                    'data' => $data
                ]);
            }
            if ($request->method() == 'POST') {
                $arequest = $request->data;
                $oSite = Sites::find(getconstant('FOOTER.FOOTER'));
                if ($oSite) {
                    $oSite->personal_info = $arequest['personal_info'];
                    $oSite->updated_at = now();
                    $oSite->save();
                } else {
                    $oSite = new Sites();
                    $oSite->personal_info = $arequest['personal_info'];
                    $oSite->created_at = now();
                    $oSite->updated_at = now();
                    $oSite->save();
                }
                return response()->json([
                    'status' => true
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function getPolicy(Request $request)
    {
        try {
            $policy = \DB::connection('mysql_master')
                ->table('digibima_payment')
                ->leftJoin(
                    'master_vendor as MasterVendor',
                    'digibima_payment.policy_name',
                    '=',
                    'MasterVendor.vid'
                )
                ->select([
                    'digibima_payment.id',
                    'digibima_payment.upload',
                    'digibima_payment.policy',
                    'digibima_payment.proposal',
                    'digibima_payment.status_details',
                    'digibima_payment.proposar_name',

                    \DB::raw('COALESCE(MasterVendor.productname, digibima_payment.policy_name) as policy_name'),

                    'digibima_payment.policy_type',
                    'digibima_payment.issue_date',
                    'digibima_payment.from_date',
                    'digibima_payment.to_date',
                    'digibima_payment.policy_pdf_path',

                    'MasterVendor.productname as vendor_policy_name',
                    'MasterVendor.type as vendor_policy_type',
                ])
                ->paginate(10);

            $policy->getCollection()->transform(function ($item) {

                $item->proposar_name = ucfirst(strtolower($item->proposar_name));

                $item->policy_pdf_path = ($item->upload && $item->upload == "1")
                    ? Storage::disk('minio')->url($item->policy_pdf_path)
                    : $item->policy_pdf_path;

                $today = date('Y-m-d');

                if ($item->to_date) {
                    $item->status = ($today <= $item->to_date)
                        ? "Active"
                        : "Inactive";
                } else {
                    $item->status = "Inactive";
                }

                return $item;
            });

            return response()->json([
                'status' => true,
                'data' => [
                    'policies' => $policy
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function RecycleBin()
    {
        try {
            $data = MasterVendor::whereNotNull('is_delete')->select('id', 'productname', 'vendorname', 'type')->paginate(5);
            $vendors = MasterVendor::whereNotNull('is_delete')->select('id', 'vendorname')->paginate(5);
            $plans = MasterPlan::whereNotNull('is_delete')->select('id', 'name')->paginate(5);

            return response()->json([
                'status' => true,
                'data' => $data,
                'vendors' => $vendors,
                'plans' => $plans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }
    public function RecentLogin()
    {
        try {
            $sevenDaysAgo = Carbon::now()->subDays(7)->toDateTimeString();
            $data = User::where('last_login', '>=', $sevenDaysAgo)
                ->select('name', 'mobile', 'city', 'created_at', 'last_login')
                ->paginate(25);
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
    }
}
