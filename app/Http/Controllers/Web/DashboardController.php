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

    public function index($id = null) {
        $text_dashboard = null;
        $user = Auth::user();
        $todaysMonth = Carbon::now()->month;
        $role = $user->getRoleNames()->first();
        $user_updated_at = $user->updated_at->year;
        if ($role == 'distributor') {
            $minimal_transaction = $user_updated_at > 2019 ?
                Setting::where('role', 'new-distributor')->first()->value :
                Setting::where('role', 'old-distributor')->first()->value;
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
            $orders = Transaction::where('user_id', $user->id)->latest()->limit(10)->get();
        } else {
            $hirarki = User::where('upper', $user->id)->get()->pluck('id')->toArray();
            $orders = Order::whereIn('user_id', $hirarki)->latest()->limit(10)->get();
        }


        $monthly_transaction = $this->getMonthlyTransaction($hirarki, $user, $minimal_transaction);
        $check = $this->checkOrderRequirements($monthly_transaction, $minimal_transaction);
        $checkMitraRequirement = count($check)>1?false:true;
       
        // dd($monthly_transaction);
        $month = $this->month;
        $royalty = Royalty::where('id', '=', 1)->get()->toArray();
        if (auth()->user()->isCustomer()) {
            return redirect('/');
        }else{
            return view('pages.index', compact('orders', 'month', 'hirarki', 'royalty', 'all_role', 'text_dashboard', 'user', 'checkMitraRequirement','role', 'monthly_transaction', 'minimal_transaction'));
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
        for ($i = $todaysMonth >= 6 ? 7 : 1; $i <= $todaysMonth; $i++) {
            if ($monthly_transaction[$i]) {
                if ($monthly_transaction[$i]["sums"] > $minimal_transaction) {
                    array_push($checkers, true);
                }else{
                    array_push($checkers, false);
                }
            }else{
                array_push($checkers, false);
            }
        }
        $filteredArray = Arr::where($checkers, function ($value, $key) {
            return $value == false;
        });
        return $filteredArray;
    }
}
