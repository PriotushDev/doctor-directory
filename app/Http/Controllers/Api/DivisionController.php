<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Division;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Division::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $division = Division::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Division created successfully',
            'data' => $division
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Division::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);

        $division->update([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Division updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Division::destroy($id);

        return response()->json([
            'message' => 'Division deleted successfully'
        ]);
    }
}
