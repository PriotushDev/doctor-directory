<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;

class DivisionController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:sanctum');

        $this->middleware('permission:division.view')->only(['index','show']);
        $this->middleware('permission:division.create')->only('store');
        $this->middleware('permission:division.update')->only('update');
        $this->middleware('permission:division.delete')->only('destroy');
    }    

    public function index()
    {
        $divisions = Division::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $divisions
        ]);
    }

    public function store(StoreDivisionRequest $request)
    {
        $division = Division::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Division created successfully',
            'data' => $division
        ]);
    }

    public function show($id)
    {
        $division = Division::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $division
        ]);
    }

    public function update(UpdateDivisionRequest $request, $id)
    {
        // dd('Controller reached', $id, $request->all());    
        // dd(auth()->user());
        // dd(auth()->user()->getRoleNames(), auth()->user()->getAllPermissions());


        $division = Division::findOrFail($id);

        $division->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Division updated successfully',
            'data' => $division
        ]);
    }

    public function destroy($id)
    {
        $division = Division::findOrFail($id);

        $division->delete();

        return response()->json([
            'success' => true,
            'message' => 'Division deleted successfully'
        ]);
    }
}