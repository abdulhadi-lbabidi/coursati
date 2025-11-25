<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AppUpdate;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\SalesPoint;
use App\Models\Student;
use App\Models\StudentSubjects;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherNotification;
use App\Models\University;
use App\Models\Video;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LayoutController extends Controller
{
    public function signupstudent()
    {
        $universities = University::where('is_deleted', '0')
            ->with('years') // Eager load the relationship
            ->get(); // Execute the query
        // The 'universities' collection now includes the 'notdeletedyears' relationship
        return response()->json([
            'universities' => $universities
        ]);
    }
    public function signupnewstudent(Request $request)
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
            'is_banned' => $request->input('is_banned') || false,
        ]);
        $year = Year::findOrFail($request->input('year_id'));
        $subjects = $year->subjectsync();
        $student->subjects()->syncWithoutDetaching($subjects->pluck('subjects.id'));
        $user = $student->user()->create([
            'password' =>$request->input('password') ,
            'phone' =>$request->input('phone') ,
            'gender' =>$request->input('gender') ,
        ]);
        $token = $user->createToken('main')->plainTextToken;
        DB::commit();

        return response()->json(['statue'=>200,'message'=>'تمت إضافة طالب بنجاح','student'=>$student->load('year.university'),'tohken'=>$token,'user'=>$student->user]);

    }
    public function homepage(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        $salespoint = SalesPoint::where('university_id', '=', $university->id)->get();

        $freecourses = Course::where('is_pending', 1)->where('is_deleted','=',false)->where('price','=',0)->inRandomOrder()->limit(10)->withAvg('rates', 'stars')->with(['subject.season.year', 'teacher'])->get();
        $recentcourses = Course::latest('created_at')->where('is_deleted','=', false)->with(['subject.season.year', 'teacher'])->withAvg('rates', 'stars')->take(5)->get();
        $mostvisited = Course::where('is_deleted','=',false)->with(['subject.season.year', 'teacher'])->withAvg('rates', 'stars')->limit(10)->get();
        return response()->json(['university' => $university, 'free_courses' => $freecourses, 'sales_points' => $salespoint, 'recently_added_courses' => $recentcourses, 'most_visited_courses' => $mostvisited]);
    }
    public function uniyears(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);

        return response()->json(['years' => $university->years]);
    }

    public function coursedetails(Request $request)
    {
        $valdata = $request->validate([
            'cousre_id' => 'required|integer',
            'student_id' => 'required|integer',
        ]);
        $course = Course::findOrFail($valdata['cousre_id'])->load('subject.season.year');
        $student = Student::findOrFail($valdata['student_id']);
        $rated = $student->rates->where('cousre_id','=',$course->id);
        $faivorit = $student->favorits->where('teacher_id','=',$course->teacher->id);
        return response()->json(['course' => $course, 'course_rate' => $course->rates()->sum('stars'),'faviorit'=>$faivorit,'rated'=>$rated]);
    }

    public function getteachers(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);

        return response()->json(['teachers' => $university->teachers]);
    }
    public function coursesearchbyname(Request $request)
    {
        $valdata = $request->validate([
            'name' => 'required|string',
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        $universityId = $university->id;
        $courses = Course::where('is_deleted', 0)->whereRelation('activesubscriptions', 'name', 'like', '%' . $valdata['name'] . '%')->withAvg('rates', 'stars')
    ->with(['subject.season.year', 'teacher','activesubscriptions'])
    ->get();
        return response()->json(['courses' => $courses]);
    }
    public function teachersearchbyname(Request $request)
    {
        $valdata = $request->validate([
            'name' => 'required|string',
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        $universityId = $university->id;
        $results = Teacher::where('name', 'like', '%' . $valdata['name'] . '%')->get();
        return response()->json(['teachers' => $results]);
    }
    public function subjectsearchbyname(Request $request)
    {
        $valdata = $request->validate([
            'name' => 'required|string',
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        $universityId = $university->id;
        $results = Subject::where('name', 'like', '%' . $valdata['name'] . '%')->get();
        return response()->json(['subject' => $results]);
    }
    public function getstudentsubjects(Request $request)
    {
        $valdata = $request->validate([
            'student_id' => 'required|integer',
        ]);
        $student = Student::findOrFail($valdata['student_id']);

        return response()->json(['year' => $student->year->load('seasons.subjects')]);
    }
    public function addstudentsubject(Request $request)
    {
        $valdata = $request->validate([
            'student_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ]);
        $student = Student::findOrFail($valdata['student_id']);
        $student->subjects()->syncWithoutDetaching($valdata['subject_id']);
        return response()->json(['year' => $student->year->load('seasons.subjects')]);
    }
    public function deletestudentsubject(Request $request)
    {
        $valdata = $request->validate([
            'id' => 'required|integer',
        ]);
        StudentSubjects::findOrFail($valdata['id']);
        return response()->json(['message' => 'success']);
    }
    public function getsubjects(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);

        return response()->json(['year' => $university->years->load('seasons.subjects')]);
    }
    public function getcoursefromsubject(Request $request)
    {

        $valdata = $request->validate([
            'subject_id' => 'required|integer',
        ]);
        $courses = Course::where('is_deleted', 0)
            ->whereHas('subject.season', function ($query) {
            $query->where('is_deleted', 0)
            ->whereHas('year', function ($query) {
                    $query->where('is_deleted', 0);
            });
        })->withAvg('rates', 'stars')
        ->with('subject.season.year') // eager load for access
        ->get();

        return response()->json(['courses' => $courses]);
    }
    public function getcoursefromteacher(Request $request)
    {

        $valdata = $request->validate([
            'teacher_id' => 'required|integer',
        ]);
        $teacher = Teacher::findOrFail($valdata['teacher_id']);
        // $courses = Course::where('is_deleted', 0)
        //     ->whereHas('subject.season', function ($query) {
        //     $query->where('is_deleted', 0)
        //     ->whereHas('year', function ($query) {
        //             $query->where('is_deleted', 0);
        //     });
        // })->withAvg('rates', 'stars')
        // ->with('subject.season.year') // eager load for access
        // ->get();

        return response()->json(['courses' => $teacher->courses->load('subject.season.year','teacher')]);
    }
    public function getcoursefromyear(Request $request)
    {

        $valdata = $request->validate([
            'year_id' => 'required|integer',
        ]);
        $year = Year::findOrFail($valdata['year_id']);
        $aa=$year->withAvg('subjects.courses.rates', 'stars')->load('subjects.courses');
        $courses = Course::where('is_deleted', 0)
            ->whereHas('subject.season', function ($query) {
            $query->where('is_deleted', 0)
            ->whereHas('year', function ($query) {
                    $query->where('is_deleted', 0);
            });
        })->withAvg('rates', 'stars')
        ->with('subject.season.year') // eager load for access
        ->get();

        return response()->json(['courses' => $courses,'$year'=>$aa]);
    }
    public function getlecvideoswithfiles(Request $request)
    {
        $valdata = $request->validate([
            'lecture_id' => 'required|integer',
        ]);
        $lecture = Lecture::findOrFail($valdata['lecture_id']);
        return response()->json(['lecture_name' => $lecture->name, 'lecture_desc' => $lecture->desc, 'lecture_videos' => $lecture->videos, 'lecture_files' => $lecture->files]);
    }
    public function videoshow(Request $request)
    {
        $valdata = $request->validate([
            'video_id' => 'required|integer',
        ]);
        $video = Video::findOrFail($valdata['video_id']);
        return response()->json(['video' => $video]);
    }
    public function uninote(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        $noteification = TeacherNotification::where('university_id', '=', $university->id)->limit(10)->get();
        return response()->json(['noteification' => $noteification]);
    }
    public function unisales(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        return response()->json(['sales_points' => $university->salepoints]);
    }
    public function getupdates()
    {
        $updates = AppUpdate::all();
        return response()->json(['updates' => $updates,]);
    }
    public function getvideodetails(Request $request)
    {
        $valdata = $request->validate([
            'video_id' => 'required|integer',
        ]);
        $video = Video::findOrFail($valdata['video_id']);
        return response()->json(['video' => $video->load('timing'),]);
    }
    public function editprofile(Request $request)
    {
        $valdata = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|integer',
            'year_id' => 'required|integer',
            'gender' => 'required|integer',
        ]);

        $student = Student::findOrFail($valdata['id']);
        DB::beginTransaction();
        $student->update([
            'name' => $request->input('name'),
            'year_id' => $request->input('year_id'),
            'is_banned' => $request->input('is_banned') || false,
        ]);
        $student->user->update([
            'gender' =>$request->input('gender') ,
        ]);
        DB::commit();
        return response()->json(['student' => $student->load('user'),]);
    }
}
