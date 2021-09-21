<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index(Request $request) {
        $query = Courier::query();
        $couriers = $query->paginate(10);
        if($request->ajax()) {
            return view('pages.pengaturan.courier.pagination', compact('couriers'))->render();
        }
        return view('pages.pengaturan.courier.index', compact('couriers'));
    }

    public function set_status(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $courier = Courier::find($id);
        $courier->update([
            'status' => $status
        ]);
        if($status) {
            $text = 'Mengaktifkan Kurir';
        } else {
            $text = 'Menonaktifkan Kurir';
        }
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => $text
            ]
        ], 200);
    }
}
