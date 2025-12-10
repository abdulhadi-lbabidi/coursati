<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Code;
use App\Models\Contactinfo;
use App\Models\Course;
use App\Models\CourseRate;
use App\Models\Forwardnotification;
use App\Models\Lecture;
use App\Models\SalesPoint;
use App\Models\Season;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\StudentSubjects;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherNotification;
use App\Models\University;
use App\Models\Video;
use App\Models\Year;
use Carbon\Carbon;
use Database\Seeders\ForwardnotificationSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentLayoutController extends Controller
{

    public function loginstudent(LoginRequest $request){
        $credentils = $request->validated();
        if (!Auth::attempt($credentils)) {
            return response()->json([
                'message' => 'Provided phone or password is incorrect'
            ], 401);
        }
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $token = $user->createToken('api-token')->plainTextToken;

        $data =Student::findOrFail($user->userable_id);
        // return response(compact('user','token'));
        $data->load('user','year.university');
        return response()->json(['student'=> $data, 'token' => $token,'user'=>$user]);
    }
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
            'year_id'=> 'required|integer',
            'is_banned'=> 'boolean|nullable',
            'password'=> 'required|string',
        ]);
        DB::beginTransaction();
        $student = Student::create([
            'name' => $request->input('name'),
            'year_id' => $request->input('year_id'),
            'is_banned' => $request->input('is_banned') || 0,
        ]);
        $year = Year::findOrFail($request->input('year_id'));
        $subjects = $year->subjectsync();
        $student->subjects()->syncWithoutDetaching($subjects->pluck('subjects.id'));
        $user = $student->user()->create([
            'password' =>$request->input('password') ,
            'phone' =>$request->input('phone') ,
        ]);
        $token = $user->createToken('api-token')->plainTextToken;
        DB::commit();

        return response()->json(['statue'=>200,'message'=>'تمت إضافة طالب بنجاح','student'=>$student->load('year.university'),'token'=>$token,'user'=>$student->user]);

    }
    public function homepage(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        $salespoint = SalesPoint::where('university_id', '=', $university->id)->get();

        $recentcourses = Course::latest('created_at')->where('is_deleted', 0)->where('is_pending', 0)
        ->whereHas('teacher', function ($query) {
            $query->where('statue', 'active')->orWhere('statue', 'banned');
        })
        ->whereHas('subject', function ($query) use ($university) {
            $query->where('is_deleted', 0)
                ->whereHas('year', function ($query) use ($university) {
                    $query->where('is_deleted', 0)->where('university_id','=',$university->id);
                });
        })->with(['subject.year', 'teacher'])->withAvg('rates', 'stars')->limit(5)
        ->get();


        $freecourses = Course::where('is_deleted', 0)->where('is_pending', 0)
        ->whereHas('teacher', function ($query) {
            $query->where('statue', 'active')->orWhere('statue', 'banned');
        })
        ->whereHas('subject', function ($query) use ($university) {
            $query->where('is_deleted', 0)
                ->whereHas('year', function ($query) use ($university) {
                    $query->where('is_deleted', 0)->where('university_id','=',$university->id);
                });
        })->where('price','=',0)->withAvg('rates', 'stars')->with(['subject.year', 'teacher'])->inRandomOrder()->limit(5)
        ->get();


        $mostvisited = Course::where('is_deleted', 0)->where('is_pending', 0)
        ->whereHas('teacher', function ($query) {
            $query->where('statue', 'active')->orWhere('statue', 'banned');
        })
        ->whereHas('subject', function ($query) use ($university) {
            $query->where('is_deleted', 0)
                ->whereHas('year', function ($query) use ($university) {
                    $query->where('is_deleted', 0)->orWhere('university_id','=',$university->id);
                });
        })->withAvg('rates', 'stars')->with(['subject.year', 'teacher'])->limit(5)
        ->get();

        return response()->json([ 'free_courses' => $freecourses, 'sales_points' => $salespoint, 'recently_added_courses' => $recentcourses, 'most_visited_courses' => $mostvisited]);
    }
    public function freecourses(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);

        $freecourses = Course::where('is_deleted', 0)->where('is_pending', 0)
        ->whereHas('teacher', function ($query) {
            $query->where('statue', 'active')->orWhere('statue', 'banned');
        })
        ->whereHas('subject', function ($query) use ($university) {
            $query->where('is_deleted', 0)
                ->whereHas('year', function ($query) use ($university) {
                    $query->where('is_deleted', 0)->where('university_id','=',$university->id);
                });
        })->where('price','=',0)->withAvg('rates', 'stars')->with(['subject.year', 'teacher'])
        ->get();
        return response()->json([ 'free_courses' => $freecourses]);
    }
    public function teachersearchbyname(Request $request)
    {
        $valdata = $request->validate([
            'name' => 'required|string',
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        $universityId = $university->id;
        $results = Teacher::where('statue','!=', 'deleted')->orWhere('statue','!=', 'pending')->where('name', 'like', '%' . $valdata['name'] . '%')->withCount('courses','faivorit')->with('university')->get();
        return response()->json(['teachers' => $results]);
    }
    public function coursesearchbyname(Request $request)
    {
        $valdata = $request->validate([
            'name' => 'required|string',
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);

        $courses  = Course::where('is_deleted', 0)->where('is_pending', 0)->where( 'name', 'like', '%'.$valdata['name'].'%')
        ->whereHas('teacher', function ($query) {
            $query->where('statue', 'active')->orWhere('statue', 'banned');
        })
        ->whereHas('subject', function ($query) use ($university) {
            $query->where('is_deleted', 0)
                ->whereHas('year', function ($query) use ($university) {
                    $query->where('is_deleted', 0)->where('university_id','=',$university->id);
                });
        })->withAvg('rates', 'stars')->with(['subject.year', 'teacher'])
        ->get();
        return response()->json(['courses' => $courses,'season'=>$university->seasons]);
    }

    public function subjectsearchbyname(Request $request)
    {
        $valdata = $request->validate([
            'name' => 'required|string',
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        $results = Subject::whereHas('year', function ($query) use ($university) {
            $query->where('university_id', $university->id);
        })->where('name', 'like', '%' . $valdata['name'] . '%')->where('is_deleted',0)->with('year')->get();
        return response()->json(['subjects' => $results]);
    }

    public function getstudentsubjects(Request $request)
    {
        $valdata = $request->validate([
            'student_id' => 'required|integer',
        ]);
        $student = Student::findOrFail($valdata['student_id']);
        $universityid = $student->year->university->id;
        $seasons = Season::where('university_id','=',$universityid)->get();
        return response()->json(['year' => $student->year->load('subjects'),'seasons'=>$seasons]);
    }

    public function uniyears(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);

        return response()->json(['years' => $university->years]);
    }

    public function getteachers(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        $universityId = $university->id;
        $results = Teacher::whereHas('university', function ($query) use ($university) {
            $query->where('university_id', $university->id);
        })->where('statue','!=', 'deleted')->where('statue','!=', 'pending')->withCount('courses','faivorit')->with('university')->get();

        return response()->json(['teachers' => $results]);
    }

    public function getsubjects(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        return response()->json(['year' => $university->years->load('subjects'),'seasons'=>$university->seasons]);
    }

    public function addstudentsubject(Request $request)
    {
        $valdata = $request->validate([
            'student_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ]);
        $student = Student::findOrFail($valdata['student_id']);
        $student->subjects()->syncWithoutDetaching($valdata['subject_id']);
        return response()->json(['year' => $student->subjects,'message' => 'تم إضافة المادة الى حساب الطالب']);
    }
    public function deletestudentsubject(Request $request)
    {
        $valdata = $request->validate([
            'student_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ]);
        $student = Student::find($valdata['student_id']); // $studentId is the student_id
        $student->subjects()->detach($valdata['subject_id']); // $subjectId is the subject_id
        return response()->json(['message' => 'تم حذف المادة من حساب الطالب']);
    }

    public function getcoursefromsubject(Request $request)
    {

        $valdata = $request->validate([
            'subject_id' => 'required|integer',
        ]);
        $courses = Course::where('is_deleted', 0)->where('is_pending', 0)
        ->whereHas('teacher', function ($query) {
            $query->where('statue', 'active')->orWhere('statue', 'banned');
        })
        ->whereHas('subject', function ($query) use ($valdata) {
            $query->where('is_deleted', 0)->where('id','=',$valdata['subject_id'])
                ->whereHas('year', function ($query)  {
                    $query->where('is_deleted', 0);
                });
        })->withAvg('rates', 'stars')->with(['subject.year', 'teacher'])
        ->get();

        return response()->json(['courses' => $courses]);
    }

    public function getlecturewithvideos(Request $request)
    {

        $valdata = $request->validate([
            'lecture_id' => 'required|integer',
        ]);
        $lecture = Lecture::findOrFail($valdata['lecture_id']);

        return response()->json(['lecture' => $lecture->load('videos')]);
    }
    public function getlecturefiles(Request $request)
    {

        $valdata = $request->validate([
            'lecture_id' => 'required|integer',
        ]);
        $lecture = Lecture::findOrFail($valdata['lecture_id']);

        return response()->json(['files' => $lecture->files]);
    }
    public function getvideodetails(Request $request)
    {

        $valdata = $request->validate([
            'video_id' => 'required|integer',
        ]);
        $video = Video::findOrFail($valdata['video_id']);

        return response()->json(['video' => $video->load('timing')]);
    }
    public function uninote(Request $request)
    {
        $valdata = $request->validate([
            'university_id' => 'required|integer',
            'student_id' => 'required|integer',
        ]);
        $university = University::findOrFail($valdata['university_id']);
        $student = Student::findOrFail($valdata['student_id']);
        $teachernoteification = TeacherNotification::where('university_id', '=', $university->id)->limit(30)->get();
        return response()->json(['noteifications' => $teachernoteification,'adminnotifies'=>$student->adminnotifi()->limit(10)->get()]);
    }
    public function subscriptions(Request $request)
    {
        $valdata = $request->validate([
            'student_id' => 'required|integer',
        ]);
        $student = Student::findOrFail($valdata['student_id']);

        $activeCourses = $student->courses()
        ->where('enddate', '>', Carbon::today())->where('is_deleted','0')
        ->get();
        $expiredCourses = $student->courses()
        ->where('enddate', '<', Carbon::today())->where('is_deleted','0')
        ->get();
        return response()->json(['activeCourses' => $activeCourses->load('teacher','rates'),'expiredCourses'=>$expiredCourses->load('teacher','rates')]);
    }

    public function coursedetails(Request $request)
    {

        $valdata = $request->validate([
            'course_id' => 'required|integer',
            'student_id' => 'required|integer',
        ]);
        $student_id = $valdata['student_id'];
        $course_id = $valdata['course_id'];
        $course = Course::with([
            'teacher.faivorit' => function ($query) use ($student_id) {
                // Only favorites by this student
                $query->where('student_id', $student_id);
            },
            'rates' => function ($query) use ($student_id) {
                // Optionally eager load only this student's rate or all rates
                $query->where('student_id', $student_id);
            },
        ])->findOrFail($course_id);

        // Get the student's rate for this course (if any)
        $studentRate = $course->rates->first();

        // Get the student's favorite for the teacher (if any)
        $studentFavorite = $course->teacher->faivorit->first();

        return response()->json([
            'course' => $course,
            'student_rate' => $studentRate,
            'student_favorite_teacher' => $studentFavorite,
        ]);
    }
    public function studentprofile(Request $request)
    {

        $valdata = $request->validate([
            'student_id' => 'required|integer',
        ]);
        $student = Student::findOrFail($valdata['student_id']);

        return response()->json(['student' => $student->load('year.university')]);
    }

    public function updatestudentuniversity(Request $request)
    {

        $valdata = $request->validate([
            'student_id' => 'required|integer',
            'year_id' => 'required|integer',
        ]);
        $year = Year::findOrFail($valdata['year_id']);
        $student = Student::findOrFail($valdata['student_id']);
        $subjects = $year->subjectsync();
        $student->subjects()->delete();
        $student->subjects()->syncWithoutDetaching($subjects->pluck('subjects.id'));
        return response()->json(['student' => $student->load('year.university')]);
    }
    public function updatestudentpass(Request $request)
    {

        $valdata = $request->validate([
            'student_id' => 'required|integer',
            'password' => 'required|min:8',
        ]);
        $student = Student::findOrFail($valdata['student_id']);
        $user = $student->user()->update([
            'password'=>$valdata['password']
        ]);

        return response()->json(['message' => 'تمت عملية تغيير كلمة المرور بنجاح']);
    }

    public function updatestudentprofile(Request $request)
    {

        $valdata = $request->validate([
            'student_id' => 'required|integer',
            'name'=> 'required|string',
            'year_id'=> 'required|integer',
            'is_banned'=> 'required|boolean',
        ]);
        $student = Student::findOrFail($valdata['student_id']);
        $student->update([
            'name'=> $valdata['name'],
            'year_id'=> $valdata['year_id'],
            'is_banned'=> $valdata['is_banned'],
        ]);
        return response()->json(['student' => $student,'message'=>'تم تغيير معلومات الطالب']);
    }
    public function salespoints()
    {
        $salespoits = SalesPoint::all();
        return response()->json(['salespoints' => $salespoits]);
    }
    public function redeemCode(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'code' => 'required|string|size:6',
        ]);

        $code = Code::where('code', $request->input('code'))->where('statue', 0)->first();

        if (!$code) {
            return response()->json(['message' => 'هذا الكود غير صالح او مستخدم'], 400);
        }
        DB::beginTransaction();
        // $course= Course::findOrFail(1);
        $course = $code->codeGroup->course;
        $student = Student::findOrFail($request->input("student_id"));

        // Prevent duplicate subscriptions
        $alreadySubscribed = StudentCourse::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadySubscribed) {
            return response()->json(['message' => 'مشترك في هذا الكورس مسبقاً'], 400);
        }

        // Mark code as used
        $code->update(['statue' => 1]);

        // Get course price (assuming 'price' field exists on Course)
        $price = $course->price;

        // Create course subscription
        StudentCourse::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'subscription_price' => $price,
            'persentage' => 0.15,
            // add other fields as needed
        ]);
        DB::commit();
        return response()->json(['message' => 'تم تفعيل الكورس']);
    }
    public function contactinfo()
    {
        $contact = Contactinfo::findOrFail(1);
        return response()->json(['info' => $contact]);
    }
    public function addteacherfaivorit(Request $request)
    {
        $request->validate([
        'student_id' => 'required|exists:students,id',
        'teacher_id' => 'required|exists:teachers,id',
    ]);

    $student = Student::findOrFail($request->input('student_id'));

    // Attach teacher if not already favorited
    $student->favorits()->syncWithoutDetaching($request->input('teacher_id'));

    return response()->json(['message' => 'تمت الإضافة الى المفضلة']);
    }
    public function deleteteacherfaivorit(Request $request)
    {
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'teacher_id' => 'required|exists:teachers,id',
    ]);

    $student = Student::findOrFail($request->input('student_id'));

    $student->favorits()->detach($request->input('teacher_id'));

    return response()->json(['message' => 'تم الإزالة من المفضلة']);
    }
    public function addcourserate(Request $request)
    {
        $valdata = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
        ]);
        $student = Student::findOrFail($valdata['student_id']);
        // $student->rates()->($valdata['subject_id']);
    }
    public function teachercourses(Request $request)
    {
        $valdata = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
        ]);
        $teacher = Teacher::findOrFail($valdata['teacher_id']);
        $courses  = Course::where('is_deleted', 0)->where('is_pending', 0)
        ->whereHas('teacher', function ($query) use ($teacher) {
            $query->where('statue','=', 'active')->orWhere('statue','=', 'banned')->where('teacher_id','=',$teacher->id);
        })
        ->whereHas('subject', function ($query) use ($teacher) {
            $query->where('is_deleted', 0)
                ->whereHas('year', function ($query) use ($teacher) {
                    $query->where('is_deleted', 0)->where('university_id','=',$teacher->university_id);
                });
        })->withAvg('rates', 'stars')->with(['subject.year', 'teacher'])
        ->get();
        return response()->json(['courses' => $courses]);
    }
    public function yearcourses(Request $request)
    {
        $valdata = $request->validate([
            'year_id' => 'required|exists:years,id',
        ]);
        $courses = Course::where('is_deleted', 0)->where('is_pending', 0)
        ->whereHas('teacher', function ($query) {
            $query->where('statue','=', 'active')->orWhere('statue','=', 'banned');
        })
        ->whereHas('subject', function ($query) use ($valdata) {
            $query->where('is_deleted', 0)
                ->whereHas('year', function ($query) use ($valdata) {
                    $query->where('is_deleted', 0)->where('year_id','=',$valdata['year_id']);
                });
        })->withAvg('rates', 'stars')->with(['subject.year', 'teacher'])
        ->get();
        return response()->json(['courses' => $courses]);
    }

    public function addRate(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'course_id' => 'required|exists:courses,id',
        'stars' => 'required|decimal:0,5', // assuming 1-5 rating scale
    ]);

    // Check if student already rated this course
    $existingRate = CourseRate::where('student_id', $request->input('student_id'))
        ->where('course_id', $request->input('course_id'))
        ->first();

    if ($existingRate) {
        // Optionally update existing rate
        $existingRate->update(['stars' => $request->input('stars')]);
        return response()->json(['message' => 'تم تعديل التقييم']);
    }

    // Create new rate
    CourseRate::create([
        'student_id' => $request->input('student_id'),
        'course_id' => $request->input('course_id'),
        'stars' => $request->input('stars'),
    ]);

    return response()->json(['message' => 'تم تقييم الكورس']);
}




}
