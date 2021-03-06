<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PageRewardController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();
        $reward = Reward::where("status", 1)->paginate(10);
        if ($request->keyword) {
            $query = Reward::query();
            $query->when('keyword', function ($sub) use ($request) {
                $keyword = $request->keyword;
                $sub->where(function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%$keyword%")->where("status", 1);
                });
            });
            $reward = $query->paginate(10);
        }
        return view('pages.other.reward.index', compact('user', 'reward'));
    }

    public function reward_user(Request $request) {
        $no = 1;
        $users = User::whereHas('reward')->with('reward')->paginate(5);
        if($request->keyword){
            $query = User::query();
            $query->when('keyword', function ($sub) use ($request) {
                $keyword = $request->keyword;
                $sub->where(function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%$keyword%")->whereHas('reward')->with('reward');
                });
            });
            $users = $query->paginate(5);
        }
        // dd($users);
        return view('pages.pengaturan.reward.user_reward')->with(["users"=>$users, "no"=>$no]);
    }

    /** Ajax */

    public function penukaran_reward(Request $request) {
        $user = User::find($request->USER_ID);
        $reward = Reward::find($request->id);
        $exchangedPoint = DB::table('reward_user')
        ->where('user_id', '=', $user->id)
        ->where('created_at', '>', Carbon::now()->subMonths(6))
        ->get();
        if(count($exchangedPoint)>0) {
            return response()->json([
                'error' => true,
                'message' => [
                    'head' => 'Gagal',
                    'body' => 'Anda hanya dapat menukarkan point 6 bulan sekali'
                ]
            ], 500);
        }
        if($user->total_point >= $reward->point) {
            DB::table("reward_user")->insert([
                'reward_id' => $reward->id,
                'user_id' => $user->id,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return response()->json([
                'error' => false,
                'message' => [
                    'head' => 'Berhasil',
                    'body' => 'Berhasil menukarkan point, Tunggu admin untuk menyetujui'
                ]
            ], 200);
        }
        return response()->json([
            'error' => true,
            'message' => [
                'head' => 'Gagal',
                'body' => 'Point kamu tidak cukup'
            ]
        ], 500);
    }

    public function set_status(Request $request) {
        $M_Reward = DB::table('reward_user')->where("id", $request->id)->first();
        $user = User::find($M_Reward->user_id);
        $reward = Reward::find($M_Reward->reward_id);
        if($user->total_point >= $reward->point) {
            DB::table('reward_user')->where("id", $request->id)->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);
            if($request->status == 1) {
                $text = "Approve";
            } else {
                $text = "Reject";
            }
            return response()->json([
                'error' => false,
                'message' => [
                    'head' => 'Berhasil',
                    'body' => 'Berhasil ' . $text
                ]
            ], 200);
        }
        return response()->json([
            'error' => true,
            'message' => [
                'head' => 'Gagal',
                'body' => 'Point kamu tidak cukup'
            ]
        ], 500);
    }

    public function riwayat_penukaran($user_id) {
        $user_penukaran = User::with(['reward'])->find($user_id);
        $no = 0;
        return view('pages.other.reward.ajax.grid_riwayat_penukaran', compact('user_penukaran', 'no'))->render();
    }

    public function riwayat_perolehan($user_id) {
        $user_perolehan = User::with(['point'])->find($user_id);
        $no = 0;
        return view('pages.other.reward.ajax.grid_riwayat_perolehan', compact('user_perolehan', 'no'))->render();
    }
}
