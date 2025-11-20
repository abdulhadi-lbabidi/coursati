<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
        public function loginadmin(LoginRequest $request){
        $credentils = $request->validated();
        if (!Auth::attempt($credentils)) {
            return response([
                'message' => 'provided phone or password is incoorect'
            ],401);
        }
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $token = $user->createToken('main')->plainTextToken;
        $data =Admin::findOrFail($user->userable_id);
        // return response(compact('user','token'));
        $data->load('user');
        return response()->json(['admin'=> $data,'token'=>$token]);
    }
        public function loginstudent(LoginRequest $request){
        $credentils = $request->validated();
        if (!Auth::attempt($credentils)) {
            return response()->json([
                'message' => 'Provided phone or password is incorrect'
            ], 401);
        }
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $token = $user->createToken('main')->plainTextToken;

        $data =Student::findOrFail($user->userable_id);
        // return response(compact('user','token'));
        $data->load('user','year.university');
        return response()->json(['student'=> $data, 'token' => $token]);
    }


    public function loginteacher(LoginRequest $request){
        $credentils = $request->validated();
        if (!Auth::attempt($credentils)) {
            return response()->json([
                'message' => 'Provided phone or password is incorrect'
            ], 401);
        }
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $token = $user->createToken('main')->plainTextToken;
        $data = Teacher::findOrFail($user->userable_id);
        // return response(compact('user','token'));
        $data->load('user');

        return response()->json(['teacher'=> $data,'token'=>$token]);
    }
    public function logout(Request $request){
        /** @var \App\Models\User $user */

        $request->user()->currentAccessToken()->delete();
        return response('',204);
    }
}
