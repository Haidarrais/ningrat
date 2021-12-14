<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\ProductPicture;
use App\Models\Stock;
use Livewire\Component;

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
    public function render()
    {
        if ($this->category) {
            if ($this->sorting=='date') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->groupBy('product_id')
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
                ->groupBy('product_id')
                ->where('category_id', $this->category)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else if ($this->sorting=='price-desc') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->orderBy('price', 'DESC')
                ->groupBy('product_id')
                ->where('category_id', $this->category)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else{
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->groupBy('product_id')
                ->where('category_id', $this->category)
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
        }else{
            if ($this->sorting=='date') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->groupBy('product_id')
                ->where('user_id', $this->id_member)
                ->orderBy('created_at', 'DESC')
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else if ($this->sorting=='price-asc') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->orderBy('price', 'ASC')
                ->groupBy('product_id')
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else if ($this->sorting=='price-desc') {
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->orderBy('price', 'DESC')
                ->groupBy('product_id')
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
            else{
                $stocks = Stock::with('discount')->join('products', 'product_id', '=', 'products.id')
                ->select('stocks.*', 'products.price')
                ->groupBy('product_id')
                ->where('user_id', $this->id_member)
                ->whereBetween('price', [$this->min_price, $this->max_price])
                ->paginate($this->pageSize);
            }
        }
        $pictures = ProductPicture::where('product_id', '=', $this->pictureId)->get();
        $this->pictures = $pictures ?? false;
        $this->categories = Category::all();
        return view('livewire.show-all-products',['stocks' => $stocks])->layout('layouts.main');
    }
}
