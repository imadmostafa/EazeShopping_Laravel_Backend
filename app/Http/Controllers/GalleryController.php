<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Dotenv\Validator;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //working , needed to retrieve image through json now ,submit via formdata not json



        if ($files = $request->file('file')) {

            //store file into document folder
            $file = $request->file('file')->store('storage/uploads', 'public');


            //store your file into database
            $document = new Gallery();
            $document->path =  env('APP_URL') . '/' . $file;
            $document->name = $request->name;
            $document->save();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $document->path
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        $gallery_id = request('id');
        $gallery_tobeDeleted = Gallery::find($gallery_id);
        $image_path = $gallery_tobeDeleted->path; // Value is not URL but directory file path
        //$gallery_tobeDeleted->delete();
        if (File::exists($image_path)) {
            File::delete($image_path);
            $gallery_tobeDeleted->delete();
            return response()->json([
                "success" => true,

            ]);
        }
        return response()->json([
            "success" => false,

        ]);
    }





    public function getImagebyId($id)
    {
        $image = Gallery::find($id);
        $path = $image->path;
        return response()->download(public_path($path));
    }
}
