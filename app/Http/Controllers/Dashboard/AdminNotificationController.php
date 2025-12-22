<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{

    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $userId = method_exists($admin, 'getAttribute') ? $admin->getAttribute('user_id') : ($admin->user->id ?? null);
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد مستخدم مرتبط بالمشرف'
            ], 400);
        }

        $query = Notification::where('user_id', $userId);

        if ($request->has('type')) {
            $query->where('type', $request->type);
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
        $admin = Auth::guard('admin')->user();
        $userId = method_exists($admin, 'getAttribute') ? $admin->getAttribute('user_id') : ($admin->user->id ?? null);

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
}
