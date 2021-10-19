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
    public $province, $city;
    protected $listeners = [
        'nothing' => 'doThisIfNothing',
        'youAreHere' => 'doThis'
    ];
    public function mount()
    {
        $this->province = Auth::user()->member->city->province_id ?? '';
        $this->city = Auth::user()->member->city_id ?? '';
    }
    public function doThis(){
        $this->alert('success', 'Member yang ada di pilihan anda merupakan yang terdekat', [
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
    public function doThisIfNothing(){
        $this->alert('error', 'Mohon maaf untuk saat ini belum ada member di area anda', [
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
    public function render()
    {
        $members = Member::with('user.roles')->whereHas('user.roles', function ($query){
            return $query->where('name', '!=', 'superadmin')->where('name', '!=', 'customer')->where('name', '!=', 'reseller');
        })->with('city', 'avgRating')->where('city_id', $this->city)->get();
        $members_scan = Member::with('user.roles')->whereHas('user.roles', function ($query){
            return $query->where('name', '!=', 'superadmin')->where('name', '!=', 'customer')->where('name', '!=', 'reseller');
        })->with('city', 'avgRating')->get();
        $dataA=[];
        $dataB=[];
        foreach ($members_scan as $value) {
            array_push($dataA, $value->city->province_id);
            array_push($dataB, $value->city->city_id);
        }
        if ($this->city === Auth::user()->member->city_id) {
            if (!$members) {
                $this->emit('nothing');
            }
        }
        array_push($dataA, Auth::user()->member->city->province_id);
        array_push($dataB, Auth::user()->member->city_id);
        $locations = Province::wherein('id', $dataA)->get();
        $cities = City::where('province_id', $this->province)->wherein('city_id', $dataB)->get();
        return view('livewire.member-component', ['members' => $members, 'locations' => $locations, 'cities' => $cities, 'members_scan' => $members_scan])->layout('layouts.main');
    }
}
