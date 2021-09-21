<?php

namespace App\Http\Controllers\Web;

use App\Models\Reward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reward\RewardStoreRequest;
use App\Http\Requests\Reward\RewardUpdateRequest;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $query = Reward::query();
        $rewards = $query->paginate(10);
        if($request->ajax()) {
            return view('pages.pengaturan.reward.pagination', compact('rewards', 'data'))->render();
        }
        return view('pages.pengaturan.reward.index', compact('rewards', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RewardStoreRequest $request)
    {
        $request->merge([
            'status' => 1
        ]);
        Reward::create($request->all());
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Tambah Reward'
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
        $reward = Reward::find($id);
        return response()->json([
            'status' => true,
            'data' => $reward
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RewardUpdateRequest $request, $id)
    {
        $reward = Reward::find($id);
        $reward->update($request->all());
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Update Reward'
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
        $reward = Reward::find($id);
        $reward->delete();
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Hapus Reward'
            ]
        ], 200);
    }

    public function set_status(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $reward = Reward::find($id);
        $reward->update([
            'status' => $status
        ]);
        if($status) {
            $text = 'Mengaktifkan Reward';
        } else {
            $text = 'Menonaktifkan Reward';
        }
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => $text
            ]
        ], 200);
    }
}
