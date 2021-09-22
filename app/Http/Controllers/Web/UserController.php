<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RequestUpgrade;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        $query = User::query();
        // Search ajax
        $query->when('keyword', function($sub) use($request) {
            $keyword = $request->keyword;
            $sub->where(function($q) use($keyword){
                $q->where('name', 'LIKE', "%$keyword%")
                ->orWhere('email', 'LIKE', "%$keyword%");
            });
        });
        if($user->getRoleNames()->first() != 'superadmin') {
            $query->where(function($sub) use($user) {
                $sub->where('upper', $user->id)->orWhere('add_by', $user->id);
            });
        }
        $users = $query->paginate(10);
        $all_user = User::all();
        if($request->ajax()) {
            return view('pages.pengaturan.user.pagination', compact('users', 'data'))->render();
        }
        return view('pages.pengaturan.user.index', compact('users', 'data', 'all_user', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $request->merge([
            'add_by' => Auth::user()->id
        ]);
        $role = $request->role ?? 'subagent';
        $user = User::create($request->all());
        $user->assignRole($role);
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Tambah User'
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
        $user = User::with(['roles'])->find($id);
        return response()->json([
            'status' => true,
            'data' => $user
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);
        // Jika password kosong maka param password di hapus
        if($request->password == '') {
            $user->update($request->except(['password']));
        } else {
            $user->update($request->all());
        }
        $user->assignRole($request->role);
        //update nilai updated_at di user untuk melacak kapan role user berubah
        $user->update(['updated_at'=>Carbon::now()]);
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Update User'
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
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Hapus User'
            ]
        ], 200);
    }

    public function hirarki($api_token) {
        $user = User::where('api_token', base64_decode($api_token))->first();
        return view('pages.pengaturan.user.hirarki', compact('user'));
    }

    public function set_status(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $user = User::find($id);
        $user->update([
            'status' => $status
        ]);
        if($status) {
            $text = 'Mengaktifkan User';
        } else {
            $text = 'Menonaktifkan User';
        }
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => $text
            ]
        ], 200);
    }

    public function upgrade(Request $request) {
        $id = $request->id;
        $user = User::find($id);
        $request_upgrade = RequestUpgrade::where('user_id', $id)->where('status', 1)->latest()->first();
        $request_upgrade->update([
            'status' => 2
        ]);
        $role_id = $user->roles->first()->id;
        $role = Role::find($role_id);
        $user->removeRole($role->name);
        $user->syncRoles(($role_id - 1));
        $new_role = Role::find(($role_id - 1));
        $user->update([
            'last_upgrade' => Carbon::now()
        ]);
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Upgrade ke '. $new_role->name
            ]
        ], 200);
    }
}
