<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Teacher;
use App\Models\University;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherLayoutController extends Controller
{
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
        $data->load('user','year.university');
        return response()->json(['teacher'=> $data, 'token' => $token,'user'=>$user]);
    }
    public function signupteacher()
    {
        $universities = University::where('is_deleted', '0')
            ->with('years') // Eager load the relationship
            ->get(); // Execute the query
        // The 'universities' collection now includes the 'notdeletedyears' relationship
        return response()->json([
            'universities' => $universities
        ]);
    }
    public function signupnewteacher(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'phone'=> 'required|string|unique:users',
            'year_id'=> 'required|integer',
            'is_banned'=> 'boolean|nullable',
            'password'=> 'required|string',
        ]);
        DB::beginTransaction();
        $student = Teacher::create([
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
         return response()->json([ 'free_courses' => 'sss']);
    }
}
