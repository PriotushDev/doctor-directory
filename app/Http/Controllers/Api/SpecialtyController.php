<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialty;

class SpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Specialty::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $specialty = Specialty::create([
            'name' => $request->name,
            'slug' => $request->slug
        ]);

        return response()->json([
            'message' => 'Specialty created successfully',
            'data' => $specialty
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Specialty::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $specialty = Specialty::findOrFail($id);

        $specialty->update([
            'name' => $request->name,
            'slug' => $request->slug
        ]);

        return response()->json([
            'message' => 'Specialty updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Specialty::destroy($id);

        return response()->json([
            'message' => 'Specialty deleted successfully'
        ]);
    }
}
