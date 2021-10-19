<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Variant\VariantStoreRequest;
use App\Http\Requests\Variant\VariantUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Variant;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $data = Variant::all();
        foreach ($data as $value) {
            if (!$value->parent_id) {
                $value->parent_id = $value->id;
                $value->save();
                return back();
            }
        }
    }
    public function index(Request $request)
    {
        $data = $request->all();
        $query = Variant::query();
        $query->when('keyword', function($q) use($request) {
            $keyword = $request->keyword;
            $q->where('name', 'LIKE', "%$keyword%");
        });
        $query->with('category');
        // $data1 = $query->first();
        // dd($data1->subVariant->name);

        $variants = $query->paginate(10);
        $categories = Category::all();
        if($request->ajax()) {
            return view('pages.master.variant.pagination', compact('variants', 'categories', 'data'))->render();
        }
        return view('pages.master.variant.index', compact('variants', 'categories', 'data'));
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
    public function store(VariantStoreRequest $request)
    {
        Variant::create($request->except(['id']));
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Tambah Varian'
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
        $variant = Variant::find($id);
        return response()->json([
            'status' => true,
            'data' => $variant
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
    public function update(VariantUpdateRequest $request, $id)
    {
        $variant = Variant::find($id);
        $variant->update($request->all());
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Update Varian'
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
        $variant = Variant::find($id);
        // dd($variant->product);
        // if (count($variant->product)>0) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => [
        //             'head' => 'Gagal',
        //             'body' => 'Varian sudah digunakan pada data produk'
        //         ]
        //     ], 500);
        // }

        $variant->delete();
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Hapus Varian'
            ]
        ], 200);
    }
    public function getVariantCategory($id){
        $variants = Variant::all();
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                // 'body' => 'Berhasil Hapus Varian',
                'data' => $variants
            ]
        ], 200);
    }
}
