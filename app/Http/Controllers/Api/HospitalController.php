<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;

class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hospitals = Hospital::with('district')->latest()->get();

        return response()->json($hospitals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $hospital = Hospital::create([
            'name' => $request->name,
            'district_id' => $request->district_id,
            'address' => $request->address,
            'phone' => $request->phone
        ]);

        return response()->json([
            'message' => 'Hospital created successfully',
            'data' => $hospital
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hospital = Hospital::with('district')->findOrFail($id);

        return response()->json($hospital);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $hospital = Hospital::findOrFail($id);

        $hospital->update([
            'name' => $request->name,
            'district_id' => $request->district_id,
            'address' => $request->address,
            'phone' => $request->phone
        ]);

        return response()->json([
            'message' => 'Hospital updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Hospital::destroy($id);

        return response()->json([
            'message' => 'Hospital deleted successfully'
        ]);
    }
}
