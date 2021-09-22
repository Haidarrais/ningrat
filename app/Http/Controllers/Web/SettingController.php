<?php

namespace App\Http\Controllers\Web;

use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\SettingStoreRequest;
use App\Http\Requests\Setting\SettingUpdateRequest;
use App\Traits\SettingTrait;
use Spatie\Permission\Models\Role;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $roles = Role::where('name','!=', 'superadmin')->get();
        $query = Setting::query();
        $settings = $query->paginate(10);
        if($request->ajax()) {
            return view('pages.pengaturan.setting.pagination', compact('roles','settings', 'data'))->render();
        }
        return view('pages.pengaturan.setting.index', compact('roles','settings', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingStoreRequest $request)
    {
        $key = Str::slug($request->key);
        $role = $request->role;
        if ($role) {
            $role= $role=="distributor"?$request->distributorType . "-" . $role : $role;
        }
        $setting = Setting::checkData($key, $role);
        if($setting) {
            return response()->json([
                'status' => false,
                'message' => [
                    'head' => 'Gagal',
                    'body' => 'Data sudah ada, silahkan edit untuk mengupdate'
                ]
            ], 500);
        }
        Setting::create([
            'key' => $key,
            'role' => $role,
            'value' => $request->value
        ]);
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Tambah Setting'
            ]
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting = Setting::find($id);
        return response()->json([
            'status' => true,
            'data' => $setting
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SettingUpdateRequest $request, $id)
    {
        $setting = Setting::find($id);
        $setting->update([
            'key' => Str::slug($request->key),
            'value' => $request->value
        ]);
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Update Setting'
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $setting = Setting::find($id);
        $setting->delete();
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Hapus setting'
            ]
        ], 200);
    }
}
