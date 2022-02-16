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

use function GuzzleHttp\Promise\all;

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
        $roles = Role::where('name', '!=', 'superadmin')->get();
        $query = Setting::query();
        $settings = $query->paginate(10);
        if ($request->ajax()) {
            return view('pages.pengaturan.setting.pagination', compact('roles', 'settings', 'data'))->render();
        }
        if ($request->keyword) {
            $query->when('keyword', function ($sub) use ($request) {
                $keyword = Str::slug($request->keyword);
                $sub->where(function ($q) use ($keyword) {
                    $q->where('key', 'LIKE', "%$keyword%")
                    ->orWhere('value', 'LIKE', "%$keyword%")
                    ->orWhere('role', 'LIKE', "%$keyword%");
                });
            });
            $settings = $query->paginate(10);
        }
        return view('pages.pengaturan.setting.index', compact('roles', 'settings', 'data'));
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
            if ($key=="minimal-belanja") {
                $role = $role == "distributor" ? $request->distributorType . "-" . $role : $role;
                # code...
            }
        }
        $setting = Setting::checkData($key, $role);
        if ($setting) {
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
            'discount' => $request->discount,
            'minimal_transaction' => $request->minimal_transaction,
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
        try {
            $role = $request->role;
            if ($role) {
                $role = $role == "distributor" ? $request->distributorType . "-" . $role : $role;
            }
            $request->role = $role;
            $key = Str::slug($request->key);
            $setting = Setting::find($id);
            if ($key == "minimal-belanja") {
                $setting->update([
                    "key" => $key,
                    "role" => $role,
                    'discount' => $request->discount,
                    "value" => $request->value,
                    "minimal_transaction" => $request->minimal_transaction
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Sukses',
                    'body' => 'Berhasil Update Setting'
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => [
                    'head' => 'gagal',
                    'body' => $th->getMessage()
                ]
            ], 200);
            //    return $th->getMessage();
        }
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
