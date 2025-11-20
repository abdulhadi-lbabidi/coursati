<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['course'=>Course::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'desc'=> 'required|string',
            'is_free'=> 'boolean|nullable',
            'teacher_id'=> 'required|integer',
            'subject_id'=> 'required|integer'
        ]);
        $course = Course::create([
            'name' => $request->input('name'),
            'desc' => $request->input('desc'),
            'is_free' => $request->input('is_free'),
            'teacher_id' => $request->input('teacher_id'),
            'subject_id' => $request->input('subject_id'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت إضافة كورس بنجاح','course'=>$course]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course=Course::findOrFail($id);
        return response()->json(['course'=>$course]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $val = $request->validate([
            'name'=> 'required|string',
            'desc'=> 'required|string',
            'is_free'=> 'boolean|nullable',
            'teacher_id'=> 'required|integer',
            'subject_id'=> 'required|integer'
        ]);
        $course=Course::findOrFail($id);
        $course->update($val);
        return response()->json(['course'=>$course]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(['statue'=>200,'message'=>'تمت حذف الكورس بنجاح']);
    }
}
