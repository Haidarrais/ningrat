<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carousel_items = Content::where('content_type', '=', '1')->get();
        $banner_items   = Content::where('content_type', '=', '2')->get();

        return view('pages.pengaturan.content.index', compact('carousel_items', 'banner_items'));
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'content_type' => 'required|integer|in:1,2',
            'image' => 'image|mimes:png,jpg'
        ]);

        if ($request->hasFile('image')) {
            $imagePost = 'IMAGE-CONTENT'.time().$request->file('image')->getClientOriginalName();
            $file = $request->image;
            $fileName = $imagePost;
            $file->move('uploads/contents',$fileName);
            $image = $fileName;
        }

        Content::create([
            'name' => $request->name,
            'description' => $request->description,
            'content_type' => $request->content_type,
            'image' => $image,
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan konten');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $content = Content::find($id);
        return response()->json([
            'status' => 1,
            'data' => $content
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $content = Content::find($id);
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'content_type' => 'required|integer|in:1,2',
            'image' => 'image|mimes:png,jpg'
        ]);

        if ($request->hasFile('image')) {
            $imagePost = 'IMAGE-CONTENT'.time().$request->file('image')->getClientOriginalName();
            $file = $request->image;
            $fileName = $imagePost;
            $file->move('uploads/contents',$fileName);
            $image = $fileName;
            $content->image = $image;
        }

        $content->name  = $request->name;
        $content->description = $request->description;
        $content->content_type = $request->content_type;

        $content->save();

        return redirect()->back()->with('success', 'Berhasil update konten');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Content::destroy($id);
        return redirect()->back()->with('success', 'Berhasil hapus konten');
    }
}
