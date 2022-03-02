<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class CartComponent extends Component
{
    public $itemQty=[];
    public $rowId;

    public function mount()
    {
        $this->itemQty = Cart::content();
    }
    public function increaseQty($rowId)
    {
        $product = Cart::get($rowId);
        $qty = $product->qty + 1;
        if ($qty<=$product->model->stock) {
            Cart::update($rowId,$qty);
            $this->itemQty = Cart::content();
        }
        else{
            session()->flash('message', 'stock tidak cukup');
        }
    }
    public function delete()
    {
        Cart::remove($this->rowId);
    }
    public function decreaseQty($rowId)
    {
        $product = Cart::get($rowId);
        $this->rowId = $rowId;
        if ($product->qty > 1) {
            $qty = $product->qty - 1;
            Cart::update($rowId,$qty);
            $this->itemQty = Cart::content();
        }
    }
    public function show($rowId)
    {
        foreach ($this->itemQty as $value) {
            $condition = Cart::get($value["rowId"]);
            if ($condition->model->stock >= intval($value["qty"])) {
                if (intval($value["qty"]) == 0) {
                    $this->rowId = $rowId;
                    $value["qty"] = 1;
                    $this->emit('toggleModal');
                }
                Cart::update($value["rowId"],intval($value["qty"]));
                $this->itemQty = Cart::content();
            }else{
                Cart::update($rowId,$condition->model->stock);
                $this->itemQty = Cart::content();
            }
        }
    }
    public function addNote($rowId, $note){
        $this->alert($rowId, $note);
        Cart::update($rowId, ['note'=>$note]);
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
