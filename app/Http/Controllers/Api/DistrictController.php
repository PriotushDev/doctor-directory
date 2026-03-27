<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Http\Requests\StoreDistrictRequest;
use App\Http\Requests\UpdateDistrictRequest;

class DistrictController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:sanctum');

        // $this->middleware('permission:district.view')->only(['index','show']);
        $this->middleware('permission:district.create')->only('store');
        $this->middleware('permission:district.update')->only('update');
        $this->middleware('permission:district.delete')->only('destroy');
    } 

    public function index()
    {
        $districts = District::with('division')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $districts
        ]);
    }

    public function store(StoreDistrictRequest $request)
    {
        $district = District::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'District created successfully',
            'data' => $district
        ]);
    }

    public function show($id)
    {
        $district = District::with('division')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $district
        ]);
    }

    public function update(UpdateDistrictRequest $request, $id)
    {
        $district = District::findOrFail($id);

        $district->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'District updated successfully',
            'data' => $district
        ]);
    }

    public function destroy($id)
    {
        $district = District::findOrFail($id);

        $district->delete();

        return response()->json([
            'success' => true,
            'message' => 'District deleted successfully'
        ]);
    }
}