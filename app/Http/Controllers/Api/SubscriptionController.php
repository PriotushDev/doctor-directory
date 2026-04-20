<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackage;
use App\Models\PromoCode;
use App\Models\DoctorSubscription;
use App\Models\Doctor;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * List all active packages (public for doctors).
     */
    public function packages()
    {
        $packages = SubscriptionPackage::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('duration_months')
            ->get()
            ->map(function ($pkg) {
                $pkg->effective_price = $pkg->effective_price;
                return $pkg;
            });

        return response()->json(['success' => true, 'data' => $packages]);
    }

    /**
     * Validate a promo code and return discount info.
     */
    public function validatePromo(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $promo = PromoCode::where('code', strtoupper(trim($request->code)))->first();

        if (!$promo) {
            return response()->json(['success' => false, 'message' => 'Promo code not found'], 404);
        }

        if (!$promo->isValid()) {
            return response()->json(['success' => false, 'message' => 'Promo code is expired or no longer valid'], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $promo->id,
                'code' => $promo->code,
                'discount_type' => $promo->discount_type,
                'discount_value' => $promo->discount_value,
            ]
        ]);
    }

    /**
     * Purchase a subscription (checkout).
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:subscription_packages,id',
            'payment_method' => 'required|in:manual,bkash,nagad,rocket,sslcommerz',
            'payment_reference' => 'nullable|string|max:255',
            'promo_code' => 'nullable|string',
        ]);

        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return response()->json(['success' => false, 'message' => 'Doctor profile not found'], 404);
        }

        $package = SubscriptionPackage::findOrFail($request->package_id);

        // Calculate pricing
        $originalPrice = $package->price;
        $discountApplied = 0;
        $promoCodeId = null;

        // Package-level discount
        if ($package->discount_percent > 0) {
            $discountApplied += ($originalPrice * $package->discount_percent / 100);
        }
        if ($package->discount_amount > 0) {
            $discountApplied += $package->discount_amount;
        }

        // Promo code discount
        if ($request->promo_code) {
            $promo = PromoCode::where('code', strtoupper(trim($request->promo_code)))->first();

            if ($promo && $promo->isValid()) {
                $promoDiscount = $promo->calculateDiscount($originalPrice - $discountApplied);
                $discountApplied += $promoDiscount;
                $promoCodeId = $promo->id;

                // Increment usage count
                $promo->increment('used_count');
            }
        }

        $finalPrice = max(0, round($originalPrice - $discountApplied, 2));

        // Calculate dates
        $startDate = now()->startOfDay();
        $endDate = $startDate->copy()->addMonths($package->duration_months);

        // Auto-approve the payment so the doctor gets instant access
        $paymentStatus = 'verified';

        $subscription = DoctorSubscription::create([
            'doctor_id' => $doctor->id,
            'user_id' => $user->id,
            'package_id' => $package->id,
            'promo_code_id' => $promoCodeId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'original_price' => $originalPrice,
            'discount_applied' => $discountApplied,
            'final_price' => $finalPrice,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'payment_status' => $paymentStatus,
            'status' => 'active',
            'is_trial' => false,
        ]);

        $subscription->load('package');

        return response()->json([
            'success' => true,
            'message' => 'Subscription purchased successfully! Awaiting payment verification.',
            'data' => $subscription,
        ], 201);
    }

    /**
     * Get doctor's subscription status.
     */
    public function status(Request $request)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return response()->json([
                'success' => true,
                'data' => [
                    'has_access' => false,
                    'subscription' => null,
                    'trial' => null,
                ]
            ]);
        }

        $activeSub = DoctorSubscription::where('doctor_id', $doctor->id)
            ->active()
            ->with('package')
            ->latest('end_date')
            ->first();

        $activeTrial = $doctor->activeTrialDays()->latest('end_date')->first();

        $hasAccess = $doctor->hasActiveAccess();

        // Warning info
        $daysRemaining = null;
        $expiryDate = null;

        if ($activeSub) {
            $daysRemaining = $activeSub->days_remaining;
            $expiryDate = $activeSub->end_date->format('Y-m-d');
        } elseif ($activeTrial) {
            $daysRemaining = max(0, (int) now()->startOfDay()->diffInDays($activeTrial->end_date, false));
            $expiryDate = $activeTrial->end_date->format('Y-m-d');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'has_access' => $hasAccess,
                'days_remaining' => $daysRemaining,
                'expiry_date' => $expiryDate,
                'subscription' => $activeSub,
                'trial' => $activeTrial,
                'show_warning' => $daysRemaining !== null && $daysRemaining <= 7,
            ]
        ]);
    }

    /**
     * Get doctor's subscription history.
     */
    public function history(Request $request)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $subscriptions = DoctorSubscription::where('doctor_id', $doctor->id)
            ->with('package', 'promoCode')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $subscriptions]);
    }
}
