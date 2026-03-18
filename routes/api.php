<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\HospitalController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\SpecialtyController;
use App\Http\Controllers\Api\DoctorChamberController;
use App\Http\Controllers\Api\AuthController;


Route::get('/test', function () {
    return response()->json([
        'message' => 'API working'
    ]);
});



Route::apiResource('divisions', DivisionController::class);
Route::apiResource('districts', DistrictController::class);
Route::apiResource('hospitals', HospitalController::class);
Route::apiResource('specialties', SpecialtyController::class);

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

// PUBLIC
Route::get('/doctors', [DoctorController::class, 'index']);
Route::get('/doctors/{id}', [DoctorController::class, 'show']);



Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout',[AuthController::class,'logout']);
    Route::apiResource('doctor-chambers', DoctorChamberController::class);
    Route::apiResource('appointments', AppointmentController::class);
    // Route::apiResource('doctors', DoctorController::class);
    Route::post('/doctors', [DoctorController::class, 'store']);
    Route::put('/doctors/{id}', [DoctorController::class, 'update']);
    Route::delete('/doctors/{id}', [DoctorController::class, 'destroy']);

    Route::apiResource('users', UserController::class);


});