<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryDiscount\CategoryDiscountStoreRequest;
use App\Http\Requests\CategoryDiscount\CategoryDiscountUpdateRequest;
use App\Models\CategoryDiscount;
use Illuminate\Http\Request;

//========subsidi ongkir controller==========
class CategoryDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $query = CategoryDiscount::query();
        $query->when('keyword', function ($q) use ($request) {
            $keyword = $request->keyword;
            $q->where('category_id', 'LIKE', "%$keyword%");
        });
        $query->with('category');
        // $data1 = $query->first();
        // dd($data1->subCategory->name);

        $category_discounts = $query->paginate(10);
        if ($request->ajax()) {
            return view('pages.master.category_discount.pagination', compact('category_discounts', 'data'))->render();
        }
        return view('pages.master.category_discount.index', compact('category_discounts', 'data'));
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
    public function store(CategoryDiscountStoreRequest $request)
    {
        CategoryDiscount::create($request->except(['id']));
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Tambah Kategori'
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
        $category_discounts = CategoryDiscount::find($id);
        return response()->json([
            'status' => true,
            'data' => $category_discounts
        ], 200);
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
    public function update(CategoryDiscountUpdateRequest $request, $id)
    {
        $category_discounts = CategoryDiscount::find($id);
        $category_discounts->update($request->all());
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Update Kategori'
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
        $category_discounts = CategoryDiscount::find($id);
        // dd($category_discounts->product);
        // if (count($category_discounts->product) > 0) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => [
        //             'head' => 'Gagal',
        //             'body' => 'Kategori sudah digunakan pada data produk'
        //         ]
        //     ], 500);
        // }

        $category_discounts->delete();
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Hapus Kategori'
            ]
        ], 200);
    }
}
