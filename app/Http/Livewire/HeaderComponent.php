<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Request;

class HeaderComponent extends Component
{
    protected $listeners = [
        'cartAdd' => 'cartAdded',
        'cartFail' => 'cartFailed'
    ];
    public function cartFailed()
    {
        session()->flash('message', 'stock tidak mencukupi');
    }
    public function cartAdded()
    {
        session()->flash('success_message', 'Telah ditambahkan ke cart');
    }
    public function render()
    {
        $role = Role::where(function($q) {
            $q->where('name', '=', 'customer');
        })->get();
        if (Request::route()->getName() == 'home.page') {
            return view('livewire.header-component', ['role' => $role]);
        }elseif (Request::route()->getName() == 'profile.lacak') {
            return view('livewire.header-component', ['role' => $role]);
        }else{
            return view('livewire.headerwithcart-component', ['role' => $role]);
        }

    }
}
