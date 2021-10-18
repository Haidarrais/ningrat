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

    public function get_sub_categories($id){
        $subs = Category::where('parent_id',$id)->where('id',"!=",$id)->get();

        return new DefaultGetResponse($subs);

    }
    public function get_sub_variants($id)
    {
       
        $subs = Variant::where('parent_id', $id)->where('id', "!=", $id)->get();

        return new DefaultGetResponse($subs);
    }

    public function get_category() {
        $categories = Category::all();
        $categories_with_sub = [];
        foreach ($categories as $key => $value) {
            $temp_data = $value;
            $sub = Category::where("parent_id", "=", $value->id)->get();

            foreach ($sub as $key => $sub_value) {
                if ($sub_value->id!=$value->id) {
                    if ($temp_data["have_subs"] != true) {
                       $temp_data["have_subs"] = true;
                    }
                }else {
                    $temp_data["have_subs"] = false;
                }
            }
            if (count($sub)==0) {
                $temp_data["have_subs"] = false;
            }
            array_push($categories_with_sub, $temp_data);
        }
        return new DefaultGetResponse($categories_with_sub);
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
