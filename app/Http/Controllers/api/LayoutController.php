<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AppUpdate;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\SalesPoint;
use App\Models\Season;
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
