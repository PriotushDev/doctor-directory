<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackage;
use App\Models\PromoCode;
use App\Models\DoctorSubscription;
use App\Models\DoctorTrialDay;
use App\Models\DoctorNotification;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    // ===== PACKAGES =====

    public function packageIndex()
    {
        $packages = SubscriptionPackage::orderBy('sort_order')->orderBy('duration_months')->get();
        return response()->json(['success' => true, 'data' => $packages]);
    }

    public function packageStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration_months' => 'required|integer|in:1,3,6,12',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'features' => 'nullable|array',
            'is_popular' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $package = SubscriptionPackage::create($request->all());
        return response()->json(['success' => true, 'data' => $package], 201);
    }

    public function packageUpdate(Request $request, $id)
    {
        $package = SubscriptionPackage::findOrFail($id);
        $package->update($request->all());
        return response()->json(['success' => true, 'data' => $package]);
    }

    public function packageDestroy($id)
    {
        SubscriptionPackage::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Package deleted']);
    }

    // ===== PROMO CODES =====

    public function promoIndex()
    {
        $promos = PromoCode::orderByDesc('created_at')->get();
        return response()->json(['success' => true, 'data' => $promos]);
    }

    public function promoStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code|max:50',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
        ]);

        $data = $request->all();
        $data['code'] = strtoupper(trim($data['code']));
        $promo = PromoCode::create($data);
        return response()->json(['success' => true, 'data' => $promo], 201);
    }

    public function promoUpdate(Request $request, $id)
    {
        $promo = PromoCode::findOrFail($id);
        $data = $request->all();
        if (isset($data['code'])) {
            $data['code'] = strtoupper(trim($data['code']));
        }
        $promo->update($data);
        return response()->json(['success' => true, 'data' => $promo]);
    }

    public function promoDestroy($id)
    {
        PromoCode::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Promo code deleted']);
    }

    // ===== TRIAL DAYS =====

    public function trialIndex()
    {
        $trials = DoctorTrialDay::with('doctor', 'grantedBy')
            ->orderByDesc('created_at')
            ->get();
        return response()->json(['success' => true, 'data' => $trials]);
    }

    public function trialStore(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'trial_days' => 'required|integer|min:1|max:365',
            'reason' => 'nullable|string',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        $startDate = now()->startOfDay();
        $endDate = $startDate->copy()->addDays($request->trial_days);

        $trial = DoctorTrialDay::create([
            'doctor_id' => $doctor->id,
            'user_id' => $doctor->user_id,
            'trial_days' => $request->trial_days,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'granted_by' => $request->user()->id,
            'reason' => $request->reason,
        ]);

        $trial->load('doctor', 'grantedBy');

        return response()->json(['success' => true, 'data' => $trial], 201);
    }

    public function trialDestroy($id)
    {
        DoctorTrialDay::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Trial entry deleted']);
    }

    // ===== SUBSCRIPTIONS MANAGEMENT =====

    public function subscriptionIndex(Request $request)
    {
        $query = DoctorSubscription::with('doctor', 'package', 'user');

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('doctor', function($qd) use ($search) {
                    $qd->where('name', 'like', "%{$search}%");
                })->orWhereHas('user', function($qu) use ($search) {
                    $qu->where('email', 'like', "%{$search}%");
                });
            });
        }

        $subscriptions = $query->orderByDesc('created_at')->paginate(20);
        return response()->json(['success' => true, 'data' => $subscriptions]);
    }

    public function subscriptionUpdate(Request $request, $id)
    {
        $subscription = DoctorSubscription::findOrFail($id);

        $request->validate([
            'payment_status' => 'nullable|in:pending,verified,rejected',
            'status' => 'nullable|in:active,expired,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($request->has('payment_status')) {
            $subscription->payment_status = $request->payment_status;
        }
        if ($request->has('status')) {
            $subscription->status = $request->status;
        }
        if ($request->has('notes')) {
            $subscription->notes = $request->notes;
        }

        $subscription->save();
        $subscription->load('doctor', 'package', 'user');

        return response()->json(['success' => true, 'data' => $subscription]);
    }

    // ===== MESSAGES / NOTIFICATIONS =====

    public function sendNotification(Request $request)
    {
        $request->validate([
            'doctor_id' => 'nullable|exists:doctors,id', // null = broadcast
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|in:warning,info,promo,system,expiry',
            'is_popup' => 'nullable|boolean',
        ]);

        $userId = null;
        if ($request->doctor_id) {
            $doctor = Doctor::find($request->doctor_id);
            $userId = $doctor?->user_id;
        }

        $notification = DoctorNotification::create([
            'doctor_id' => $request->doctor_id,
            'user_id' => $userId,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type ?? 'info',
            'is_popup' => $request->is_popup ?? false,
            'sent_by' => $request->user()->id,
        ]);

        return response()->json(['success' => true, 'data' => $notification], 201);
    }

    public function notificationIndex()
    {
        $notifications = DoctorNotification::with(['doctor', 'sender', 'reads.doctor'])
            ->orderByDesc('created_at')
            ->paginate(30);
        return response()->json(['success' => true, 'data' => $notifications]);
    }

    public function notificationDestroy($id)
    {
        DoctorNotification::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Notification deleted']);
    }
}
