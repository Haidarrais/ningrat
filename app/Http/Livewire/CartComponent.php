<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class CartComponent extends Component
{
    public function increaseQty($rowId)
    {
        $product = Cart::get($rowId);
        $qty = $product->qty + 1;
        if ($qty<=$product->model->stock) {
            Cart::update($rowId,$qty);
        }
        else{
            session()->flash('message', 'stock tidak cukup');
        }
    }
    public function decreaseQty($rowId)
    {
        $product = Cart::get($rowId);
        $qty = $product->qty - 1;
        Cart::update($rowId,$qty);

    }

    public function destroyItem($rowId)
    {
        $product = Cart::remove($rowId);
    }
    public function render()
    {
        return view('livewire.cart-component')->layout('layouts.main');
    }
}
