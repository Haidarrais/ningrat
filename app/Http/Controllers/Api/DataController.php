<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\Royalty;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class DataController extends Controller
{
    private $month;
    private $royalty;

    public function __construct() {
        $this->royalty = Royalty::where('id', '=', 1)->first();
        $this->month = [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli ', 'Augustus', 'September', 'Oktober', 'November', 'Desember' ];
    }

    public function get_penjualan(Request $request) {
        $hirarki = $request->hirarki;
        $month = $request->month;
        $year = $request->year;
        $user = $request->user;
        $query = Order::query();
        $query->selectRaw("COALESCE( COUNT( CASE WHEN STATUS = 0 THEN id END), 0 ) AS pending, COALESCE( COUNT( CASE WHEN STATUS = 1 OR STATUS = 2 THEN id END ), 0 ) AS shipping, COALESCE( COUNT( CASE WHEN STATUS = 3 OR STATUS = 4 THEN id END ), 0 ) AS completed");
        $query->whereIn('user_id', json_decode($hirarki));
        $query->whereMonth("created_at", $month);
        $query->whereYear("created_at", $year);
        $data = $query->first();
        return response()->json([
            'status' => true,
            'data' => $data
        ], 200);
    }

    public function get_status_penjualan(Request $request) {
        $royalty = $this->royalty;
        $hirarki = $request->hirarki;
        $hirarki = json_decode($hirarki);
        if(!$hirarki) {
            $raw_hirarki = $request->hirarki;
            $hirarki = User::whereHas('roles', function($q) use ($raw_hirarki){
                $q->where("name", $raw_hirarki);
            })->get()->pluck('id')->toArray();
            $hirarki = User::whereIn('id', $hirarki)->get()->pluck('id')->toArray();
        }
        $user = $request->user;
        if ($user) {
            $query = Transaction::query();
            $query->selectRaw("SUM(subtotal*$royalty->royalty/100) as pendapatan, COUNT(subtotal) as penjualan");
        }else{
            $query = Order::query();
            $query->selectRaw("SUM(subtotal) as pendapatan, COUNT(subtotal) as penjualan");
        }
        $query->whereIn('user_id', $hirarki);
        $query->whereYear('created_at', date('Y'));
        $query->where(function($q) {
            $q->where('status', 3)->orWhere('status', 4);
        });
        $data = $query->first();
        return response()->json([
            'status' => true,
            'data' => $data
        ], 200);
    }

    public function get_grafik_penjualan(Request $request) {
        $royalty = $this->royalty;
        $hirarki = $request->hirarki;
        $hirarki = json_decode($hirarki);
        if(!$hirarki) {
            $raw_hirarki = $request->hirarki;
            $hirarki = User::whereHas('roles', function($q) use ($raw_hirarki){
                $q->where("name", $raw_hirarki);
            })->get()->pluck('id')->toArray();
            $hirarki = User::whereIn('id', $hirarki)->get()->pluck('id')->toArray();
        }
        $user = $request->user;
        if ($user) {
            $query = Transaction::query();
            $query->selectRaw("SUM(subtotal*$royalty->royalty/100) as pendapatan, COUNT(subtotal) as penjualan, MONTH(created_at) as bulan");
        }else{
            $query = Order::query();
            $query->selectRaw("SUM(subtotal) as pendapatan, COUNT(subtotal) as penjualan, MONTH(created_at) as bulan");
        }
        $query->whereIn('user_id', $hirarki);
        $query->where(function($q) {
            $q->where('status', 3)->orWhere('status', 4);
        });
        $query->groupBy(DB::raw("MONTH(created_at)"));
        $raw_data = $query->get();
        $data = [];
        $index = 0;
        $year = date('Y');
        foreach ($this->month as $key => $value) {
            if(isset($raw_data[$index]['bulan'])) {
                if(($key+1) == $raw_data[$index]['bulan']) {
                    $temp['pendapatan'] = $raw_data[$index]['pendapatan'];
                    $temp['penjualan'] = $raw_data[$index]['penjualan'];
                    $temp['date'] = $value." ".$year;
                    $index++;
                } else {
                    $temp['pendapatan'] = 0;
                    $temp['penjualan'] = 0;
                    $temp['date'] = $value." ".$year;
                }
            } else {
                $temp['pendapatan'] = 0;
                $temp['penjualan'] = 0;
                $temp['date'] = $value." ".$year;
            }
            array_push($data, $temp);
        }
        return response()->json([
            'status' => true,
            'data' => $data
        ], 200);
    }

    public function get_user_by_id(Request $request) {
        $role = $request->role;
        $user = User::whereHas('roles', function($q) use ($role){
            $q->where("name", $role);
        })->get();

        return response()->json([
            'status' => true,
            'data' => $user
        ], 200);
    }

    public function get_rank(Request $request) {
        $no = 1;
        $level = $request->level ?? null;
        $month = $request->month;
        $year = $request->year;

        $rank = Order::whereHas("user", function($sub) use($level){
            $sub->whereHas('roles', function($q) use ($level){
                if($level) {
                    $q->where("name", $level);
                }
                $q->where("name", "!=", 'reseller');
            });
        })->whereMonth("created_at", $month)->whereYear("created_at", $year)->orderBy('subtotal', 'DESC')->groupBy('user_id')->limit(10)->get();
        return view("ajax.ranking", compact('rank', 'no'))->render();
    }
}
