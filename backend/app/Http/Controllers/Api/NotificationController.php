<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $notifications = Notification::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('role', $user->role);
            })
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->count()
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        
        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }

    public function createSystemNotification(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
            'type' => ['required', 'in:info,warning,error,success'],
            'target_role' => ['nullable', 'in:dean,faculty,student,all'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $notificationData = [
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
        ];

        if ($request->target_role === 'all') {
            // Send to all users
            $users = User::pluck('id');
            foreach ($users as $userId) {
                Notification::create(array_merge($notificationData, [
                    'user_id' => $userId,
                ]));
            }
        } elseif ($request->target_role) {
            // Send to specific role
            $users = User::where('role', $request->target_role)->pluck('id');
            foreach ($users as $userId) {
                Notification::create(array_merge($notificationData, [
                    'user_id' => $userId,
                ]));
            }
        } else {
            // Send to specific user
            Notification::create(array_merge($notificationData, [
                'user_id' => $request->user_id,
            ]));
        }

        Audit::log($request->user(), 'create_notification', [
            'title' => $request->title,
            'target_role' => $request->target_role ?? 'specific'
        ]);

        return response()->json([
            'message' => 'Notification created successfully'
        ], 201);
    }

    public function getUnreadCount(Request $request)
    {
        $user = $request->user();
        
        $count = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'unread_count' => $count
        ]);
    }

    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully'
        ]);
    }

    public function getSystemNotifications(Request $request)
    {
        $notifications = Notification::where(function ($query) use ($request) {
                $query->whereNull('user_id') // System-wide notifications
                      ->orWhere('role', $request->user()->role);
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'system_notifications' => $notifications
        ]);
    }
}
