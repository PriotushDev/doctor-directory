<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Http\Requests\StoreHospitalRequest;
use App\Http\Requests\UpdateHospitalRequest;
use Illuminate\Http\Request; 

class HospitalController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:sanctum');

        // $this->middleware('permission:hospital.view')->only(['index','show']);
        $this->middleware('permission:hospital.create')->only('store');
        $this->middleware('permission:hospital.update')->only('update');
        $this->middleware('permission:hospital.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Hospital::with('district.division');

        // 🔥 Division filter
        if ($request->division_id) {
            $query->whereHas('district', function ($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        // 🔥 District filter
        if ($request->district_id) {
            $query->where('district_id', $request->district_id);
        }

        // 🔥 Search
        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // 🔥 Pagination (MAIN FIX)
        $data = $query->latest()->paginate($request->per_page ?? 9);

        return response()->json([
            'success' => true,
            'data' => $data
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
