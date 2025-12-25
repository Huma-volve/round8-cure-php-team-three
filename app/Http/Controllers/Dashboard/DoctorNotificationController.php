<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorNotificationController extends Controller
{

    public function index(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();
        $userId = method_exists($doctor, 'getAttribute') ? $doctor->getAttribute('user_id') : ($doctor->user->id ?? null);
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد مستخدم مرتبط بالطبيب'
            ], 400);
        }

        $query = Notification::where('user_id', $userId);

        if ($request->has('is_read')) {
            $query->where('is_read', $request->is_read);
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }


    public function markAsRead($id)
    {
        $doctor = Auth::guard('doctor')->user();
        $userId = method_exists($doctor, 'getAttribute') ? $doctor->getAttribute('user_id') : ($doctor->user->id ?? null);

        $notification = Notification::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'الإشعار غير موجود'
            ], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد الإشعار كمقروء'
        ]);
    }


    public function unreadCount()
    {
        $doctor = Auth::user();
        $userId = method_exists($doctor, 'getAttribute') && $doctor->getAttribute('user_id')
            ? $doctor->getAttribute('user_id')
            : ($doctor->id ?? null);

        $count = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $count
        ]);
    }

    public function testCreate(Request $request)
    {
        $doctor = Auth::user();
        $title = $request->input('title', 'Test Notification');
        $body = $request->input('body', 'This is a test notification for doctor');
        $notification = (new NotificationService())->sendToDoctor($doctor, $title, $body);

        return response()->json([
            'success' => true,
            'notification' => $notification
        ], 201);
    }
}
