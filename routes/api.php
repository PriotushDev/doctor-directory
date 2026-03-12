<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\HospitalController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\AppointmentController;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API working'
    ]);
});

Route::apiResource('divisions', DivisionController::class);
Route::apiResource('districts', DistrictController::class);
Route::apiResource('hospitals', HospitalController::class);
Route::apiResource('doctors', DoctorController::class);
Route::apiResource('appointments', AppointmentController::class);