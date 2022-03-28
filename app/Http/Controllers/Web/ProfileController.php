<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Order;
use App\Models\RoleUpgrade;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RequestUpgrade;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Arr;

class ProfileController extends Controller
{
    private $month;

    public function __construct()
    {
        $this->month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli ', 'Augustus', 'September', 'Oktober', 'November', 'Desember'];
    }

    public function index() {
        // $user = Auth::user();
        $user_id = Auth::id();
        $user = User::with('member.city')->find($user_id);
        $role_id = $user->roles->pluck('id')->first();
        $role = $user->getRoleNames()->first();
        // $role_upgrade = RoleUpgrade::where('role_id', $role_id)->where('status', 1)->get();
        $last_upgrade = Carbon::make($user->last_upgrade);
        $monthDiffFromLastUpgrade = $last_upgrade->diffInMonths(Carbon::now());
        $user_updated_at = $last_upgrade->year;
        $hierarki = [];
        if ($user->hirarki) {
            $hierarki = $user->hirarki;
        }
        $count_hierarki = count($hierarki);
        $orders = [];
        $stock_total = 0;
        if ($user->orders) {
            $orders = $user->orders->where('status', 4)->get();
        }
        if ($user->stock) {
            if ($role == "superadmin") {
                $stock_total = "999+";
            }else{
                $stocks = $user->stock->where('stock','>', 0);
                foreach ($stocks as $key => $stock) {
                    $stock_total+=$stock->stock;
                }
            }
        }
        // $status_request = RequestUpgrade::where('user_id', $user->id)->where('status', 1)->first();
        if ($role == 'distributor') {
            $minimal_transaction = $user_updated_at > 2020 ?
                Setting::where('role', 'new-distributor')->first()->value ?? 0 :
                Setting::where('role', 'old-distributor')->first()->value ?? 0;
        } else {
            $minimal_transaction = Setting::where('role', $role)->first()->value ?? 0;
        }
        $d = $this->getMonthlyTransaction( $user);
        $monthly_transaction = [];
        $month = $this->month;
        $check = $this->checkOrderRequirements($d, $minimal_transaction);
        $checkMitraRequirement = count($check["checkMitraRequirement"]) >= 6 ? true : false;
        $monthly_transaction = $check["newData"];
        return view('pages.pengaturan.profile.index', compact('user', 'monthly_transaction','month','checkMitraRequirement', 'monthDiffFromLastUpgrade', 'minimal_transaction', 'orders', 'stock_total', 'count_hierarki'));
    }

    public function update(UpdateProfileRequest $request) {
        DB::beginTransaction();
        try {
            $user_id = Auth::id();
            $user = User::find($user_id);
            $data = [];
            if($request->password) {
                $data = $request->all();
            } else {
                $data = $request->except(['password']);
            }
            $user->update($data);
            $user->member()->updateOrCreate([
                'user_id' => $user_id
            ], $data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Sukses',
                    'body' => 'Berhasil Update Profile'
                ]
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Gagal',
                    'body' => 'Gagal Update Profile'
                ]
            ], 500);
            DB::rollBack();
        }
    }

    public function upgrade(Request $request) {
        $user = Auth::user();
        $role_id = $user->roles->pluck('id')->first();
        $role_upgrade = RoleUpgrade::where('role_id', $role_id)->where('status', 1)->get();
        $role = $user->getRoleNames()->first();
        // $role_upgrade = RoleUpgrade::where('role_id', $role_id)->where('status', 1)->get();
        $last_upgrade = Carbon::make($user->last_upgrade);
        $monthDiffFromLastUpgrade = $last_upgrade->diffInMonths(Carbon::now());
        $user_updated_at = $last_upgrade->year;
        if ($role == 'distributor') {
            $minimal_transaction = $user_updated_at > 2020 ?
                Setting::where('role', 'new-distributor')->first()->value ?? 0 :
                Setting::where('role', 'old-distributor')->first()->value ?? 0;
        } else {
            $minimal_transaction = Setting::where('role', $role)->first()->value ?? 0;
        }
        $d = $this->getMonthlyTransaction($user);
        $check = $this->checkOrderRequirements($d, $minimal_transaction);
        // $checkMitraRequirement = count($check["checkMitraRequirement"]) >= 6 ? true : false;
        $status_upgrade = false;
        if ($monthDiffFromLastUpgrade < 6) {
            $status_upgrade = false;
        }else if(count($check["checkMitraRequirement"]) < 6){
            $status_upgrade = false;

        }else{
            $status_upgrade = true;
        }
        // foreach ($role_upgrade as $key => $value) {
        //     if(strpos(Str::lower($value->description), Str::lower("Member Setelah Upgrade")) > 0) {
        //         $last_upgrade = Carbon::make($user->last_upgrade);
        //         if($last_upgrade->diffInMonths(Carbon::now()) >= $value->value) {
        //             $status_upgrade = true;
        //         } else {
        //             $status_upgrade = false;
        //             break;
        //         }
        //     } else if(strpos(Str::lower($value->description), Str::lower("Marketing")) > 0) {
        //         if(User::where('upper', $user->id)->count() >= $value->value) {
        //             $status_upgrade = true;
        //         } else {
        //             $status_upgrade = false;
        //             break;
        //         }
        //     } else if(strpos(Str::lower($value->description), Str::lower("Penjualan")) > 0) {
        //         if($this->get_omset($value->value)) {
        //             $status_upgrade = true;
        //         } else {
        //             $status_upgrade = false;
        //             break;
        //         }
        //     }
        // }

        if($status_upgrade) {
            RequestUpgrade::updateOrCreate([
                'user_id' => $user->id,
                'role_id' => ($role_id - 1),
            ], [
                'user_id' => $user->id,
                'role_id' => ($role_id - 1),
                'status' => 1
            ]);
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Berhasil',
                    'body' => 'Request anda berhasil'
                ]
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => [
                    'head' => 'Gagal',
                    'body' => 'Anda belum bisa upgrade level'
                ]
            ], 500);
        }
    }

    public static function get_omset($value) {
        $user = Auth::user();
        $data = unserialize($value);
        $pendapatan = Order::selectRaw("SUM(subtotal) AS pendapatan, MONTH(created_at) AS bulan")
                ->where('user_id', $user->id)
                ->whereDate('created_at', '>', $user->last_upgrade)
                ->groupBy(DB::raw("MONTH(created_at)"))->get();
        $result = false;
        if(count($pendapatan) >= $data[1]) {
            foreach ($pendapatan as $key => $value) {
                if($value->pendapatan > ($data[0] * 1000000)) $result = true;
                else {
                    $result = false;
                    break;
                }
            }
        }
        return $result;
    }

    private function getMonthlyTransaction( $user)
    {
        $todaysMonth = Carbon::now()->month;
        $processedOrders = [];
        $orders =  Order::select(
            DB::raw('sum(subtotal) as sums'),
            DB::raw("DATE_FORMAT(created_at,'%m') as month")
        )
            ->where('user_id', $user->id)
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
            return $value == true;
        });
        // dd($filteredArray);
        return [
            "newData" => $newData,
            "checkMitraRequirement" =>  $filteredArray
        ];}
}
