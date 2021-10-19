<?php

namespace App\Http\Livewire;

use App\Models\City;
use App\Models\Member;
use App\Models\Province;
use App\Models\Review;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MemberComponent extends Component
{
    public $province;
    public $city;
    public function mount()
    {
        $this->province = Auth::user()->member->city->province_id ?? '';
        $this->city = Auth::user()->member->city_id ?? '';
    }
    public function render()
    {
        $members = Member::with('user.roles')->whereHas('user.roles', function ($query){
            return $query->where('name', '!=', 'superadmin')->Where('name', '!=', 'customer')->where('name', '!=', 'reseller');
        })->with('city', 'avgRating')->where('city_id', $this->city)->get();
        $dataA=[Auth::user()->member->city->province_id];
        $dataB=[Auth::user()->member->city_id];
        foreach ($members as $value) {
            array_push($dataA, $value->city->province_id);
            array_push($dataB, $value->city->city_id);
        }
        $locations = Province::wherein('id', $dataA)->get();
        $cities = City::where('province_id', $this->province)->wherein('city_id', $dataB)->get();
        return view('livewire.member-component', ['members' => $members, 'locations' => $locations, 'cities' => $cities])->layout('layouts.main');
    }
}
