<?php

use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CourseController;
use App\Http\Controllers\api\CourseRateController;
use App\Http\Controllers\api\LayoutController;
use App\Http\Controllers\api\LectureController;
use App\Http\Controllers\api\SalesPointController;
use App\Http\Controllers\api\StudentController;
use App\Http\Controllers\api\StudentFaivoritController;
use App\Http\Controllers\api\SubjectController;
use App\Http\Controllers\api\TeacherController;
use App\Http\Controllers\api\TeacherNoteController;
use App\Http\Controllers\api\UniversityController;
use App\Http\Controllers\api\VideoController;
use App\Http\Controllers\api\YearController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::apiResource('/university',UniversityController::class);
// Route::apiResource('/year',YearController::class);
// Route::apiResource('/student',StudentController::class);
// Route::apiResource('/teacher',TeacherController::class);
// Route::apiResource('/teacherrate',StudentFaivoritController::class);
// Route::apiResource('/subject',SubjectController::class);
// Route::apiResource('/course',CourseController::class);
// Route::apiResource('/courserate',CourseRateController::class);
// Route::apiResource('/lecture',LectureController::class);
// Route::apiResource('/video',VideoController::class);
// Route::apiResource('/salespoint',SalesPointController::class);

Route::post('/student/login',[AuthController::class,'loginstudent']);
Route::post('/admin/login',[AuthController::class,'loginadmin']);
Route::post('/teacher/login',[AuthController::class,'loginteacher']);


// student
Route::get('/student/signup',[LayoutController::class,'signupstudent']);
Route::post('/student/signup',[LayoutController::class,'signupnewstudent']);
Route::get('/student/gethome',[LayoutController::class,'homepage']);
Route::get('/student/coursesearch',[LayoutController::class,'coursesearchbyname']);
Route::get('/student/teachersearch',[LayoutController::class,'teachersearchbyname']);
Route::get('/student/subjectsearch',[LayoutController::class,'subjectsearchbyname']);
Route::get('/student/getyears',[LayoutController::class,'uniyears']);
Route::get('/student/coursedetails',[LayoutController::class,'coursedetails']);
Route::get('/student/teacher',[LayoutController::class,'getteachers']);
Route::get('/student/mysubjects',[LayoutController::class,'getstudentsubjects']);
Route::post('/student/add/subject',[LayoutController::class,'addstudentsubject']);
Route::delete('/student/delete/subject',[LayoutController::class,'deletestudentsubject']);
Route::get('/student/subjects',[LayoutController::class,'getsubjects']);
Route::get('/student/courses',[LayoutController::class,'getcourse']);
Route::get('/student/yearcourses',[LayoutController::class,'getcoursefromyear']);
Route::get('/student/teachercourses',[LayoutController::class,'getcoursefromteacher']);
Route::get('/student/lecturevideofiles',[LayoutController::class,'getlecvideoswithfiles']);
Route::get('/student/noteifi',[LayoutController::class,'uninote']);
Route::get('/student/salespoints',[LayoutController::class,'unisales']);
Route::get('/student/getupdates',[LayoutController::class,'getupdates']);
Route::get('/student/videodetails',[LayoutController::class,'getvideodetails']);
Route::get('/student/qractive',[LayoutController::class,'getvideodetails']);
Route::put('/student/editprofile',[LayoutController::class,'editprofile']);


// teacher
Route::get('/data/teacher/mycourses',[TeacherController::class,'mycourses']);
Route::post('/data/teacher/note',[TeacherNoteController::class,'teacherstore']);
Route::post('/data/teacher/lecturefile',[LectureController::class,'lecturefile']);



// admin
Route::get('/data/admin/home',[AdminController::class,'mainlayout']);
Route::post('/data/admin/note',[TeacherNoteController::class,'adminstore']);
Route::put('/data/admin/note/{id}',[TeacherNoteController::class,'statue']);
Route::delete('/data/admin/note/{id}',[TeacherNoteController::class,'deletenote']);
