<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class HomeComponent extends Component
{
    public function destroyItem($rowId)
    {
        $product = Cart::remove($rowId);
    }
    public function render()
    {
        $categories = DB::table('categories')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->select('categories.name as cname', 'products.category_id', 'products.name as pname', DB::raw('count(products.id) as count'))
            ->groupBy('products.category_id')
            ->get();
        $cat = Category::all();
        $product = Product::all();
        return view('livewire.home-component', ['categories' => $categories, 'cat' => $cat, 'product' => $product])->layout('layouts.main');
    }
}
