<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Order;
use App\Models\Royalty;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $month;

    public function __construct() {
        $this->month = [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli ', 'Augustus', 'September', 'Oktober', 'November', 'Desember' ];
    }

    public function index($id = null) {
        $text_dashboard = null;
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
        $month = $this->month;
        $royalty = Royalty::where('id', '=', 1)->get()->toArray();
        if (auth()->user()->isCustomer()) {
            return redirect('/');
        }else{
            return view('pages.index', compact('orders', 'month', 'hirarki', 'royalty', 'all_role', 'text_dashboard', 'user'));
        }
    }
}
