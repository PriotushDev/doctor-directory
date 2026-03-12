<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DoctorChamber;

class DoctorChamberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chambers = DoctorChamber::with(['doctor','hospital'])->latest()->get();

        return response()->json($chambers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $chamber = DoctorChamber::create([
            'doctor_id' => $request->doctor_id,
            'hospital_id' => $request->hospital_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'fee' => $request->fee
        ]);

        return response()->json([
            'message' => 'Doctor chamber created successfully',
            'data' => $chamber
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $chamber = DoctorChamber::with(['doctor','hospital'])->findOrFail($id);

        return response()->json($chamber);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $chamber = DoctorChamber::findOrFail($id);

        $chamber->update($request->all());

        return response()->json([
            'message' => 'Chamber updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DoctorChamber::destroy($id);

        return response()->json([
            'message' => 'Chamber deleted successfully'
        ]);
    }
}
