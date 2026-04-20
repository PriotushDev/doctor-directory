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
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\WalkInPatientController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\DoctorNotificationController;
use App\Http\Controllers\Api\AdminSubscriptionController;
use App\Http\Controllers\Api\MedicineController;
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

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

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
| PUBLIC SUBSCRIPTION ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/subscription/packages', [SubscriptionController::class, 'packages']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (AUTH REQUIRED)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Current user info (with roles)
    Route::get('/me', [AuthController::class, 'me']);

    // Admin dashboard stats
    Route::get('/admin/stats', [AdminController::class, 'stats']);

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
    Route::get('permissions', [UserController::class, 'getPermissions']);
    Route::apiResource('users', UserController::class);
    Route::put('users/{id}/role', [UserController::class, 'updateRole']);
    Route::put('users/{id}/permissions', [UserController::class, 'syncPermissions']);

    // Prescriptions
    Route::apiResource('prescriptions', PrescriptionController::class);

    // Doctor Walk-in Patients
    Route::post('doctor/patients', [WalkInPatientController::class, 'store']);

    // Medicines (Auto-complete & CRUD)
    Route::apiResource('medicines', MedicineController::class);

    // ===== SUBSCRIPTION SYSTEM =====
    Route::prefix('subscription')->group(function () {
        Route::post('/validate-promo', [SubscriptionController::class, 'validatePromo']);
        Route::post('/purchase', [SubscriptionController::class, 'purchase']);
        Route::get('/status', [SubscriptionController::class, 'status']);
        Route::get('/history', [SubscriptionController::class, 'history']);
    });

    // ===== DOCTOR NOTIFICATIONS =====
    Route::prefix('notifications')->group(function () {
        Route::get('/', [DoctorNotificationController::class, 'index']);
        Route::get('/popups', [DoctorNotificationController::class, 'popups']);
        Route::get('/unread-count', [DoctorNotificationController::class, 'unreadCount']);
        Route::put('/{id}/read', [DoctorNotificationController::class, 'markRead']);
        Route::post('/mark-all-read', [DoctorNotificationController::class, 'markAllRead']);
    });

    // ===== ADMIN SUBSCRIPTION MANAGEMENT =====
    Route::prefix('admin')->group(function () {
        // Packages
        Route::get('/packages', [AdminSubscriptionController::class, 'packageIndex']);
        Route::post('/packages', [AdminSubscriptionController::class, 'packageStore']);
        Route::put('/packages/{id}', [AdminSubscriptionController::class, 'packageUpdate']);
        Route::delete('/packages/{id}', [AdminSubscriptionController::class, 'packageDestroy']);

        // Promo Codes
        Route::get('/promo-codes', [AdminSubscriptionController::class, 'promoIndex']);
        Route::post('/promo-codes', [AdminSubscriptionController::class, 'promoStore']);
        Route::put('/promo-codes/{id}', [AdminSubscriptionController::class, 'promoUpdate']);
        Route::delete('/promo-codes/{id}', [AdminSubscriptionController::class, 'promoDestroy']);

        // Trial Days
        Route::get('/trial-days', [AdminSubscriptionController::class, 'trialIndex']);
        Route::post('/trial-days', [AdminSubscriptionController::class, 'trialStore']);
        Route::delete('/trial-days/{id}', [AdminSubscriptionController::class, 'trialDestroy']);

        // Subscription Management
        Route::get('/subscriptions', [AdminSubscriptionController::class, 'subscriptionIndex']);
        Route::put('/subscriptions/{id}', [AdminSubscriptionController::class, 'subscriptionUpdate']);

        // Messages / Notifications
        Route::get('/sent-notifications', [AdminSubscriptionController::class, 'notificationIndex']);
        Route::post('/send-notification', [AdminSubscriptionController::class, 'sendNotification']);
        Route::delete('/notifications/{id}', [AdminSubscriptionController::class, 'notificationDestroy']);
    });

});



/*
|--------------------------------------------------------------------------
| OPEN DATA (NO AUTH NEEDED)
|--------------------------------------------------------------------------
*/