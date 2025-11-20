<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CourseRate;
use Illuminate\Http\Request;

class CourseRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'stars'=> 'required|decimal:0,5',
            'course_id'=> 'required|integer',
            'student_id'=> 'required|integer',
        ]);

        CourseRate::create([
            'stars' => $request->input('stars'),
            'course_id' => $request->input('course_id'),
            'student_id' => $request->input('student_id'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت إضافة التقييم بنجاح']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $courserate = CourseRate::findOrFail($id);
        $courserate->delete();
        return response()->json(['statue'=>200,'message'=>'تمت الإزالة التقييم بنجاح']);
    }
}
