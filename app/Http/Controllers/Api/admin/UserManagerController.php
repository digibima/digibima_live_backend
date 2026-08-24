<?php

namespace App\Http\Controllers\Api\admin;
use App\Http\Controllers\{Controller, SystemController};
use Illuminate\Http\Request;
use App\Models\{User, CareToken, DigiPayment, MasterAPI, Claim};
use Illuminate\Support\Facades\{Session, Auth, DB, Storage};

class UserManagerController
{
    public function index(Request $request)
    {
        try {
            $userId = $request->userid;
            $mobile=$request->mobile??"";
            // $user = User::whereNotNull('mobile')
            //     ->orderBy('created_at', 'desc')
            //     ->get(['name', 'email', 'mobile', 'pincode', 'city', 'state', 'created_at']);
$query = User::query();

$search = trim($request->search ?? '');

if (!empty($search)) {

    $query->where(function ($q) use ($search) {

        $q->where('mobile', encodeMobile($search))
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->orWhere('email', 'LIKE', "%{$search}%");
    });
}

$user = $query->whereNotNull('mobile')
    ->orderBy('created_at', 'desc')
    ->select([
        'id',
        'name',
        'email',
        'mobile',
        'pincode',
        'city',
        'state',
        'created_at'
    ])
    ->paginate(20);
            

                $data = ['user' => $user];
$payments = DB::connection('mysql_master')
    ->table('digibima_payment')
    ->leftJoin(
        'master_vendor as MasterVendor',
        'digibima_payment.vid',
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

        'digibima_payment.policy_name',
        'digibima_payment.policy_type',
        'digibima_payment.issue_date',
        'digibima_payment.from_date',
        'digibima_payment.to_date',
        'digibima_payment.policy_pdf_path',

        'MasterVendor.productname as vendor_policy_name',
        'MasterVendor.type as vendor_policy_type',
    ])
    ->orderBy('digibima_payment.id', 'desc')
    ->paginate(20);

$payments->getCollection()->transform(function ($item) {
    $item->proposar_name = ucfirst(strtolower($item->proposar_name));

    $item->policy_pdf_path = $item->upload == "1"
        ? Storage::disk('minio')->url($item->policy_pdf_path)
        : $item->policy_pdf_path;

    return $item;
});
// dd($payments);
          return response()->json([
    'status' => true,
    'data' => [
        'user' => $user,
        'policies' => $payments
    ]
]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
        //return view('admin.dashboard.manageuser', compact('data', 'payments'));
    }
    public function getUserPolicies(Request $request)
{
    try {

        $userId = $request->user_id;

        $policies = DB::connection('mysql_master')
    ->table('digibima_payment')
   ->leftJoin(
    'master_vendor as MasterVendor',
    'digibima_payment.policy_name',
    '=',
    'MasterVendor.vid'
)
    ->where('digibima_payment.userid', $userId)
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
    ->orderBy('digibima_payment.id', 'desc')
    ->paginate(10);

        $policies->getCollection()->transform(function ($item) {

            $item->proposar_name = ucfirst(strtolower($item->proposar_name));

            $item->policy_pdf_path = $item->upload == "1"
                ? Storage::disk('minio')->url($item->policy_pdf_path)
                : $item->policy_pdf_path;

            return $item;
        });

        return response()->json([
            'status' => true,
            'data' => $policies
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => false,
            'msg' => $e->getMessage()
        ]);
    }
}
}
