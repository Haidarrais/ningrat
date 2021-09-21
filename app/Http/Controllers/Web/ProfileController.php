<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Order;
use App\Models\RoleUpgrade;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RequestUpgrade;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function index() {
        $user_id = Auth::id();
        $user = User::with('member.city')->find($user_id);
        $role_id = $user->roles->pluck('id')->first();
        $role_upgrade = RoleUpgrade::where('role_id', $role_id)->where('status', 1)->get();
        $status_request = RequestUpgrade::where('user_id', $user->id)->where('status', 1)->first();
        return view('pages.pengaturan.profile.index', compact('user', 'role_upgrade', 'status_request'));
    }

    public function update(UpdateProfileRequest $request) {
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
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Sukses',
                    'body' => 'Berhasil Update Profile'
                ]
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Gagal',
                    'body' => 'Gagal Update Profile'
                ]
            ], 500);
            DB::rollBack();
        }
    }

    public function upgrade(Request $request) {
        $user = Auth::user();
        $role_id = $user->roles->pluck('id')->first();
        $role_upgrade = RoleUpgrade::where('role_id', $role_id)->where('status', 1)->get();
        $status_upgrade = false;
        foreach ($role_upgrade as $key => $value) {
            if(strpos(Str::lower($value->description), Str::lower("Member Setelah Upgrade")) > 0) {
                $last_upgrade = Carbon::make($user->last_upgrade);
                if($last_upgrade->diffInMonths(Carbon::now()) >= $value->value) {
                    $status_upgrade = true;
                } else {
                    $status_upgrade = false;
                    break;
                }
            } else if(strpos(Str::lower($value->description), Str::lower("Marketing")) > 0) {
                if(User::where('upper', $user->id)->count() >= $value->value) {
                    $status_upgrade = true;
                } else {
                    $status_upgrade = false;
                    break;
                }
            } else if(strpos(Str::lower($value->description), Str::lower("Penjualan")) > 0) {
                if($this->get_omset($value->value)) {
                    $status_upgrade = true;
                } else {
                    $status_upgrade = false;
                    break;
                }
            }
        }

        if($status_upgrade) {
            RequestUpgrade::updateOrCreate([
                'user_id' => $user->id,
                'role_id' => ($role_id - 1),
            ], [
                'user_id' => $user->id,
                'role_id' => ($role_id - 1),
                'status' => 1
            ]);
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Berhasil',
                    'body' => 'Request anda berhasil'
                ]
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => [
                    'head' => 'Gagal',
                    'body' => 'Anda belum bisa upgrade level'
                ]
            ], 500);
        }
    }

    public static function get_omset($value) {
        $user = Auth::user();
        $data = unserialize($value);
        $pendapatan = Order::selectRaw("SUM(subtotal) AS pendapatan, MONTH(created_at) AS bulan")
                ->where('user_id', $user->id)
                ->whereDate('created_at', '>', $user->last_upgrade)
                ->groupBy(DB::raw("MONTH(created_at)"))->get();
        $result = false;
        if(count($pendapatan) >= $data[1]) {
            foreach ($pendapatan as $key => $value) {
                if($value->pendapatan > ($data[0] * 1000000)) $result = true;
                else {
                    $result = false;
                    break;
                }
            }
        }
        return $result;
    }
}
