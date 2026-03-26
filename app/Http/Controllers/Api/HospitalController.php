<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Http\Requests\StoreHospitalRequest;
use App\Http\Requests\UpdateHospitalRequest;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::with('district')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $hospitals
        ]);
    }

    public function store(StoreHospitalRequest $request)
    {
        $hospital = Hospital::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Hospital created successfully',
            'data' => $hospital
        ]);
    }

    public function show($id)
    {
        $hospital = Hospital::with('district')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $hospital
        ]);
    }

    public function update(UpdateHospitalRequest $request, $id)
    {
        $hospital = Hospital::findOrFail($id);

        $hospital->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Hospital updated successfully',
            'data' => $hospital
        ]);
    }

    public function destroy($id)
    {
        $hospital = Hospital::findOrFail($id);

        $hospital->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hospital deleted successfully'
        ]);
    }
}
