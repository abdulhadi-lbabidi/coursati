<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Year;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['subject'=>Year::all()->load('subjects','university')]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'season'=> 'required|integer',
            'is_deleted'=> 'boolean|nullable',
            'year_id'=> 'required|integer'
        ]);
        Subject::create([
            'name' => $request->input('name'),
            'season' => $request->input('season'),
            'is_deleted' => $request->input('is_deleted'),
            'year_id' => $request->input('year_id'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت إضافة مادة بنجاح']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject=Subject::findOrFail($id);
        return response()->json(['subject'=>$subject]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $val = $request->validate([
            'name'=> 'required|string',
            'season'=> 'required|integer',
            'is_deleted'=> 'boolean|nullable',
            'year_id'=> 'required|integer'
        ]);
        $subject=Subject::findOrFail($id);
        $subject->update($val);
        return response()->json(['subject'=>$subject]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return response()->json(['statue'=>200,'message'=>'تمت حذف المادة بنجاح']);
    }
}
