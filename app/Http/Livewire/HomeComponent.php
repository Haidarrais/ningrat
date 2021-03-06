<?php

namespace App\Http\Livewire;

use App\Models\Article;
use App\Models\Category;
use App\Models\Content;
use App\Models\Product;
use App\Models\Testimonial;
use Carbon\Carbon;
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
    public function goBlog($id)
    {
        $articles = Article::where('banner_id', $id)->first();
        if ($articles) {
            return redirect("/blog/$id");
        }else{
            $this->alert('error', 'Tidak ada artikel', [
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
    }
    public function render()
    {
        $carousel   = Content::where('content_type', '=', '1')->get();
        $banner     = Content::where('content_type', '=', '2')->limit(2)->get();
        $testi = Testimonial::all();
        // $categories = DB::table('categories')
        //     ->join('products', 'categories.id', '=', 'products.category_id')
        //     ->select('categories.name as cname', 'products.category_id', 'products.name as pname', DB::raw('count(products.id) as count'))
        //     ->groupBy('products.category_id')
        //     ->get();
        $cat = Category::with('product')->get();
        $product = Product::whereRaw('products.status = 1')->get();
        return view('livewire.home-component', ['cat' => $cat, 'product' => $product, 'carousel' => $carousel, 'banner' => $banner, 'testi' => $testi])->layout('layouts.main');
    }
}
