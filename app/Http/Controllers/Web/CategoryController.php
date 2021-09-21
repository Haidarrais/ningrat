<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $query = Category::query();
        $query->when('keyword', function($q) use($request) {
            $keyword = $request->keyword;
            $q->where('name', 'LIKE', "%$keyword%");
        });
        $query->with(['product', 'subCategory']);
        // $data1 = $query->first();
        // dd($data1->subCategory->name);
        $categories = $query->paginate(10);
        if($request->ajax()) {
            return view('pages.master.category.pagination', compact('categories', 'data'))->render();
        }
        return view('pages.master.category.index', compact('categories', 'data'));
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
    public function store(CategoryStoreRequest $request)
    {
        Category::create($request->except(['id']));
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
        $category = Category::with(['subCategory'])->find($id);
        return response()->json([
            'status' => true,
            'data' => $category
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
    public function update(CategoryUpdateRequest $request, $id)
    {
        $category = Category::find($id);
        $category->update($request->all());
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
        $category = Category::find($id);
        if(Category::where('parent_id', $category->id)->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => [
                    'head' => 'Gagal',
                    'body' => 'Kategori Masih memiliki anak haram'
                ]
            ], 500);
        }
        $category->delete();
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Hapus Kategori'
            ]
        ], 200);
    }
}
