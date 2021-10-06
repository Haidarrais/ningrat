<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testimonials = Testimonial::all();
        return view('pages.pengaturan.testimonial.index', compact('testimonials'));
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
            'actor' => 'required',
            'word' => 'required',
            'image' => 'image|mimes:png,jpg'
        ]);

        if ($request->hasFile('image')) {
            $imagePost = 'IMAGE-TESTIMONIAL'.time().$request->file('image')->getClientOriginalName();
            $file = $request->image;
            $fileName = $imagePost;
            $file->move('uploads/contents',$fileName);
            $image = $fileName;
        }

        Testimonial::create([
            'name' => $request->name,
            'actor' => $request->actor,
            'word' => $request->word,
            'image' => $image,
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan testimoni');
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
        $testimonial = Testimonial::find($id);
        return response()->json([
            'status' => 1,
            'data' => $testimonial
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
        $testimonial = Testimonial::find($id);
        $request->validate([
            'name' => 'required',
            'actor' => 'required',
            'word' => 'required',
            'image' => 'image|mimes:png,jpg'
        ]);

        if ($request->hasFile('image')) {
            $imagePost = 'IMAGE-TESTIMONIAL'.time().$request->file('image')->getClientOriginalName();
            $file = $request->image;
            $fileName = $imagePost;
            $file->move('uploads/contents',$fileName);
            $image = $fileName;
            $testimonial->image = $image;
        }

        $testimonial->name  = $request->name;
        $testimonial->actor = $request->actor;
        $testimonial->word = $request->word;

        $testimonial->save();

        return redirect()->back()->with('success', 'Berhasil update testimonial');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Testimonial::destroy($id);
        return redirect()->back()->with('success', 'Berhasil hapus testimonial');
    }
}
