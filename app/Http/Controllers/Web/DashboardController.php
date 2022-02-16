<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Order;
use App\Models\Royalty;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class DashboardController extends Controller
{
    private $month;

    public function __construct() {
        $this->month = [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli ', 'Augustus', 'September', 'Oktober', 'November', 'Desember' ];
    }


    public function index(Request $request, $id = null) {
        $text_dashboard = null;
        $user = Auth::user();
        $todaysMonth = Carbon::now()->month;
        $role = $user->getRoleNames()->first();
        $user_updated_at = $user->updated_at->year;
        if ($role == 'distributor') {
            $minimal_transaction = $user_updated_at > 2020 ?
                Setting::where('role', 'new-distributor')->first()->value?? 0 :
                Setting::where('role', 'old-distributor')->first()->value?? 0;
        } else {
            $minimal_transaction = Setting::where('role', $role)->first()->value ?? 0;
        }
        if($id) {
            $user = User::find($id);
            $text_dashboard = $user->name;
        } else {
            $user = Auth::user();
        }
        $all_role = Role::all();
        $role = $user->getRoleNames()->first();

        if($role == 'reseller') {
            $hirarki = User::where('id', $user->id)->get()->pluck('id')->toArray();
            $orders = Transaction::where('user_id', $user->id)->latest()->paginate(5);
        } else {
            $hirarki = User::where('upper', $user->id)->get()->pluck('id')->toArray();
            $orders = Order::whereIn('user_id', $hirarki)->latest()->paginate(5);
        }


        $d = $this->getMonthlyTransaction($hirarki, $user, $minimal_transaction);
        $monthly_transaction = [];

            $check = $this->checkOrderRequirements($d, $minimal_transaction);
            $checkMitraRequirement = count($check["checkMitraRequirement"])>1?false:true;
            $monthly_transaction = $check["newData"];

        $month = $this->month;
        $royalty = Royalty::where('id', '=', 1)->get()->toArray();
        if (auth()->user()->isCustomer()) {
            return redirect('/');
        }else{
            return $request->ajax() ? 
            view("pages.paginationds", compact('orders', 'month', 'hirarki', 'royalty', 'all_role', 'text_dashboard', 'user', 'checkMitraRequirement', 'role', 'monthly_transaction', 'minimal_transaction'))->render()
            :
             view('pages.index', compact('orders', 'month', 'hirarki', 'royalty', 'all_role', 'text_dashboard', 'user', 'checkMitraRequirement','role', 'monthly_transaction', 'minimal_transaction'));
        //  return view('pages.index', compact('orders', 'month', 'hirarki', 'royalty', 'all_role', 'text_dashboard', 'user', 'checkMitraRequirement','role', 'monthly_transaction', 'minimal_transaction'));
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
    private function checkOrderRequirements($monthly_transaction, $minimal_transaction){
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
                    }else{
                        array_push($checkers, false);
                        $fakeData = [
                            "sums" => 0,
                        ];
                        // array_push($newData,$fakeData);
                        $newData[$i] = $fakeData;
                    }
                }else{
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
