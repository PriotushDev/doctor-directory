<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Union;
use Illuminate\Http\Request;

class UnionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:union.create')->only('store');
        $this->middleware('permission:union.update')->only('update');
        $this->middleware('permission:union.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Union::query();

        if ($request->has('upazila_id') && $request->upazila_id != '') {
            $query->where('upazila_id', $request->upazila_id);
        }

        $unions = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $unions
        ]);
    }

    public function show($id)
    {
        $union = Union::with('upazila.district')->find($id);

        if (!$union) {
            return response()->json([
                'success' => false,
                'message' => 'Union not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $union
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:255',
            'bangla_name' => 'nullable|string|max:255'
        ]);

        $union = Union::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Union created successfully',
            'data' => $union
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $union = Union::findOrFail($id);

        $validated = $request->validate([
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:255',
            'bangla_name' => 'nullable|string|max:255'
        ]);

        $union->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Union updated successfully',
            'data' => $union
        ]);
    }

    public function destroy($id)
    {
        $union = Union::findOrFail($id);
        $union->delete();

        return response()->json([
            'success' => true,
            'message' => 'Union deleted successfully'
        ]);
    }
}
