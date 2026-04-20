<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\PrescriptionMedicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');
        $this->middleware('permission:medicine.create')->only('store');
        $this->middleware('permission:medicine.update')->only('update');
        $this->middleware('permission:medicine.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Medicine::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('medicine_name', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%");
        }
        
        if ($request->has('dosage_type') && $request->dosage_type !== 'ALL') {
            $query->where('dosage_type', $request->dosage_type);
        }
        
        if ($request->has('company_name')) {
            $query->where('company_name', 'like', '%' . $request->company_name . '%');
        }

        // Paginated output for admin panel
        if ($request->has('page') || $request->has('per_page')) {
            $perPage = $request->get('per_page', 15);
            $medicines = $query->paginate($perPage);
            return response()->json($medicines);
        }

        // Original limited list for prescription write typeahead
        $medicines = $query->take(20)->get()->map(function($med) {
            $lastUsage = PrescriptionMedicine::where('medicine_name', $med->medicine_name)
                ->latest()
                ->first();
            
            return [
                'id' => $med->id,
                'medicine_name' => $med->medicine_name,
                'generic_name' => $med->generic_name,
                'strength' => $med->strength,
                'dosage_type' => $med->dosage_type,
                'company_name' => $med->company_name,
                'dosage' => $lastUsage?->dosage,
                'duration' => $lastUsage?->duration,
                'instructions' => $lastUsage?->instructions,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $medicines
        ]);
    }

    public function show($id)
    {
        $medicine = Medicine::findOrFail($id);
        return response()->json(['success' => true, 'data' => $medicine]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage_type' => 'required|string',
        ]);

        $medicine = Medicine::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Medicine created successfully',
            'data' => $medicine
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage_type' => 'required|string',
        ]);

        $medicine->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Medicine updated successfully',
            'data' => $medicine
        ]);
    }

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return response()->json([
            'success' => true,
            'message' => 'Medicine deleted successfully'
        ]);
    }
}
