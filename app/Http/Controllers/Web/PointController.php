<?php

namespace App\Http\Controllers\Web;

use App\Models\Point;
use App\Models\Reward;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reward\RewardStoreRequest;
use App\Http\Requests\Reward\RewardUpdateRequest;
use App\Http\Requests\Point\PointStoreRequest;
use App\Http\Requests\Point\PointUpdateRequest;



class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $query = Point::query();
        $points = $query->paginate(10);
        $categories = Category::all();
        if($request->ajax()) {
            return view('pages.pengaturan.point.pagination', compact('points', 'data'))->render();
        }
        return view('pages.pengaturan.point.index', compact('points', 'data', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PointStoreRequest $request)
    {
        Point::create($request->all());
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Tambah Point'
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
        $point = Point::find($id);
        return response()->json([
            'status' => true,
            'data' => $point
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PointUpdateRequest $request, $id)
    {
       
        $point = Point::find($id);
        $point->update($request->all());
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Update Point'
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
        $point = Point::find($id);
        $point->delete();
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Sukses',
                'body' => 'Berhasil Hapus Point'
            ]
        ], 200);
    }
}
