<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['student'=>Student::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'phone'=> 'required|string|unique:users',
            'gender'=> 'required|string',
            'year_id'=> 'required|integer',
            'is_banned'=> 'boolean|nullable',
            'password'=> 'required|string',
        ]);
        DB::beginTransaction();
        $student = Student::create([
            'name' => $request->input('name'),
            'year_id' => $request->input('year_id'),
            'is_banned' => $request->input('is_banned'),
        ]);
        $user = $student->user()->create([
            'password' =>$request->input('password') ,
            'phone' =>$request->input('phone') ,
            'gender' =>$request->input('gender') ,
        ]);
        DB::commit();

        return response()->json(['statue'=>200,'message'=>'تمت إضافة طالب بنجاح','student'=>$student,'user'=>$user]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Student=Student::findOrFail($id);
        return response()->json(['Student'=>$Student]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $valst = $request->validate([
            'name'=> 'required|string',
            'year_id'=> 'required|integer',
        ]);
        $valus = $request->validate([
            'phone'=> 'required|string',
            'gender'=> 'required|string',
        ]);
        DB::beginTransaction();
        $student=Student::findOrFail($id);
        $student->update($valst);
        $user = $student->user()->update($valus);
        DB::commit();
        return response()->json(['statue'=>200,'message'=>'تمت تعديل طالب بنجاح','student'=>$student,'user'=>$user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return response()->json(['statue'=>200,'message'=>'تمت حذف الطالب بنجاح']);
    }
}
