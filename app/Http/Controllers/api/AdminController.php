<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
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
        //
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
        //
    }

    public function mainlayout()
    {

        $oneMonthAgo = Carbon::now()->subMonth();
        $newStudents = Student::where('created_at', '>=', $oneMonthAgo)->get();
        $courses = Course::all();
        $teachers = Teacher::all();
        $teachersnote = TeacherNotification::all();
        $recentcourses = Course::latest('created_at')->with(['subject','teacher'])->take(5)->get();
        $mostvisited = Course::orderBy('likes', 'desc')->limit(10)->get();
        return response()->json(['courses_count'=>$courses->count(),'teacher_count'=>$teachers->count(),'newStudents'=>$newStudents,'teachersnote'=>$teachersnote->count(),'recentcourses'=>$recentcourses,'mostvisited'=>$mostvisited]);
    }
}
