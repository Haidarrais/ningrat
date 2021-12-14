<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\ProductPicture;
use App\Models\Stock;
use Livewire\Component;
use Livewire\WithPagination;
use Cart;
use Symfony\Component\Process\Process;

class ShowAllProducts extends Component
{
    public $sorting;
    public $pictures, $pictureId;
    public $pageSize;
    public $id_member;
    public $category;
    public $categories;
    public $min_price;
    public $max_price;

    protected $listeners = [
        'cart:update' => '$refresh',
    ];

    public function mount()
    {
        $this->sorting = "default";
        $this->pageSize = 6;
        // $this->id_member = $id;
        $this->min_price = 50;
        $this->max_price = 1000000;
    }
    public function pictures($id)
    {
        $this->pictureId = $id;
    }
    public function category($category)
    {
        $this->category = $category;
    }
    public function store($stock_id, $stock_name, $stock_price)
    {
        $stock = Stock::where('id', $stock_id)->first();
        $product = Stock::where('product_id', $stock->product->id)->first();
        $stock = Cart::content()->where('id', $stock_id)->first();
        if (empty($stock->qty)) {
            $catitem = Cart::add($stock_id, $stock_name, 1, $stock_price, ['stock' => $product]);
            $catitem->associate('App\Models\Stock');
            $this->emit('cartAdd');
        }elseif ($stock->qty < $product->stock) {
            $catitem = Cart::add($stock_id, $stock_name, 1, $stock_price, ['stock' => $product]);
            $catitem->associate('App\Models\Stock');
            $this->emit('cartAdd');
        }else{
            $this->emit('cartFail');
        }
        //return redirect()->route('product.cart');
    }
    use WithPagination;
    public function render()
    {
        if ($this->category) {
            if ($this->sorting=='date') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->groupBy('products.id')
                ->where('category_id', $this->category)
                ->where('user_id', $this->id_member)
                ->orderBy('created_at', 'DESC')
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else if ($this->sorting=='price-asc') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->orderBy('price', 'ASC')
                ->groupBy('products.id')
                ->where('category_id', $this->category)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else if ($this->sorting=='price-desc') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->orderBy('price', 'DESC')
                ->groupBy('products.id')
                ->where('category_id', $this->category)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else{
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->groupBy('products.id')
                ->where('category_id', $this->category)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
        }else{
            if ($this->sorting=='date') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->groupBy('products.id')
                ->where('user_id', $this->id_member)
                ->orderBy('created_at', 'DESC')
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else if ($this->sorting=='price-asc') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->orderBy('price', 'ASC')
                ->groupBy('products.id')
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else if ($this->sorting=='price-desc') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->orderBy('price', 'DESC')
                ->groupBy('products.id')
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else{
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->groupBy('products.id')
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
        }
        if (count($stocks) < 1) {
            $this->alert('info', 'Stok produk pada kategori ini belum tersedia', [
                'position' =>  'center',
                'timer' =>  3000,
                'toast' =>  true,
                'text' =>  '',
                'confirmButtonText' =>  'Ok',
                'cancelButtonText' =>  'Cancel',
                'showCancelButton' =>  false,
                'showConfirmButton' =>  false,
            ]);
        }
        $pictures = ProductPicture::where('product_id', '=', $this->pictureId)->get();
        $this->pictures = $pictures ?? false;
        $this->categories = Category::all();
        return view('livewire.shop-component',['stocks' => $stocks])->layout('layouts.main');
    }
}
