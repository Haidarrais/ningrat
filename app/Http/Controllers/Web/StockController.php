<?php

namespace App\Http\Controllers\Web;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Discount;

class StockController extends Controller
{
    public function index(Request $request) {
        $data = $request->all();
        $user_id = Auth::user()->id;
        $stock_reseller = [];
        if(User::find($user_id)->getRoleNames()->first() == 'reseller') {
            $stock_reseller = Stock::with(['product', 'user'])->where('user_id', $user_id)->get();
        }
        $user = User::find($user_id);
        if (User::find($user_id)->getRoleNames()->first() == 'superadmin') {
            $query = Stock::with(['product.category', 'user', 'discount'])->where('user_id', 'pusat');
        }else{
            $query = Stock::with(['product.category', 'user', 'discount'])->where('user_id', $user_id);

        }
        $stocks = $query->paginate(10);
        $upper_origin = [];
        $user = User::find($user_id);
        $upper_origin = [
            'city' => $user->member->city_id,
            'subdistrict' => $user->member->subdistrict_id
        ];
        if($request->ajax()) {
            return view('pages.order.stock.pagination', compact('stocks', 'data'))->render();
        }
        return view('pages.order.stock.index', compact('user', 'stocks', 'data', 'stock_reseller', 'upper_origin'));
    }

    public function set_status($id, $status) {
        if($status == 1) {
            $text = "Diaktifkan";
        } else if($status == 0) {
            $text = "Dinonaktifkan";
        }
        $stock = Stock::find($id);
        $stock->update(['status' => $status]);
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Berhasil',
                'body' => 'Produk Telah '. $text
            ]
        ], 200);
    }

    public function edit($id) {
        $stock = Stock::with(['product', 'discount'])->find($id);
        if($stock) {
            return response()->json([
                'status' => true,
                'data' => $stock
            ], 200);
        }
        return response()->json([
            'status' => false,
            'data' => []
        ], 404);
    }

    public function update(Request $request, $id) {
        $user_id = Auth::user()->id;
        $request->validate([
            'member_price' => 'required|numeric',
            'discount' => 'required|numeric|max:100'
        ]);

        $stock = Stock::with(['product', 'discount'])->find($id);
        if($request->member_price < $stock->product->price) {
            return response()->json([
                'status' => false,
                'message' => [
                    'head' => 'Gagal',
                    'body' => 'Harga Minimal adalah '. $stock->product->price
                ]
            ], 500);
        }
        if ($request->stock) {
            $stock->update([
                'stock' => $request->stock
            ]);
        }
        $stock->update([
            'member_price' => $request->member_price
        ]);
        $stock->discount()->updateOrCreate([
            'stock_id' => $id,
            'user_id' => Auth::user()->id,
            'status' => 1
        ], [
            'discount' => $request->discount
        ]);
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Berhasil',
                'body' => 'Berhasil update data'
            ]
        ], 200);
    }

    public function set_status_discount($id, $status) {
        $discount = Discount::where('stock_id', $id)->first();
        $discount->update([
            'status' => $status
        ]);
        if($status) {
            $text = 'mengaktifkan';
        } else {
            $text = 'menonaktifkan';
        }
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Berhasil',
                'body' => 'Berhasil '.$text.' diskon'
            ]
        ], 200);
    }
}
