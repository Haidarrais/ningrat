<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CreateDiscountRequest;
use App\Http\Requests\Master\UpdateDiscountRequest;
use App\Models\MasterDiscount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request) {
        $data = $request->all();
        $query = MasterDiscount::query();
        $query->when('keyword', function($q) use($request) {
            $keyword = $request->keyword;
            $q->where('name', 'LIKE', "%$keyword%");
        });
        $discounts = $query->paginate(10);
        if($request->ajax()) {
            return view('pages.master.discount.pagination', compact('discounts', 'data'))->render();
        }
        return view('pages.master.discount.index', compact('discounts', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDiscountRequest $request)
    {
        $request->merge([
            'status' => 1
        ]);
        MasterDiscount::create($request->except(['id']));
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Tambah Diskon'
            ]
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discount = MasterDiscount::find($id);
        return response()->json([
            'status' => true,
            'data' => $discount
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDiscountRequest $request, $id)
    {
        $discount = MasterDiscount::find($id);
        $discount->update($request->all());
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Update Diskon'
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $discount = MasterDiscount::find($id);
        $discount->delete();
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Hapus Diskon'
            ]
        ], 200);
    }

    public function set_status(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $discount = MasterDiscount::find($id);
        $discount->update([
            'status' => $status
        ]);
        if($status) {
            $text = 'Mengaktifkan Diskon';
        } else {
            $text = 'Menonaktifkan Diskon';
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
