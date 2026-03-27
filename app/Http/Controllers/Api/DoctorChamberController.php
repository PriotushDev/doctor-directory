<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorChamber;
use App\Http\Requests\StoreDoctorChamberRequest;
use App\Http\Requests\UpdateDoctorChamberRequest;

class DoctorChamberController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:sanctum');

        // $this->middleware('permission:doctor_chamber.view')->only(['index','show']);
        $this->middleware('permission:doctor_chamber.create')->only('store');
        $this->middleware('permission:doctor_chamber.update')->only('update');
        $this->middleware('permission:doctor_chamber.delete')->only('destroy');
    } 

    public function index()
    {
        $chambers = DoctorChamber::with(['doctor', 'hospital'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $chambers
        ]);
    }

    public function store(StoreDoctorChamberRequest $request)
    {
        $chamber = DoctorChamber::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Doctor chamber created successfully',
            'data' => $chamber
        ]);
    }

    public function show($id)
    {
        $chamber = DoctorChamber::with(['doctor', 'hospital'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $chamber
        ]);
    }

    public function update(UpdateDoctorChamberRequest $request, $id)
    {
        $chamber = DoctorChamber::findOrFail($id);

        $chamber->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Doctor chamber updated successfully',
            'data' => $chamber
        ]);
    }

    public function destroy($id)
    {
        $chamber = DoctorChamber::findOrFail($id);

        $chamber->delete();

        return response()->json([
            'success' => true,
            'message' => 'Doctor chamber deleted successfully'
        ]);
    }
}
