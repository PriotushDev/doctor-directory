<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upazila;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:upazila.create')->only('store');
        $this->middleware('permission:upazila.update')->only('update');
        $this->middleware('permission:upazila.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Upazila::query();

        if ($request->has('district_id') && $request->district_id != '') {
            $query->where('district_id', $request->district_id);
        }

        $upazilas = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $upazilas
        ]);
    }

    public function show($id)
    {
        $upazila = Upazila::with('district')->find($id);

        if (!$upazila) {
            return response()->json([
                'success' => false,
                'message' => 'Upazila not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $upazila
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'bangla_name' => 'nullable|string|max:255'
        ]);

        $upazila = Upazila::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Upazila created successfully',
            'data' => $upazila
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $upazila = Upazila::findOrFail($id);

        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'bangla_name' => 'nullable|string|max:255'
        ]);

        $upazila->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Upazila updated successfully',
            'data' => $upazila
        ]);
    }

    public function destroy($id)
    {
        $upazila = Upazila::findOrFail($id);
        $upazila->delete();

        return response()->json([
            'success' => true,
            'message' => 'Upazila deleted successfully'
        ]);
    }
}
