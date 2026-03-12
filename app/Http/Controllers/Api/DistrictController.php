<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\District;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $districts = District::with('division')->latest()->get();

        return response()->json($districts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $district = District::create([
            'name' => $request->name,
            'division_id' => $request->division_id
        ]);

        return response()->json([
            'message' => 'District created successfully',
            'data' => $district
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $district = District::with('division')->findOrFail($id);

        return response()->json($district);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $district = District::findOrFail($id);

        $district->update([
            'name' => $request->name,
            'division_id' => $request->division_id
        ]);

        return response()->json([
            'message' => 'District updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        District::destroy($id);

        return response()->json([
            'message' => 'District deleted successfully'
        ]);
    }
}
