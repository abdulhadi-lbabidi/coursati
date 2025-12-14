<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Contactinfo;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\LectureFile;
use App\Models\SalesPoint;
use App\Models\StudentCourse;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherNotification;
use App\Models\University;
use App\Models\Video;
use App\Models\Withdraw;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherLayoutController extends Controller
{
    // 1
        public function loginteacher(LoginRequest $request){
        $credentils = $request->validated();
        if (!Auth::attempt($credentils)) {
            return response()->json([
                'message' => 'Provided phone or password is incorrect'
            ], 401);
        }
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $token = $user->createToken('api-token')->plainTextToken;

        $data =Teacher::findOrFail($user->userable_id);
        // return response(compact('user','token'));
        $data->load('user','university');
        return response()->json(['teacher'=> $data, 'token' => $token,'user'=>$user]);
    }
    // 2
    public function signupteacher()
    {
        $universities = University::where('is_deleted', '0')
            ->get(); // Execute the query
        // The 'universities' collection now includes the 'notdeletedyears' relationship
        return response()->json([
            'universities' => $universities
        ]);
    }
    // 3
    public function signupnewteacher(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'telegram_url'=> 'nullable|string',
            'description'=>'nullable|string',
            // 'image_url'=>'nullable|string',
            'gender'=>'required|string',
            'university_id'=> 'required|exists:universities,id',
            'phone'=> 'required|string|unique:users',
            'password'=> 'required|string',
        ]);
        DB::beginTransaction();

            $teacher = Teacher::create([
            'name' => $request->input('name'),
            'telegram_url'=> $request->input('telegram_url'),
            'description'=>$request->input('description'),
            'gender'=>$request->input('gender'),
            'university_id'=> $request->input('university_id'),
        ]);
        $user = $teacher->user()->create([
            'password' =>$request->input('password') ,
            'phone' =>$request->input('phone') ,
        ]);
        $token = $user->createToken('api-token')->plainTextToken;


        DB::commit();
        return response()->json(['statue'=>200,'message'=>'تمت إضافة الإستاذ بنجاح','teacher'=>$teacher->load('university'),'token'=>$token,'user'=>$teacher->user]);

    }
    // 5
    public function homepage(Request $request)
    {
        $valdata = $request->validate([
            'teacher_id'=> 'required|exists:teachers,id',
            'university_id'=> 'required|exists:universities,id',
        ]);

    //    // 1. Number of students subscribed to teacher's courses
   $teacher = Teacher::where('id', $valdata['teacher_id'])
    ->where('university_id', $valdata['university_id'])
    ->first();

if (!$teacher) {
    // Handle the case where teacher is not found
    return response()->json(['error' => 'Teacher not found'], 404);
}

$courseIds = $teacher->courses()->pluck('id');

    $studentSubscriptionsCount = StudentCourse::whereIn('course_id', $courseIds)
        ->distinct('student_id')
        ->count('student_id');

    // 2. Number of favorites to the teacher
    $teacher = Teacher::find($valdata['teacher_id']);
    $favoritesCount = $teacher->faivorit()->count();

    // 3. Number of courses for the teacher where is_deleted = 0 and is_pending = 0
    $activeCoursesCount = $teacher->courses()->where('is_deleted', 0)->where('is_pending', 0)->count();

    // 4. Sum all courses rates then * 100 / 5
    $totalRates = $teacher->courses()->with('rates')->get()->pluck('rates')->flatten()->sum('stars');
    $sumRatesPercent = $totalRates * 100 / 5;

    // 5. Courses for the teacher where is_deleted = 0 and is_pending = 1
    $pendingCourses = $teacher->courses()->where('is_deleted', 0)->where('is_pending', 1)->get();

    // 6. Courses for the teacher, latest created_at, limit 5
    $latestCourses = $teacher->courses()->orderByDesc('created_at')->limit(5)->get();

    // 7. Sum withdraws + sum subscriptions for this month to teacher
    $now = Carbon::now();
    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();

    $withdrawsSum = Withdraw::where('teacher_id', $valdata['teacher_id'])
        ->whereBetween('withdraw_date', [$startOfMonth, $endOfMonth])
        ->sum('amount');

    $subscriptionsSum = StudentCourse::whereIn('course_id', $courseIds)
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum('subscription_price');

    $totalIncomeThisMonth = $withdrawsSum + $subscriptionsSum;

    // Return as JSON or pass to view
    return response()->json([
        'student_subscriptions_count' => $studentSubscriptionsCount,
        'favorites_count' => $favoritesCount,
        'active_courses_count' => $activeCoursesCount,
        'sum_rates_percent' => $sumRatesPercent,
        'pending_courses' => $pendingCourses,
        'latest_courses' => $latestCourses,
        'total_income_this_month' => $totalIncomeThisMonth,
    ]);
    }
    // 6
    public function teachercourses(Request $request)
    {
        $valdata = $request->validate([
            'teacher_id'=> 'required|exists:teachers,id',
            'university_id'=> 'required|exists:universities,id',
        ]);

        $courses  = Course::where('is_deleted', 0)
        ->whereHas('teacher', function ($query) use ($request, $valdata) {
            $query->where('id','=', $valdata['teacher_id']);
        })
        ->whereHas('subject', function ($query) use ($valdata) {
            $query->where('is_deleted', 0)
                ->whereHas('year', function ($query) use ($valdata) {
                    $query->where('is_deleted', 0)->where('university_id','=',$valdata['university_id']);
                })
                ->whereHas('season', function ($query) use ($valdata) {
                    $query->where('is_deleted', 0)->where('university_id','=',$valdata['university_id']);
                });
        })->withAvg('rates', 'stars')->with(['subject.year', 'teacher'])
        ->get();

        return response()->json([ 'courses' => $courses]);
    }
    // 7
    public function teachersubjects(Request $request)
    {
        $valdata = $request->validate([
            'year_id'=> 'required|exists:years,id',
            'teacher_id'=> 'required|exists:teachers,id',
        ]);
        $year = Year::findOrFail($valdata['year_id']);

        if (!$year) {
            // handle year not found
            $subjectsTaught = collect();
        } else {
            $universityId = $year->university_id;
            $university = $year->university;
            $subjectsTaught = Subject::where('year_id', $valdata['year_id'])->where('is_deleted', 0)
                ->whereHas('teachers', function($q) use ($universityId) {
                    $q->where('university_id', $universityId);
                })
                ->whereHas('year', function($q) use ($universityId) {
                    $q->where('university_id', $universityId)->where('is_deleted',0);
                })
                ->whereHas('season', function($q) use ($universityId) {
                    $q->where('university_id', $universityId)->where('is_deleted',0);
                })
                ->with('year','season')
                ->get();
        }

        $subjectsNoTeacher = Subject::where('year_id', $valdata['year_id'])->where('is_deleted', 0)
            ->whereHas('year', function($q) use ($universityId) {
                $q->where('university_id', $universityId)->where('is_deleted',0);
            })
            ->whereHas('season', function($q) use ($universityId) {
                $q->where('university_id', $universityId)->where('is_deleted',0);
            })
            ->whereDoesntHave('teachers')
            ->with('year','season')
            ->get();
        $teacherId = 5; // example teacher id

        $subjectsNotTaken = Subject::where('year_id', $valdata['year_id'])->where('is_deleted', 0)
                ->whereHas('year', function($q) use ($universityId) {
                    $q->where('university_id', $universityId)->where('is_deleted',0);
                })
                ->whereHas('season', function($q) use ($universityId) {
                    $q->where('university_id', $universityId)->where('is_deleted',0);
                })
            ->whereDoesntHave('teachers', function($q) use ($valdata) {
                $q->where('teachers.id', $valdata['teacher_id']);
            })
            ->with('year','season')
            ->get();

        return response()->json([ 'subjectsTaught' => $subjectsTaught,'subjectsNoTeacher'=>$subjectsNoTeacher,'subjectsNotTaken'=>$subjectsNotTaken , 'seasons'=>$university->seasons]);
    }

    public function createCourseIfAllowed(Request $request)
    {

        $teacherId = $request->input('teacher_id');
        $subjectId = $request->input('subject_id');
        $courseData = $request->only([
            'name', 'desc', 'courese_type', 'course_tag_name', 'free_course_description',
            'free_course_image', 'lectures_number', 'total_videos_duration', 'price',
            'enddate', 'telegram_url'
        ]);

        // Validate required inputs (optional but recommended)
        if (!$teacherId || !$subjectId) {
            return response()->json(['message' => 'teacher_id and subject_id are required'], 422);
        }

        // Find teacher
        $teacher = Teacher::find($teacherId);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        // Check if teacher has any active courses for this subject
        $hasActiveCourseForSubject = $teacher->courses()
            ->where('subject_id', $subjectId)
            ->where('is_deleted', 0)
            ->where('is_pending', 0)
            ->exists();

        if ($hasActiveCourseForSubject) {
            return response()->json([
                'message' => 'Teacher already has an active course for this subject. Cannot create a new course.'
            ]);
        }

        // Create the course for the teacher and subject
        $course = new Course($courseData);
        $course->teacher_id = $teacherId;
        $course->subject_id = $subjectId;
        $course->is_deleted = 0;
        $course->is_pending = 0; // or 1 if you want to mark as pending initially
        $course->save();

        return response()->json([
            'message' => 'تم إنشاء الكورس بنجاح',
            'course' => $course
        ]);

    }

    public function getCoursesByExpiry(Request $request)
    {
        $teacher_id = $request->input('teacher_id');
        $university_id = $request->input('university_id');
        $today = Carbon::today();

        // Eager load relationships for efficiency
        $courses = Course::with([
            'subject.year',
            'subject.season',
            'rates',
            'lectures.videos',
            'teacher'  // optional, if you want to verify teacher's university
        ])
        ->where('teacher_id', $teacher_id)
        ->whereHas('teacher', function($query) use ($university_id) {
            $query->where('university_id', $university_id);
        })
        ->whereHas('subject', function ($query)  {
            $query->where('is_deleted', 0)
                ->whereHas('year', function ($query)  {
                    $query->where('is_deleted', 0);
                })
                ->whereHas('season', function ($query)  {
                    $query->where('is_deleted', 0);
                });
        })
        ->get();

        $expired = [];
        $active = [];

        foreach ($courses as $course) {
            // Calculate average rate (or 0 if no rates)
            $avgRate = $course->rates->count() > 0
                ? round($course->rates->avg('stars'), 2)
                : 0;

            // Get season name (if exists)
            $seasonName = optional(optional($course->subject)->season)->name;

            // Count all videos in all lectures
            $videosCount = $course->lectures->reduce(function($carry, $lecture) {
                return $carry + $lecture->videos->count();
            }, 0);

            // Full course details as array
            $courseData = $course->toArray();

            // Add computed fields
            $courseData['avg_rate'] = $avgRate;
            $courseData['videos_count'] = $videosCount;

            if (Carbon::parse($course->enddate)->lt($today)) {
                $expired[] = $courseData;
            } else {
                $active[] = $courseData;
            }
        }

        return response()->json([
            'expired_courses' => $expired,
            'active_courses' => $active,
        ]);
    }

    public function updateCourse(Request $request)
    {
        $courseId = $request->input('course_id');

        // Find the course
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['message' => 'لم يتم إيجاد الكورس'], 404);
        }

        // Update course fields (adjust fields as needed)
        $fields = [
            'name', 'desc', 'courese_type', 'course_tag_name', 'free_course_description',
            'free_course_image', 'lectures_number', 'total_videos_duration', 'price',
            'enddate', 'telegram_url', 'subject_id', 'is_deleted', 'is_pending'
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $course->$field = $request->input($field);
            }
        }

        $course->save();

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => $course->load('subject.year', 'rates',
                'lectures.videos',
                'teacher'),
        ]);


    }
    public function createLecture(Request $request)
    {
        // Validate required fields according to your migration and model
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'number' => 'required|integer',
            'lecture_tag_name' => 'required|string|max:255',
            'desc' => 'required|string',
        ]);

        // Create lecture using fillable fields
        $lecture = Lecture::create([
            'course_id' => $request->input('course_id'),
            'name' => $request->input('name'),
            'number' => $request->input('number'),
            'lecture_tag_name' => $request->input('lecture_tag_name'),
            'desc' => $request->input('desc'),
        ]);

        // Retrieve all lectures for this course ordered by number
        $lectures = Lecture::where('course_id', $request->input('course_id'))
            ->orderBy('number')
            ->get();

        return response()->json([
            'message' => 'Lecture created successfully',
            'lectures' => $lectures,
        ]);
    }

    public function destroyLecture(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
        ]);

        $lecture = Lecture::find($request->input('lecture_id'));

        if (!$lecture) {
            return response()->json(['message' => 'Lecture not found'], 404);
        }

        $lecture->delete();

        return response()->json([
            'message' => 'تم حذف المحاضرة بنجاح',
        ]);
    }

    public function updateLecture(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
            'name' => 'sometimes|required|string|max:255',
            'number' => 'sometimes|required|integer',
            'lecture_tag_name' => 'sometimes|required|string|max:255',
            'desc' => 'sometimes|required|string',
        ]);

        $lecture = Lecture::findOrFail($request->input('lecture_id'));

        // Update only provided fields
        $fields = ['name', 'number', 'lecture_tag_name', 'desc'];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $lecture->$field = $request->input($field);
            }
        }

        $lecture->save();

        return response()->json([
            'message' => 'Lecture updated successfully',
            'lecture' => $lecture,
        ]);
    }

    public function getLectureDetails(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
        ]);

        $lecture = Lecture::find($request->input('lecture_id'));

        return response()->json([
            'lecture' => $lecture,
        ]);
    }

    public function getLectureFiles(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
        ]);

        $lecture = Lecture::find($request->input('lecture_id'));

        $files = $lecture->files()->orderBy('number')->get(); // Eager loaded files

        return response()->json([
            'files' => $files,
        ]);
    }

    public function getLectureVideos(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
        ]);

        $lecture = Lecture::find($request->input('lecture_id'));

        $videos = $lecture->videos()->orderBy('number')->get(); // Eager loaded videos ordered by number

        return response()->json([
            'videos' => $videos,
        ]);
    }
public function addVideo(Request $request)
{
    $request->validate([
        'lecture_id' => 'required|exists:lectures,id',
        'name' => 'required|string|max:255',
        'desc' => 'nullable|string',
        'video_url' => 'required|string',
        'views' => 'nullable|integer',
        'is_free' => 'required|boolean',
        'video_tag_name' => 'nullable|string|max:255',
        'number' => 'required|integer',
    ]);

    $video = Video::create([
        'lecture_id' => $request->input('lecture_id'),
        'name' => $request->input('name'),
        'desc' => $request->input('desc'),
        'video_url' => $request->input('video_url'),
        'views' => $request->input('views') ?? 0,
        'is_free' => $request->input('is_free'),
        'video_tag_name' => $request->input('video_tag_name'),
        'number' => $request->input('number'),
    ]);

    return response()->json([
        'message' => 'Video added successfully',
        'video' => $video,
    ]);
}
    public function deleteVideo(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
        ]);

        $video = Video::find($request->input('video_id'));
        $video->delete();

        return response()->json([
            'message' => 'Video deleted successfully',
        ]);
    }

    public function addLectureFile(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
            'name' => 'required|string|max:255',
            'size' => 'required|decimal:0,1000',
            'file_url' => 'required|string',
            'number' => 'required|integer',
        ]);

        $lectureFile = LectureFile::create([
            'lecture_id' => $request->input('lecture_id'),
            'name' => $request->input('name'),
            'size' => $request->input('size'),
            'file_url' => $request->input('file_url'),
            'number' => $request->input('number'),
        ]);

        return response()->json([
            'message' => 'Lecture file added successfully',
            'lecture_file' => $lectureFile,
        ]);
    }


    public function deleteLectureFile(Request $request)
    {
        $request->validate([
            'lecturefile_id' => 'required|exists:lecture_files,id',
        ]);

        $lectureFile = LectureFile::find($request->input('lecturefile_id'));
        $lectureFile->delete();

        return response()->json([
            'message' => 'Lecture file deleted successfully',
        ]);
    }

    public function getVideoWithTiming(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
        ]);

        // If timing is a relationship, eager load it; otherwise adjust accordingly
        $video = Video::with('timing')->find($request->input('video_id'));

        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }
        $video->increment('views');

        return response()->json([
            'video' => $video,
            'timing' => $video->timing,  // or $video->timing if a relationship
        ]);
    }

    public function teacherInnerByCourse (Request $request){
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);


        $course = Course::find($request->input('course_id'));

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        // Sum of subscription_price
        $sumSubscriptionPrice = $course->subscriptions->sum('subscription_price');

        // Average rate (if 'rate' column exists in student_courses)
        $averageRate = $course->subscriptions->avg('rate');
        $results = DB::table('student_courses')
        ->select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw("SUM(subscription_price) as total_subscription_price"),
            DB::raw("COUNT(*) as subscription_count"),
            DB::raw("AVG(subscription_price) as average_price")
        )
        ->where('course_id', $request->input('course_id'))
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
        ->orderBy('month', 'asc')
        ->get();


        return response()->json([
            'course' => $course,
            'sum_subscription_price' => $sumSubscriptionPrice,
            'average_rate' => $averageRate,
            'bymonth' => $results,
        ]);
        // return response()->json(['bymonthes'=>$results]);
    }
    public function teacherWithdraws (Request $request){
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $withdrawSum = DB::table('withdraws')
        ->where('teacher_id', $request->input('teacher_id'))
        ->sum('amount');

        $innerSum = DB::table('student_courses')
        ->join('courses', 'student_courses.course_id', '=', 'courses.id')
        ->where('courses.teacher_id', $request->input('teacher_id'))
        ->sum('student_courses.subscription_price');

        $innerMinusOuter = $innerSum - $withdrawSum;

        $courseSums = DB::table('student_courses')
        ->join('courses', 'student_courses.course_id', '=', 'courses.id')
        ->select('courses.id as course_id', 'courses.name as course_name', DB::raw('SUM(student_courses.subscription_price) as total_subscription'))
        ->where('courses.teacher_id', $request->input('teacher_id'))
        ->groupBy('courses.id', 'courses.name')
        ->get();

        return response()->json(['coursesinners'=>$courseSums,'innerSum'=>$innerSum,'withdrawSum'=>$withdrawSum,'total'=>$innerMinusOuter]);
    }
    public function teacherInnerByMonth (Request $request){
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
        ]);
        $resultbymonth = DB::table('student_courses')
        ->join('courses', 'student_courses.course_id', '=', 'courses.id')
        ->select(
            DB::raw("DATE_FORMAT(student_courses.created_at, '%Y-%m') as month"),
            DB::raw("SUM(student_courses.subscription_price) as total_subscription_price")
        )
        ->where('courses.teacher_id', $request->input('teacher_id'))
        ->groupBy(DB::raw("DATE_FORMAT(student_courses.created_at, '%Y-%m')"))
        ->orderBy('month', 'asc')
        ->get();

        $resultbycourse = DB::table('student_courses')
        ->join('courses', 'student_courses.course_id', '=', 'courses.id')
        ->select(
            'courses.name as course_name',
            DB::raw("DATE_FORMAT(student_courses.created_at, '%Y-%m') as month"),
            DB::raw("SUM(student_courses.subscription_price) as total_subscription_price")
        )
        ->where('courses.teacher_id', $request->input('teacher_id'))
        ->groupBy('courses.name', DB::raw("DATE_FORMAT(student_courses.created_at, '%Y-%m')"))
        ->orderBy('courses.name')
        ->orderBy('month')
        ->get();
        return response()->json(['bymonthes'=>$resultbymonth,'bycoursemonth'=>$resultbycourse]);
    }

    public function addTeacherNotification(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'university_id' => 'required|exists:universities,id',
        ]);

        $notification = TeacherNotification::create([
            'teacher_id' => $request->input('teacher_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'is_allowed' => 0,
            'university_id' => $request->input('university_id'),
        ]);

        return response()->json([
            'message' => 'Teacher notification added successfully',
            'notification' => $notification,
        ]);
    }

    public function getTeacherNotifications(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $notifications = TeacherNotification::where('teacher_id', $request->input('teacher_id'))
            ->orderBy('is_allowed', 'desc')  // optional: order by newest first
            ->get();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    public function getTeacherWithUserAndUniversity(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $teacher = Teacher::with(['user', 'university'])->find($request->input('teacher_id'));

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        return response()->json([
            'teacher' => $teacher,
        ]);
    }


    public function editTeacherprofile(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'name' => 'string|max:255',
            'telegram_url' => 'string|max:255',
            'description' => 'string',
            'gender' => 'in:ذكر,أنثى',  // adjust options as needed
            'image_url' => 'string|max:255',
            'persentage' => 'numeric|min:0|max:100',
            'university_id' => 'sometimes|exists:universities,id',
        ]);

        $teacher = Teacher::find($request->input('teacher_id'));

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        $teacher->fill($request->only([
            'name',
            'telegram_url',
            'description',
            'gender',
            'image_url',
            'persentage',
            'university_id',
        ]));

        $teacher->save();

        return response()->json([
            'message' => 'Teacher updated successfully',
            'teacher' => $teacher,
        ]);
    }







    // 27
    public function salespoints()
    {
        $salespoints = SalesPoint::all();
        return response()->json(['salespoints' => $salespoints]);
    }

    // 28
    public function contactinfo()
    {
        $contact = Contactinfo::findOrFail(1);
        return response()->json(['info' => $contact]);
    }


    // 30
    public function getCourseDetails(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::with(['teacher', 'subject.year', 'subject.season'])
            ->find($request->input('course_id'));

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        $courserate = $course->rates()->sum('stars');

        return response()->json([
            'course' => $course,
            'teacher' => $course->teacher,
            'rate' => $courserate*100/5, // or $course->rate() if it's a relationship
            'subject' => $course->subject,
            'year' => $course->subject->year, // or $course->year->name if it's a relationship
        ]);
    }

    // 31
    public function getCourseLectures(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $lectures = Lecture::where('course_id', $request->input('course_id'))
            ->orderBy('number')
            ->get();

        return response()->json([
            'lectures' => $lectures,
        ]);
    }


}
