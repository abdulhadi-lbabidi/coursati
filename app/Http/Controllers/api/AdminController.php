<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\CodeGroup;
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

    public function createCodeGroup(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'number_of_codes' => 'required|integer|min:1',
    ]);

    $codeGroup = CodeGroup::create([
        'course_id' => $request->course_id,
        // other fields as needed
        'price'=>10000,
        'persentage'=>0.15
    ]);

    // Allowed chars: 0-9, A-Z, symbols (define allowed symbols)
    $allowedChars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codesToCreate = [];

    for ($i = 0; $i < $request->number_of_codes; $i++) {
        // Generate a unique 6-char code
        do {
            $code = '';
            for ($j = 0; $j < 6; $j++) {
                $code .= $allowedChars[random_int(0, strlen($allowedChars) - 1)];
            }
        } while (Code::where('code', $code)->exists());

        $codesToCreate[] = [
            'code_group_id' => $codeGroup->id,
            'code' => $code,
            'statue' => 0, // or whatever status you use
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    Code::insert($codesToCreate);

    return response()->json(['message' => 'Code group and codes created successfully']);
}
}
