<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ImageHandlerTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Models\ProductPicture;
use App\Models\Stock;
use App\Models\Variant;
use Illuminate\Support\Facades\File;

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
        $variants = Variant::all();
        $query = Product::query();
        $query->when('keyword', function($q) use($request) {

            $keyword = $request->keyword;
            $q->where('name', 'LIKE', "%".$keyword."%")
                ->orWhere('description', 'LIKE', "%".$keyword."%")
                ->orWhere('price', 'LIKE', "%".$keyword."%")
                ->orWhere('weight', 'LIKE', "%".$keyword."%");
        });
        $query->with(['category', 'picture', 'onePicture']);
        $products = $query->paginate(10);
        // dd($products);
        if($request->ajax()) {
            return view('pages.master.product.pagination', compact('products', 'data', 'variants'))->render();
        }
        return view('pages.master.product.index', compact('products', 'data', 'variants'));
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
        // $this->uploadImage()
        $data = $request->except(['id', 'image']);
        // $data['image'] = $imageName ?? '';
        $data['price'] = floor((float)preg_replace('/[Rp. ]/', '', $request->price));
        $product = Product::create($data);
        if ($request->image) {
            $images = $request->image;
            // dd(count($images));
            $countImages = count($this->countImages($product->id));
            if ($countImages > 5) {
                return response()->json([
                    'status' => true,
                    'message' => [
                        'head' => 'Gagal',
                        'body' => 'Jumlah maksimal foto produk tidak boleh melebihi 5'
                    ]
                ], 500);
            }
            $this->storeImage($images, $product->id);
            // }
        }
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
        $product = Product::with(['category', 'picture', 'onePicture'])->find($id);
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
        // dd($request->all());
        if($request->image) {
            $images = $request->image;
            // dd(count($images));
            $countImages =count($this->countImages($product->id));
            if($countImages>5) {
               return response()->json([
                   'status' => true,
                    'message' => [
                        'head' => 'Gagal',
                        'body' => 'Jumlah maksimal foto produk tidak boleh melebihi 5'
                    ]
               ],500);
            }
            // if ($request->filenames) {
            $this->storeImage($request->image, $product->id);
            // }
            // $this->unlinkImage($this->pathImage, $product->image);
        }
        $data = $request->except(['id','image']);
        // $data['image'] = $imageName ?? $product->image;
        $data['price'] = floor((float)preg_replace('/[Rp. ]/', '', $request->price));

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
                    'body' => 'Produk yang sudah tidak bisa dihapus'
                ]
            ],
                500
            );
        }

        $product_images = $this->countImages($product->id);
        foreach ($product_images as $key => $value) {
            $this->unlinkImage($this->pathImage, $value->image);
            $value->delete();
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

    public function setStatus($id){
        try {
            $product = Product::find($id);
            if ($product->status==null || $product->status==false) {
                $product->update(['status'=>true]);
                return response()->json([
                    'status' => true,
                    'message' => [
                        'head' => 'Sukses',
                        'body' => 'Status berhasil diupdate!'
                    ]
                ], 200);
            }else{
                $product->update(['status' => false]);
                return response()->json([
                    'status' => true,
                    'message' => [
                        'head' => 'Sukses',
                        'body' => 'Status berhasil diupdate!'
                    ]
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => [
                    'head' => 'Gagal',
                    'body' => 'Ooops!'
                ]
            ], 200);
        }
    }
    private function countImages($product_id){
        return ProductPicture::where("product_id", $product_id)->get();
    }

    private function storeImage(array $files, $product_id)
    {
        try {
            for ($i = 0; $i < count($files); $i++) {
                if (isset($files[$i])) {
                    $hashedName = $files[$i]->hashName();
                    $imageName = time() . $hashedName;
                    // $location = public_path($path);
                    $files[$i]->move(public_path($this->pathImage), $imageName);
                    ProductPicture::create([
                        'product_id' => $product_id,
                        'image' =>  $imageName,
                    ]);
                }
            }
            //code...
        } catch (\Throwable $th) {
            dd($th);
        }

        //
    }
    public function pictureShow($id)
    {
        $data = ProductPicture::where('product_id', '=', $id)->get();
        $stock = Stock::where('product_id', '=', $id)->get();
        return response()->json([
            'status'    => 0,
            'data'      => $data,
            'stock'   => $stock
        ]);
    }
    public function destroyImage($id)
    {
        $product_image = ProductPicture::find($id);
        try {
            $image = $product_image->image;
            File::delete($image);
            $product_image->delete();
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Sukses',
                    'body' => 'Foto berhasil dihapus'
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => [
                    'head' => 'Gagal',
                    'body' => 'Gagal Menghapus foto'
                ]
            ], 500);
        }
    }
}
