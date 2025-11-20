<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\StudentFaivoritTeacher;
use Illuminate\Http\Request;

class StudentFaivoritController extends Controller
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
            'is_favorit'=> 'required|boolean',
            'student_id'=> 'required|integer',
            'teacher_id'=> 'required|integer',
        ]);
        StudentFaivoritTeacher::create([
            'is_favorit' => $request->input('is_favorit'),
            'student_id' => $request->input('student_id'),
            'teacher_id' => $request->input('teacher_id'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت إضافة للمفضلة بنجاح']);
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
        $faiv = StudentFaivoritTeacher::findOrFail($id);
        $faiv->delete();
        return response()->json(['statue'=>200,'message'=>'تمت الإزالة من المفضلة بنجاح']);
    }
}
