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

        // 🔥 Upazila filter
        if ($request->upazila_id) {
            $query->where('upazila_id', $request->upazila_id);
        }

        // 🔥 Union filter
        if ($request->union_id) {
            $query->where('union_id', $request->union_id);
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
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('hospitals', 'public');
        }

        $hospital = Hospital::create($data);

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
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($hospital->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($hospital->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($hospital->photo);
            }
            $data['photo'] = $request->file('photo')->store('hospitals', 'public');
        } elseif (isset($data['photo']) && is_string($data['photo'])) {
            // Keep existing photo if string, or remove if needed... actually ignore string photo
            unset($data['photo']);
        }

        $hospital->update($data);

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
