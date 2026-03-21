<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Doctor::with(['specialty', 'hospital']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('specialty_id')) {
            $query->where('specialty_id', (int)$request->specialty_id);
        }

        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', (int)$request->hospital_id);
        }

        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $doctor = Doctor::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'specialty_id' => $request->specialty_id,
            'hospital_id' => $request->hospital_id,
            'degree' => $request->degree,
            'experience' => $request->experience,
            'phone' => $request->phone,
            'email' => $request->email,
            'bio' => $request->bio
        ]);

        return response()->json([
            'message' => 'Doctor created successfully',
            'data' => $doctor
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $doctor = Doctor::with(['hospital','specialty','chambers'])->findOrFail($id);

        return response()->json($doctor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $doctor = Doctor::update([
            'name' => $request->name,
            'slug' => $request->slug,
            'specialty_id' => $request->specialty_id,
            'hospital_id' => $request->hospital_id,
            'degree' => $request->degree,
            'experience' => $request->experience,
            'phone' => $request->phone,
            'email' => $request->email,
            'bio' => $request->bio
        ]);

        return response()->json([
            'message' => 'Doctor updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Doctor::destroy($id);

        return response()->json([
            'message' => 'Doctor deleted successfully'
        ]);
    }
}
