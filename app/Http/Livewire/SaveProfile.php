<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class SaveProfile extends Component
{
    public $request;
    protected $rules = [
        'name' => 'required',
        'email' => "required",
        'phone_number' => 'required',
        'password' => 'nullable|confirmed|min:8',
        'province_id' => 'required|integer',
        'city_id' => 'required|integer',
        'subdistrict_id' => 'required|integer',
        'address' => 'required'
    ];
    public function mount(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = Auth::id();
            $user = User::find($user_id);
            $data = [];
            if($request->password) {
                $data = $request->all();
            } else {
                $data = $request->except(['password']);
            }
            $user->update($data);
            $user->member()->updateOrCreate([
                'user_id' => $user_id
            ], $data);

            DB::commit();
            session()->flash('status', 'Profile updated!');
            if (auth()->user() && auth()->user()->isCustomer()) {
                return redirect()->route('profile.customer');
            }else{
                return redirect()->route('profile.reseller');
            }
        } catch(\Exception $e) {
            session()->flash('status', 'Profile not updated!');
            if (auth()->user() && auth()->user()->isCustomer()) {
                return redirect()->route('profile.customer');
            }else{
                return redirect()->route('profile.reseller');
            }
            DB::rollBack();
        }
    }
}
