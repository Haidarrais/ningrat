<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ImageHandlerTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;

class ProductController extends Controller
{
    use ImageHandlerTrait;
    private $pathImage = "upload/product/";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $query = Product::query();
        $query->when('keyword', function($q) use($request) {
            
            $keyword = $request->keyword;
            $q->where('name', 'LIKE', "%".$keyword."%")
                ->orWhere('description', 'LIKE', "%".$keyword."%")
                ->orWhere('price', 'LIKE', "%".$keyword."%")
                ->orWhere('weight', 'LIKE', "%".$keyword."%");
        });
        $query->with(['category']);
        $products = $query->paginate(10);
        if($request->ajax()) {
            return view('pages.master.product.pagination', compact('products', 'data'))->render();
        }
        return view('pages.master.product.index', compact('products', 'data'));
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
    public function store(ProductStoreRequest $request)
    {
        if($request->hasFile('image')) {
            $imageName = $this->uploadImage($request, $this->pathImage);
        }
        $data = $request->except(['id']);
        $data['image'] = $imageName ?? '';
        $data['price'] = floor(preg_replace('/[Rp. ]/', '', $request->price));
        Product::create($data);
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Tambah Produk'
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
        $product = Product::with(['category'])->find($id);
        return response()->json([
            'status' => true,
            'data' => $product
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
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if($request->hasFile('image')) {
            $imageName = $this->uploadImage($request, $this->pathImage);
            $this->unlinkImage($this->pathImage, $product->image);
        }
        $data = $request->except(['id']);
        $data['image'] = $imageName ?? $product->image;
        $data['price'] = floor(preg_replace('/[Rp. ]/', '', $request->price));
        
        $product->update($data);
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Update Produk'
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
        $product = Product::find($id);
        
        if (count($product->buyed)>0) {
            return response()->json([
                'status' => false,
                'message' => [
                    'head' => 'Gagal',
                    'body' => 'Produk yang sudah terbeli tidak bisa dihapus'
                ]
            ],
                500
            );
        }
        $product->delete();
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Hapus Produk'
            ]
        ], 200);
    }
}
