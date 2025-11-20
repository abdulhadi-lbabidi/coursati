<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['year'=>Year::all()->load('university')]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'number'=> 'required|string',
            'is_deleted'=> 'boolean|nullable',
            'university_id'=> 'required|integer'
        ]);
        Year::create([
            'name' => $request->input('name'),
            'number' => $request->input('number'),
            'is_deleted' => $request->input('is_deleted'),
            'university_id' => $request->input('university_id'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت إضافة سنة بنجاح']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $year=Year::findOrFail($id);
        return response()->json(['year'=>$year]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $val = $request->validate([
            'name'=> 'required|string',
            'number'=> 'required|string',
            'is_deleted'=> 'boolean|nullable',
            'university_id'=> 'required|integer'
        ]);
        $year=Year::findOrFail($id);
        $year->update($val);
        return response()->json(['year'=>$year]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $year = Year::findOrFail($id);
        $year->delete();
        return response()->json(['statue'=>200,'message'=>'تمت حذف السنة بنجاح']);
    }
}
