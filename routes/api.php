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
| PUBLIC DIVISION ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/divisions', [DivisionController::class, 'index']);
Route::get('/divisions/{id}', [DivisionController::class, 'show']);

/*
|--------------------------------------------------------------------------
| PUBLIC DISTRICT ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/districts', [DistrictController::class, 'index']);
Route::get('/districts/{id}', [DistrictController::class, 'show']);

/*
|--------------------------------------------------------------------------
| PUBLIC HOSPITAL ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/hospitals', [HospitalController::class, 'index']);
Route::get('/hospitals/{id}', [HospitalController::class, 'show']);

/*
|--------------------------------------------------------------------------
| PUBLIC SPECIALTY ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/specialties', [SpecialtyController::class, 'index']);
Route::get('/specialties/{id}', [SpecialtyController::class, 'show']);

/*
|--------------------------------------------------------------------------
| PUBLIC DOCTOR CHAMBER ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/doctor-chambers', [DoctorChamberController::class, 'index']);
Route::get('/doctor-chambers/{id}', [DoctorChamberController::class, 'show']);

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

    // Divisions
    Route::post('/divisions', [DivisionController::class, 'store']);
    Route::put('/divisions/{id}', [DivisionController::class, 'update']);
    Route::delete('/divisions/{id}', [DivisionController::class, 'destroy']);

    // Districts
    Route::post('/districts', [DistrictController::class, 'store']);
    Route::put('/districts/{id}', [DistrictController::class, 'update']);
    Route::delete('/districts/{id}', [DistrictController::class, 'destroy']);

    // Hospitals
    Route::post('/hospitals', [HospitalController::class, 'store']);
    Route::put('/hospitals/{id}', [HospitalController::class, 'update']);
    Route::delete('/hospitals/{id}', [HospitalController::class, 'destroy']);

    // Specialties
    Route::post('/specialties', [SpecialtyController::class, 'store']);
    Route::put('/specialties/{id}', [SpecialtyController::class, 'update']);
    Route::delete('/specialties/{id}', [SpecialtyController::class, 'destroy']);

    // Doctor Chambers
    Route::post('/doctor-chambers', [DoctorChamberController::class, 'store']);
    Route::put('/doctor-chambers/{id}', [DoctorChamberController::class, 'update']);
    Route::delete('/doctor-chambers/{id}', [DoctorChamberController::class, 'destroy']);

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