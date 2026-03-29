<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialty;
use App\Http\Requests\StoreSpecialtyRequest;
use App\Http\Requests\UpdateSpecialtyRequest;

class SpecialtyController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:sanctum');

        // $this->middleware('permission:specialty.view')->only(['index','show']);
        $this->middleware('permission:specialty.create')->only('store');
        $this->middleware('permission:specialty.update')->only('update');
        $this->middleware('permission:specialty.delete')->only('destroy');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specialties = Specialty::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $specialties
        ]);
    }

    public function store(StoreSpecialtyRequest $request)
    {
        $specialty = Specialty::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Specialty created successfully',
            'data' => $specialty
        ]);
    }

    public function show($id)
    {
        $specialty = Specialty::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $specialty
        ]);
    }

    public function update(UpdateSpecialtyRequest $request, $id)
    {
        $specialty = Specialty::findOrFail($id);

        $specialty->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Specialty updated successfully',
            'data' => $specialty
        ]);
    }

    public function destroy($id)
    {
        $specialty = Specialty::findOrFail($id);

        $specialty->delete();

        return response()->json([
            'success' => true,
            'message' => 'Specialty deleted successfully'
        ]);
    }
}

