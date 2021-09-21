<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Cart;
use Symfony\Component\Process\Process;

class CategoryComponent extends Component
{
    public $sorting;
    public $pageSize;
    public $categoryId;
    public function mount($id)
    {
        $this->sorting = "default";
        $this->pageSize = 6;
        $this->categoryId = $id;
    }

    public function store($product_id, $product_name, $product_price)
    {
        Cart::add($product_id, $product_name, 1, $product_price)->associate('App\Models\Product');
        session()->flash('success_message', 'item added in cart');
        return redirect()->route('product.cart');
    }
    use WithPagination;
    public function render()
    {
        $category = Category::where('id', $this->categoryId)->first();
        $category_id = $category->id;
        if ($this->sorting=='date') {
            $product = Product::where('category_id', $category_id)->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        }
        else if ($this->sorting=='price') {
            $product = Product::where('category_id', $category_id)->orderBy('price', 'ASC')->paginate($this->pageSize);
        }
        else if ($this->sorting=='price-desc') {
            $product = Product::where('category_id', $category_id)->orderBy('price', 'DESC')->paginate($this->pageSize);
        }
        else{
            $product = Product::where('category_id', $category_id)->paginate($this->pageSize);
        }
        $categories = Category::all();
        return view('livewire.category-component',['product' => $product, 'categories' => $categories])->layout('layouts.main');
    }
}
