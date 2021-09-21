<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Stock;
use Livewire\Component;
use Livewire\WithPagination;
use Cart;
use Symfony\Component\Process\Process;

class ShopComponent extends Component
{
    public $sorting;
    public $pageSize;
    public $id_member;
    public $category;
    public $categories;
    public $min_price;
    public $max_price;

    protected $listeners = [
        'cart:update' => '$refresh',
    ];

    public function mount($id)
    {
        $this->sorting = "default";
        $this->pageSize = 6;
        $this->id_member = $id;
        $this->min_price = 50;
        $this->max_price = 1000000;
    }
    public function category($category)
    {
        $this->category = $category;
    }
    public function store($stock_id, $stock_name, $stock_price)
    {
        $product = Stock::where('product_id', $stock_id)->first();
        $stock = Cart::content()->where('id', $stock_id)->first();
        if (empty($stock->qty)) {
            Cart::add($stock_id, $stock_name, 1, $stock_price, ['stock' => $product])->associate('App\Models\Stock');
            $this->emit('cartAdd');
        }elseif ($stock->qty < $product->stock) {
            Cart::add($stock_id, $stock_name, 1, $stock_price, ['stock' => $product])->associate('App\Models\Stock');
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
                ->select('stocks.*', 'products.id')
                ->where('stock', '>', 0)
                ->where('category_id', $this->category)
                ->where('user_id', $this->id_member)
                ->orderBy('created_at', 'DESC')
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else if ($this->sorting=='price-asc') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.id')
                ->orderBy('price', 'ASC')
                ->where('stock', '>', 0)
                ->where('category_id', $this->category)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else if ($this->sorting=='price-desc') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.id')
                ->orderBy('price', 'DESC')
                ->where('stock', '>', 0)
                ->where('category_id', $this->category)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else{
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.id')
                ->where('stock', '>', 0)
                ->where('category_id', $this->category)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
        }else{
            if ($this->sorting=='date') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.id')
                ->where('stock', '>', 0)
                ->where('user_id', $this->id_member)
                ->orderBy('created_at', 'DESC')
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else if ($this->sorting=='price-asc') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.id')
                ->orderBy('price', 'ASC')
                ->where('stock', '>', 0)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else if ($this->sorting=='price-desc') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.id')
                ->orderBy('price', 'DESC')
                ->where('stock', '>', 0)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else{
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.id')
                ->where('stock', '>', 0)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
        }
        $this->categories = Category::all();
        return view('livewire.shop-component',['stocks' => $stocks])->layout('layouts.main');
    }
}
