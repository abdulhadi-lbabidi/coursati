<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TeacherNotification;
use Illuminate\Http\Request;

class TeacherNoteController extends Controller
{
    public function teacherstore(Request $request)
    {
        $request->validate([
            'title'=> 'required|string',
            'description'=> 'required|text',
            'university_id'=> 'required|integer',
            'teacher_id'=> 'required|integer',
        ]);
        $teachernote = TeacherNotification::create([
            'title'=> $request->input('title'),
            'description'=> $request->input('description'),
            'is_allowed'=> false,
            'university_id'=> $request->input('university_id'),
            'teacher_id'=> $request->input('teacher_id'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت إضافة الإشعار بنجاح','teachernote'=>$teachernote]);

    }
    public function adminstore(Request $request)
    {
        $request->validate([
            'title'=> 'required|string',
            'description'=> 'required|string',
            'is_allowed'=> 'required|boolean',
            'university_id'=> 'required|integer',
            'teacher_id'=> 'required|integer',
        ]);
        $teachernote = TeacherNotification::create([
            'title'=> $request->input('title'),
            'description'=> $request->input('description'),
            'is_allowed'=> $request->input('is_allowed'),
            'university_id'=> $request->input('university_id'),
            'teacher_id'=> $request->input('teacher_id'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت إضافة الإشعار بنجاح','teachernote'=>$teachernote]);

    }
    public function statue(Request $request,string $id)
    {
        $request->validate([
            'is_allowed'=> 'required|boolean',
        ]);
        $teachernote = TeacherNotification::findOrFail($id);
        $teachernote->update([
            'is_allowed'=>$request->input('is_allowed'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت تعديل الإشعار بنجاح','teachernote'=>$teachernote]);
    }
    public function deletenote(string $id)
    {
        $teachernote = TeacherNotification::findOrFail($id);
        $teachernote->delete();
        return response()->json(['statue'=>200,'message'=>'تمت حذف الإشعار بنجاح']);

        //
    }
}
