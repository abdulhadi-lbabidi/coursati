<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Lecture;
use App\Models\LectureFile;
use Illuminate\Http\Request;

class LectureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['lecture'=>Lecture::all()]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'desc'=> 'required|string',
            'course_id'=> 'required|integer'
        ]);
        $lecture = Lecture::create([
            'name' => $request->input('name'),
            'desc'=> $request->input('desc'),
            'course_id' => $request->input('course_id'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت إضافة محاضرة بنجاح','lecture'=>$lecture]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lecture=Lecture::findOrFail($id);
        return response()->json(['lecture'=>$lecture]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $val = $request->validate([
            'name'=> 'required|string',
            'course_id'=> 'required|integer'
        ]);
        $lecture=Lecture::findOrFail($id);
        $lecture->update($val);
        return response()->json(['lecture'=>$lecture]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lecture = Lecture::findOrFail($id);
        $lecture->delete();
        return response()->json(['statue'=>200,'message'=>'تمت حذف المحاضرة بنجاح']);

    }

    public function lecturefile(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'file_url'=> 'required|string',
            'lecture_id'=> 'required|integer'
        ]);
        $lecture = LectureFile::create([
            'name' => $request->input('name'),
            'file_url'=> $request->input('file_url'),
            'lecture_id' => $request->input('lecture_id'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت إضافة ملف للمحاضرة بنجاح','FILE'=>$lecture]);
    }
}
