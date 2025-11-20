<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\SalesPoint;
use Illuminate\Http\Request;

class SalesPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['salespoint'=>SalesPoint::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'phone'=> 'required|string',
            'address'=> 'required|string',
            'university_id'=> 'required|integer'
        ]);
        SalesPoint::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'university_id' => $request->input('university_id'),
        ]);
        return response()->json(['statue'=>200,'message'=>'تمت إضافة نقطة مبيع بنجاح']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $salepoint=SalesPoint::findOrFail($id);
        return response()->json(['salepoint'=>$salepoint]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $val = $request->validate([
            'name'=> 'required|string',
            'phone'=> 'required|string',
            'address'=> 'required|string',
            'university_id'=> 'required|integer'
        ]);
        $salepoint=SalesPoint::findOrFail($id);
        $salepoint->update($val);
        return response()->json(['salepoint'=>$salepoint]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salepoint = SalesPoint::findOrFail($id);
        $salepoint->delete();
        return response()->json(['statue'=>200,'message'=>'تمت حذف الكورس بنجاح']);
    }
}
