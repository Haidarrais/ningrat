<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultGetResponse;
use App\Models\Category;
use App\Models\City;
use App\Models\Province;
use App\Models\Subdistrict;
use App\Models\Variant;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class MasterController extends Controller
{
    public function get_role() {
        $role = Role::all();
        return new DefaultGetResponse($role);
    }

    public function get_category() {
        $categories = Category::all();
        return new DefaultGetResponse($categories);
    }

    public function get_variant() {
        $variants = Variant::all();
        return new DefaultGetResponse($variants);
    }

    public function get_provice(Request $request) {
        $province = Province::when($request->id, function($q) use ($request) {
            $q->find($request->id);
        })->get();
        return new DefaultGetResponse($province);
    }

    public function get_city(Request $request) {
        $city = City::when($request->province_id, function($q) use ($request) {
            $q->where('province_id', $request->province_id);
        })->get();
        return new DefaultGetResponse($city);
    }

    public function get_subdistict(Request $request) {
        $subdistict = Subdistrict::when($request->city_id, function($q) use ($request) {
            $q->where('city_id', $request->city_id);
        })->get();
        return new DefaultGetResponse($subdistict);
    }
}
