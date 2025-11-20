<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['teacher'=>Teacher::all()->load('university')]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'phone'=> 'required|string',
            'telegram_url'=> 'required|string',
            'is_deleted'=> 'boolean|nullable',
            'university_id'=> 'required|integer',
            'gender'=> 'required|string',
            'email' => 'required|email|unique:users',
            'password'=> 'required|string',
        ]);


        DB::beginTransaction();
        $teacher = Teacher::create([
            'name' => $request->input('name'),
            'telegram_url' => $request->input('telegram_url'),
            'is_deleted' => $request->input('is_deleted'),
            'university_id' => $request->input('university_id'),
        ]);
        $user = $teacher->user()->create([
            'email' =>$request->input('email'),
            'password' =>$request->input('password') ,
            'phone' =>$request->input('phone') ,
            'gender' =>$request->input('gender') ,
        ]);
        DB::commit();
        return response()->json(['statue'=>200,'message'=>'تمت إضافة الاستاذ بنجاح','teacher'=>$teacher,'user'=>$user]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teacher=Teacher::findOrFail($id);
        return response()->json(['teacher'=>$teacher]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $valst = $request->validate([
            'name'=> 'required|string',
            'telegram_url'=> 'required|string',
            'is_deleted'=> 'boolean|nullable',
            'university_id'=> 'required|integer',

        ]);
        $valus = $request->validate([
            'phone'=> 'required|string',
            'gender'=> 'required|string',
        ]);
        DB::beginTransaction();
        $teacher=Teacher::findOrFail($id);
        $teacher->update($valst);
        $user = $teacher->user()->update($valus);
        DB::commit();
        return response()->json(['teacher'=>$teacher]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();
        return response()->json(['statue'=>200,'message'=>'تم حذف الاستاذ بنجاح']);
    }

    public function mycourses(Request $request)
    {
        $val = $request->validate([
            'teacher_id'=> 'required|integer',
        ]);
        $mycourses = Course::where('teacher_id','=',$val['teacher_id'])->get();
        return response()->json(['mycourses'=>$mycourses]);
    }
}
