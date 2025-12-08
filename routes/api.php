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
use App\Http\Controllers\api\StudentLayoutController;
use App\Http\Controllers\api\SubjectController;
use App\Http\Controllers\api\TeacherController;
use App\Http\Controllers\api\TeacherNoteController;
use App\Http\Controllers\api\UniversityController;
use App\Http\Controllers\api\VideoController;
use App\Http\Controllers\api\YearController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

Route::post('/admin/login',[AuthController::class,'loginadmin']);
Route::post('/teacher/login',[AuthController::class,'loginteacher']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


// student
Route::post('/student/login',[StudentLayoutController::class,'loginstudent']);
Route::get('/student/signup',[StudentLayoutController::class,'signupstudent']);
Route::post('/student/signup',[StudentLayoutController::class,'signupnewstudent']);
Route::get('/student/gethome',[StudentLayoutController::class,'homepage']);
Route::get('/student/freecourses',[StudentLayoutController::class,'freecourses']);
Route::get('/student/coursesearch',[StudentLayoutController::class,'coursesearchbyname']);
Route::get('/student/teachersearch',[StudentLayoutController::class,'teachersearchbyname']);
Route::get('/student/subjectsearch',[StudentLayoutController::class,'subjectsearchbyname']);
Route::get('/student/mysubjects',[StudentLayoutController::class,'getstudentsubjects']);
Route::get('/student/getyears',[StudentLayoutController::class,'uniyears']);
Route::get('/student/teacher',[StudentLayoutController::class,'getteachers']);
Route::get('/student/subjects',[StudentLayoutController::class,'getsubjects']);
Route::post('/student/add/subject',[StudentLayoutController::class,'addstudentsubject']);
Route::delete('/student/delete/subject',[StudentLayoutController::class,'deletestudentsubject']);
Route::get('/student/subjectcourses',[StudentLayoutController::class,'getcoursefromsubject']);
Route::get('/student/lecturevideos',[StudentLayoutController::class,'getlecturewithvideos']);
Route::get('/student/lecturefiles',[StudentLayoutController::class,'getlecturefiles']);
Route::get('/student/videodetails',[StudentLayoutController::class,'getvideodetails']);
Route::get('/student/noteifi',[StudentLayoutController::class,'uninote']);
Route::get('/student/subscriptions',[StudentLayoutController::class,'subscriptions']);

Route::post('/student/redeemcode',[StudentLayoutController::class,'redeemCode']);

Route::get('/student/coursedetails',[StudentLayoutController::class,'coursedetails']);
Route::get('/student/getstudentprofile',[StudentLayoutController::class,'studentprofile']);
Route::put('/student/updateuniversity',[StudentLayoutController::class,'updatestudentuniversity']);
Route::put('/student/updatepassword',[StudentLayoutController::class,'updatestudentpass']);
Route::put('/student/updateprofile',[StudentLayoutController::class,'updatestudentprofile']);
Route::get('/student/salespoints',[StudentLayoutController::class,'salespoints']);
Route::get('/student/contact',[StudentLayoutController::class,'contactinfo']);

Route::post('/student/teacherfav',[StudentLayoutController::class,'addteacherfaivorit']);
Route::delete('/student/teachernotfav',[StudentLayoutController::class,'deleteteacherfaivorit']);
Route::post('/student/courserate',[StudentLayoutController::class,'addRate']);
Route::get('/student/teachercourses',[StudentLayoutController::class,'teachercourses']);
Route::get('/student/yearcourses',[StudentLayoutController::class,'yearcourses']);

// teacher
Route::get('/data/teacher/mycourses',[TeacherController::class,'mycourses']);
Route::post('/data/teacher/note',[TeacherNoteController::class,'teacherstore']);
Route::post('/data/teacher/lecturefile',[LectureController::class,'lecturefile']);



// admin
Route::get('/data/admin/test',[AdminController::class,'createCodeGroup']);
Route::get('/data/admin/home',[AdminController::class,'mainlayout']);
Route::post('/data/admin/note',[TeacherNoteController::class,'adminstore']);
Route::put('/data/admin/note/{id}',[TeacherNoteController::class,'statue']);
Route::delete('/data/admin/note/{id}',[TeacherNoteController::class,'deletenote']);


Route::get('/migrate-and-seed', function () {
    Artisan::call('migrate:fresh --seed');
    return response()->json(['message' => 'Database migrated and seeded successfully!']);
});
