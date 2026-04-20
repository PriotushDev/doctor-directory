<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorNotification;
use App\Models\NotificationRead;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorNotificationController extends Controller
{
    /**
     * Get notifications for the current doctor.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $notifications = DoctorNotification::with(['reads' => function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            }])
            ->where(function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id)
                  ->orWhereNull('doctor_id'); // broadcasts
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        $notifications->getCollection()->transform(function ($notification) {
            if (is_null($notification->doctor_id)) {
                $notification->is_read = $notification->reads->isNotEmpty();
            }
            $notification->unsetRelation('reads');
            return $notification;
        });

        return response()->json(['success' => true, 'data' => $notifications]);
    }

    /**
     * Get unread popup notifications (shown on login).
     */
    public function popups(Request $request)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $popups = DoctorNotification::popupFor($doctor->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $popups]);
    }

    /**
     * Mark a notification as read.
     */
    public function markRead(Request $request, $id)
    {
        $doctor = Doctor::where('user_id', $request->user()->id)->first();
        if (!$doctor) return response()->json(['success' => false], 403);

        $notification = DoctorNotification::findOrFail($id);

        if (is_null($notification->doctor_id)) {
            NotificationRead::firstOrCreate([
                'notification_id' => $notification->id,
                'doctor_id' => $doctor->id
            ]);
        } else {
            $notification->update(['is_read' => true]);
        }

        return response()->json(['success' => true, 'message' => 'Marked as read']);
    }

    /**
     * Mark all notifications as read for a doctor.
     */
    public function markAllRead(Request $request)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        if ($doctor) {
            // 1. Mark direct messages read
            DoctorNotification::where('doctor_id', $doctor->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            // 2. Mark broadcast messages read
            $unreadBroadcasts = DoctorNotification::whereNull('doctor_id')
                ->whereDoesntHave('reads', function ($qr) use ($doctor) {
                    $qr->where('doctor_id', $doctor->id);
                })->get();

            $readsData = [];
            foreach ($unreadBroadcasts as $notification) {
                $readsData[] = [
                    'notification_id' => $notification->id,
                    'doctor_id' => $doctor->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($readsData)) {
                NotificationRead::insert($readsData);
            }
        }

        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
    }

    /**
     * Get unread count.
     */
    public function unreadCount(Request $request)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        $count = 0;
        if ($doctor) {
            $count = DoctorNotification::unreadFor($doctor->id)->count();
        }

        return response()->json(['success' => true, 'count' => $count]);
    }
}
