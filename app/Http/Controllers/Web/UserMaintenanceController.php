<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request)
    {
        $month = $this->month;
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'superadmin')->whereDate('last_upgrade', '<=', Carbon::now()->startOfMonth());
        })->with('roles')->get();
        $userWithRoleAndOrders = [];
        
        foreach ($users as $index => $user) {
            # code...
            $role = $user->getRoleNames()->first();
            $userWithRoleAndOrders[$index]["name"] = $user->name;
            $userWithRoleAndOrders[$index]["id"] = $user->id;
            $userWithRoleAndOrders[$index]["role"] = $role;
            $userWithRoleAndOrders[$index]["email"] = $user->email;
                        
            $user_updated_at = $user->updated_at->year;
            $minimal_transaction =0;
            if ($role == 'distributor') {
                $minimal_transaction = $user_updated_at > 2019 ?
                    Setting::where('role', 'new-distributor')->first()->value :
                    Setting::where('role', 'old-distributor')->first()->value;
            } else {
                $minimal_transaction = Setting::where('role', $role)->first()->value ?? 0;
            }
            $hirarki = $role == 'reseller'? 
            User::where('id', $user->id)->get()->pluck('id')->toArray()
            : 
            User::where('upper', $user->id)->get()->pluck('id')->toArray();
            $d = $this->getMonthTotalTransaction($hirarki, $user);
            if ($d> $minimal_transaction) {
               $userWithRoleAndOrders[$index]["status"] = true;
            }else{
                $userWithRoleAndOrders[$index]["status"] = false;
            }
        }
        if ($request->ajax()) {
            return view("pages.pengaturan.usermaintenance.pagination", compact('userWithRoleAndOrders', 'month'))->render();
        }
        return view("pages.pengaturan.usermaintenance.index", compact('userWithRoleAndOrders', 'month'));
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
        $user_updated_at = $user->updated_at->year;
        $minimal_transaction = 0;
        $role = $user->getRoleNames()->first();
        if ($role == 'distributor') {
            $minimal_transaction = $user_updated_at > 2019 ?
                Setting::where('role', 'new-distributor')->first()->value :
                Setting::where('role', 'old-distributor')->first()->value;
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
        try {
            if ($role=="subagent"|| $role == "reseller" || $role== "customer") {
                return response()->json([
                    'status' => false,
                    'message' => [
                        'head'=>'Error',
                        'body'=>"User ini tidak bisa didowngrade"]
                ], 500);
            }else{
            $user = User::find($id);
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
            $user->update(['last_upgrade'=>Carbon::now()]);
                return response()->json([
                    'status' => true,
                    'message' => [
                        'head' => 'Success',
                        'body' => 'User berhasil didowngrade!'
                    ]
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => $th
            ], 200);
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
