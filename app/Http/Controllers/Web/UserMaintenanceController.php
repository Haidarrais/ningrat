<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RequestUpgrade;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;

use function PHPSTORM_META\map;

class UserMaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $month;

    public function __construct()
    {
        $this->month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli ', 'Augustus', 'September', 'Oktober', 'November', 'Desember'];
    }
    public function index(Request $request, $is_accepting_upgrade_req = null)
    {
        $month = $this->month;
        $request_upgrades = RequestUpgrade::where("status", 1)->get();
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'superadmin')->where('name', '!=', 'reseller')->where('name', '!=', 'customer')->where('name', '!=', 'subagent')->whereDate('last_upgrade', '<=', Carbon::now()->startOfMonth());
        })->with('roles')->get();
        $userWithRoleAndOrders = [];

        foreach ($users as $index => $user) {
            # code...
            $role = $user->getRoleNames()->first();
            $userWithRoleAndOrders[$index]["name"] = $user->name;
            $userWithRoleAndOrders[$index]["id"] = $user->id;
            $userWithRoleAndOrders[$index]["role"] = $role;
            $userWithRoleAndOrders[$index]["email"] = $user->email;
            // dd(Carbon::createFromFormat('Y-m-d H:i:s', $user->last_upgrade)->year);
            $user_updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $user->last_upgrade)->year;
            $minimal_transaction = 0;
            if ($role == 'distributor') {
                $minimal_transaction = $user_updated_at > 2020 ?
                    Setting::where('role', 'new-distributor')->first()->value??0:
                    Setting::where('role', 'old-distributor')->first()->value??0;
            } else {
                $minimal_transaction = Setting::where('role', $role)->first()->value ?? 0;
            }
            $hirarki = $role == 'reseller' ?
                User::where('id', $user->id)->get()->pluck('id')->toArray()
                :
                User::where('upper', $user->id)->get()->pluck('id')->toArray();
            $d = $this->getMonthTotalTransaction($hirarki, $user);
            if ($d > $minimal_transaction) {
                $userWithRoleAndOrders[$index]["status"] = true;
            } else {
                $userWithRoleAndOrders[$index]["status"] = false;
            }
        }
        if ($request->ajax() && $is_accepting_upgrade_req == null) {
            return view("pages.pengaturan.usermaintenance.pagination", compact('userWithRoleAndOrders', 'request_upgrades', 'month'))->render();
        }else if($request->ajax()){
            return view("pages.pengaturan.usermaintenance.upgrade-reqs-pagination", compact('userWithRoleAndOrders', 'request_upgrades', 'month'))->render();
        }
        return view("pages.pengaturan.usermaintenance.index", compact('userWithRoleAndOrders','request_upgrades', 'month'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        // dd($user);
        // return $user;
        $user_updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $user->last_upgrade)->year;
        $minimal_transaction = 0;
        $role = $user->getRoleNames()->first();
        if ($role == 'distributor') {
            $minimal_transaction = $user_updated_at > 2020 ?
                Setting::where('role', 'new-distributor')->first()->value ??0:
                Setting::where('role', 'old-distributor')->first()->value??0;
        } else {
            $minimal_transaction = Setting::where('role', $role)->first()->value ?? 0;
        }
        $hirarki = $role == 'reseller' ?
            User::where('id', $user->id)->get()->pluck('id')->toArray()
            :
            User::where('upper', $user->id)->get()->pluck('id')->toArray();
        $d = $this->getMonthlyTransaction($hirarki, $user, $minimal_transaction);
        $monthly_transaction = [];
        // if ($d) {
        $check = $this->checkOrderRequirements($d, $minimal_transaction);
        $checkMitraRequirement = count($check["checkMitraRequirement"]) > 1 ? false : true;
        $monthly_transaction = $check["newData"];
        return $monthly_transaction;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $role = $request->role;
        $id = $request->id;
        // dd($isUpgrade);
        $isUpgrade = $request->is_upgrade;
        $status = $request->status_request;
        try {
            // dd($isUpgrade, $status, $role);
            if( $isUpgrade && $status==false){
                RequestUpgrade::where("user_id", $id)->update(["status" => 3]);
                return response()->json([
                    'status' => true,
                    'message' => [
                        'head' => 'Success',
                        'body' => 'Permintaan berhasil ditolak!'
                    ]
                ], 200);
            }
            else if ($isUpgrade == null && ($role == "subagent" || $role == "reseller" || $role == "customer")) {
                if ($request->downAll) {
                    return ['name' => User::find($id)->name, 'status' => false];
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => [
                            'head' => 'Error',
                            'body' => "User ini tidak bisa didowngrade"
                        ]
                    ], 500);
                }
            }
            // downgrade user hanya bisa dilakukan di bulan juni dan desember
             else if (Carbon::now()->month !== 12 || Carbon::now()->month !== 6) {
                if ($request->downAll) {
                    return ['name'=>User::find($id)->name, 'status'=>false];
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => [
                            'head' => 'Error',
                            'body' => "Maintenance user hanya dapat dilakukan pada bulan Juni dan Desember"
                        ]
                    ], 500);
                }
            } 
            else {
                $user = User::find($id);
                if ($isUpgrade && $status) {
                    // if($status) {
                        switch ($role) {
                            case 'agent+':
                                $user->removeRole($role);
                                $user->assignRole('distributor');
                                break;
                            case 'agent':
                                $user->removeRole($role);
                                $user->assignRole('agent+');
                                break;
                            case 'subagent':
                                $user->removeRole($role);
                                $user->assignRole('agent');
                                break;
                            default:
                                return response()->json([
                                    'status' => false,
                                    'message' => [
                                        'head' => 'Error',
                                        'body' => "User ini tidak bisa upgrade"
                                    ]
                                ], 500);
                                break;
                        // };
                        RequestUpgrade::where("user_id", $id)->update(["status" => 2]);
                    }
                }else {
                    switch ($role) {
                        case 'distributor':
                            $user->removeRole($role);
                            $user->assignRole('agent+');
                            break;
                        case 'agent+':
                            $user->removeRole($role);
                            $user->assignRole('agent');
                            break;
                        case 'agent':
                            $user->removeRole($role);
                            $user->assignRole('subagent');
                            break;
                        default:
                            break;
                    }
                }
                $user->update(['last_upgrade' => Carbon::now()]);
                if ($request->downAll) {
                    return ['name' => User::find($id)->name, 'status' => true];
                }else  if ($isUpgrade && $status){
                    return response()->json([
                        'status' => true,
                        'message' => [
                            'head' => 'Success',
                            'body' => 'User berhasil diupgrade!'
                        ]
                    ], 200);
                } else {
                    return response()->json([
                        'status' => true,
                        'message' => [
                            'head' => 'Success',
                            'body' => 'User berhasil didowngrade!'
                        ]
                    ], 200);
                }
            }
        } catch (\Throwable $th) {
            if ($request->downAll) {
                return ['name' => User::find($id)->name, 'status' => false];
            } else {
                return response()->json([
                    'status' => true,
                    'message' => $th
                ], 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function downgradeAll(Request $request)
    {
        $userIds = $request->user_id;
        $userRoles = $request->role;
        $processedData = [];
        $results = [];
        for ($i = 0; $i < count($userRoles) ; $i++) {
            $tempdata = [];
            $tempdata["id"] = (int)$userIds[$i];
            $tempdata["role"] = $userRoles[$i];
            array_push($processedData, $tempdata);
        }
        
        $dataToUpdate = new Request;
        for ($j = 0; $j < count($processedData) ; $j++) {
            $dataToUpdate->merge([
                'role' => $processedData[$j]["role"],
                'id' => $processedData[$j]["id"],
                'downAll' => "yes"
            ]);
            $res = $this->update($dataToUpdate);
            array_push($results, $res);
        }

        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Success',
                'body' => $results
            ]
        ], 200);
    }
    private function getMonthTotalTransaction($hirarki, $user)
    {

        $todaysMonth = Carbon::now()->month;
        $processedOrders = [];
        $orders =  Order::select(
            DB::raw('sum(subtotal) as sums'),
            DB::raw("DATE_FORMAT(created_at,'%m') as month")
        )->whereIn('user_id', $hirarki)
            ->orWhere('user_id', $user->id)
            ->where('status', 4)
            ->whereMonth('created_at', $todaysMonth)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('created_at', 'ASC')
            ->get()->toArray();

        if ($todaysMonth > 6) {
            $filteredArray = Arr::where($orders, function ($value, $key) {
                return intval($value['month']) > 6;
            });

            // return $filteredArray;
        } else {
            $filteredArray = Arr::where($orders, function ($value, $key) {
                return intval($value['month']) <= 6;
            });
            // return $filteredArray;
        }
        foreach ($filteredArray as $index => $value) {
            $processedOrders[0] = $value;
        }
        if (count($processedOrders) > 0) {
            return intval($processedOrders[0]["sums"]) ?? 0;
            # code...
        } else {
            return 0;
        }
    }

    private function getMonthlyTransaction($hirarki, $user)
    {
        $todaysMonth = Carbon::now()->month;
        $processedOrders = [];
        $orders =  Order::select(
            DB::raw('sum(subtotal) as sums'),
            DB::raw("DATE_FORMAT(created_at,'%m') as month")
        )->whereIn('user_id', $hirarki)
            ->orWhere('user_id', $user->id)
            ->where('status', 4)
            // ->whereMonth('created_at', 8)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('created_at', 'ASC')
            ->get()->toArray();

        if ($todaysMonth > 6) {
            $filteredArray = Arr::where($orders, function ($value, $key) {
                return intval($value['month']) > 6;
            });

            // return $filteredArray;
        } else {
            $filteredArray = Arr::where($orders, function ($value, $key) {
                return intval($value['month']) <= 6;
            });
            // return $filteredArray;
        }
        foreach ($filteredArray as $index => $value) {
            $processedOrders[intval($filteredArray[$index]["month"])] = $value;
        }
        return $processedOrders;
    }
    private function checkOrderRequirements($monthly_transaction, $minimal_transaction)
    {
        $todaysMonth = Carbon::now()->month;
        $checkers = [];
        $newData = [];
        // dd($monthly_transaction);
        for ($i = $todaysMonth >= 6 ? 7 : 1; $i <= $todaysMonth; $i++) {
            try {
                if ($monthly_transaction[$i]) {
                    if ($monthly_transaction[$i]["sums"] > $minimal_transaction) {
                        array_push($checkers, true);
                        $newData[$i] = $monthly_transaction[$i];
                        // array_push($newData, $monthly_transaction[$i]);
                    } else {
                        array_push($checkers, false);
                        $fakeData = [
                            "sums" => 0,
                        ];
                        // array_push($newData,$fakeData);
                        $newData[$i] = $fakeData;
                    }
                } else {
                    array_push($checkers, false);
                    $fakeData = [
                        "sums" => 0,
                    ];
                    $newData[$i] = $fakeData;
                }
                //code...
            } catch (\Throwable $th) {
                array_push($checkers, false);
                $fakeData = [
                    "sums" => 0,
                ];
                $newData[$i] = $fakeData;
            }
        }
        $filteredArray = Arr::where($checkers, function ($value, $key) {
            return $value == false;
        });
        // dd($filteredArray);
        return [
            "newData" => $newData,
            "checkMitraRequirement" =>  $filteredArray
        ];
    }
}
