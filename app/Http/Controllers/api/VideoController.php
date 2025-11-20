<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['video'=>Video::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'video_url'=> 'required|string',
            'is_free'=> 'boolean|nullable',
            'lecture_id'=> 'required|integer'
        ]);
        Video::create([
            'name' => $request->input('name'),
            'video_url' => $request->input('video_url'),
            'is_free' => $request->input('is_free'),
            'lecture_id' => $request->input('lecture_id'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت إضافة فيديو بنجاح']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $video=Video::findOrFail($id);
        return response()->json(['video'=>$video]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $val = $request->validate([
            'name'=> 'required|string',
            'video_url'=> 'required|string',
            'is_free'=> 'boolean|nullable',
            'lecture_id'=> 'required|integer'
        ]);
        $video=Video::findOrFail($id);
        $video->update($val);
        return response()->json(['video'=>$video]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $video = Video::findOrFail($id);
        $video->delete();
        return response()->json(['statue'=>200,'message'=>'تمت حذف الكورس بنجاح']);
    }
}
