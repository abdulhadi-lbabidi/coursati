<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['university'=>University::all()]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'name'=> 'required|string',
        ]);
        $newun= University::create([
            'name' => $request->input('name')
        ]);
        return response()->json(['university'=>$newun]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $un=University::findOrFail($id);
        return response()->json(['university'=>$un]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $us = $request->validate([
            'name'=> 'required|string',
        ]);
        $un=University::findOrFail($id);
        $un->update($us);
        return response()->json(['university'=>$un]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $un = University::findOrFail($id);
        $un->delete();
        return response()->json(['statue'=>200,'message'=>'تمت حذف الجامعة بنجاح']);
    }
}
