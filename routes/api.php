<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\HospitalController;
use App\Http\Controllers\Api\SpecialtyController;
use App\Http\Controllers\Api\DoctorChamberController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| TEST ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/test', function () {
    return response()->json([
        'message' => 'API working'
    ]);
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:login');

/*
|--------------------------------------------------------------------------
| PUBLIC DOCTOR ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/doctors', [DoctorController::class, 'index']);
Route::get('/doctors/{id}', [DoctorController::class, 'show']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (AUTH REQUIRED)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Doctors (protected actions only)
    Route::post('/doctors', [DoctorController::class, 'store']);
    Route::put('/doctors/{id}', [DoctorController::class, 'update']);
    Route::delete('/doctors/{id}', [DoctorController::class, 'destroy']);
    
    Route::apiResource('divisions', DivisionController::class);
    Route::apiResource('districts', DistrictController::class);
    Route::apiResource('hospitals', HospitalController::class);
    Route::apiResource('specialties', SpecialtyController::class);
    Route::apiResource('doctor-chambers', DoctorChamberController::class);

    // Appointments (ONLY ONE PLACE)
    Route::middleware('throttle:appointments')->group(function () {
        Route::apiResource('appointments', AppointmentController::class);
    });

    // Users
    Route::apiResource('users', UserController::class);
});

/*
|--------------------------------------------------------------------------
| OPEN DATA (NO AUTH NEEDED)
|--------------------------------------------------------------------------
*/