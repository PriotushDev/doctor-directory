<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Doctor;
use App\Models\DoctorSubscription;
use App\Models\DoctorTrialDay;
class AuthController extends Controller
{
    
    
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6'
            ]);

            // Generate unique 8 digit registration number
            do {
                $patientId = random_int(10000000, 99999999);
            } while (User::where('patient_id', $patientId)->exists());

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'patient_id' => $patientId
            ]);

            // ✅ assign role (only if exists)
            if (class_exists(\Spatie\Permission\Models\Role::class)) {
                $user->assignRole('user');
            }

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => $user
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
        

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = auth()->user();

            $token = $user->createToken('auth_token')->plainTextToken;

            // Load roles so frontend knows the user's role
            $user->load('roles');

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(),
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                    'subscription_status' => $this->getSubscriptionStatus($user),
                ]
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage() // 👈 SHOW REAL ERROR
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get the currently authenticated user with roles.
     * Used by frontend to restore session on page refresh.
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('roles');

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'subscription_status' => $this->getSubscriptionStatus($user),
            ]
        ]);
    }

    /**
     * Build subscription status payload for a user.
     */
    private function getSubscriptionStatus($user): array
    {
        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return [
                'is_doctor' => false,
                'has_access' => false,
                'subscription' => null,
                'trial' => null,
                'days_remaining' => null,
                'show_warning' => false,
            ];
        }

        $activeSub = DoctorSubscription::where('doctor_id', $doctor->id)
            ->where('status', 'active')
            ->where('payment_status', 'verified')
            ->where('end_date', '>=', now()->startOfDay())
            ->latest('end_date')
            ->first();

        $activeTrial = DoctorTrialDay::where('doctor_id', $doctor->id)
            ->where('end_date', '>=', now()->startOfDay())
            ->latest('end_date')
            ->first();

        $hasAccess = $activeSub || $activeTrial;
        $daysRemaining = null;
        $expiryDate = null;

        if ($activeSub) {
            $daysRemaining = max(0, (int) now()->startOfDay()->diffInDays($activeSub->end_date, false));
            $expiryDate = $activeSub->end_date->format('Y-m-d');
        } elseif ($activeTrial) {
            $daysRemaining = max(0, (int) now()->startOfDay()->diffInDays($activeTrial->end_date, false));
            $expiryDate = $activeTrial->end_date->format('Y-m-d');
        }

        return [
            'is_doctor' => true,
            'has_access' => (bool) $hasAccess,
            'days_remaining' => $daysRemaining,
            'expiry_date' => $expiryDate,
            'show_warning' => $daysRemaining !== null && $daysRemaining <= 7,
            'is_trial' => !$activeSub && (bool) $activeTrial,
            'subscription' => $activeSub ? [
                'id' => $activeSub->id,
                'package_name' => $activeSub->package?->name,
                'end_date' => $activeSub->end_date->format('Y-m-d'),
                'status' => $activeSub->status,
            ] : null,
            'trial' => $activeTrial ? [
                'end_date' => $activeTrial->end_date->format('Y-m-d'),
                'trial_days' => $activeTrial->trial_days,
            ] : null,
        ];
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Usually you would send an email here using Laravel Mail.
        // For development/API testing purposes, we return it or assume it's sent.
        // E.g. Mail::to($request->email)->send(new ResetPasswordMail($token));

        return response()->json([
            'success' => true,
            'message' => 'Reset password link sent to your email (in a real scenario).',
            'token' => $token // Returning token for demonstration/dev only
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $reset = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->where('token', $request->token)
                    ->first();

        if (!$reset) {
            return response()->json(['success' => false, 'message' => 'Invalid token!'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['success' => true, 'message' => 'Password has been successfully changed']);
    }
}
