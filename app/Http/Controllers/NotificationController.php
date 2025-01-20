<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
  /**
   * Display notifications index page
   */
  public function index()
  {
    try {
      $user = Auth::guard('admin')->user();
      $notifications = $user->notifications()->paginate(10);

      return view('admin.notifications.index', compact('notifications'));
    } catch (\Exception $e) {
      Log::error('Error in notification index: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error loading notifications'
      ], 500);
    }
  }

  /**
   * Get recent notifications list
   */
  public function list()
  {
    try {
      $user = Auth::guard('admin')->user();

      if (!$user) {
        return response()->json([
          'success' => false,
          'message' => 'User not authenticated'
        ], 401);
      }

      $notifications = $user->notifications()
                            ->latest()
                            ->take(5)
                            ->get()
                            ->map(function($notification) {
                              return [
                                'id' => $notification->id,
                                'read_at' => $notification->read_at,
                                'created_at' => $notification->created_at->toISOString(),
                                'data' => array_merge($notification->data, [
                                  'user_photo' => $notification->data['user_photo'] ?? 'avatar.png',
                                  'message' => $notification->data['message'] ?? 'New notification'
                                ])
                              ];
                            });

      return response()->json([
        'success' => true,
        'notifications' => $notifications
      ]);
    } catch (\Exception $e) {
      Log::error('Error getting notifications list: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error loading notifications'
      ], 500);
    }
  }

  /**
   * Mark a notification as read
   */
  public function markAsRead($id)
  {
    try {
      $user = Auth::guard('admin')->user();

      if (!$user) {
        return response()->json([
          'success' => false,
          'message' => 'User not authenticated'
        ], 401);
      }

      $notification = $user->notifications()->where('id', $id)->first();

      if (!$notification) {
        return response()->json([
          'success' => false,
          'message' => 'Notification not found'
        ], 404);
      }

      $notification->markAsRead();

      return response()->json([
        'success' => true,
        'message' => 'Notification marked as read'
      ]);
    } catch (\Exception $e) {
      Log::error('Error marking notification as read: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error marking notification as read'
      ], 500);
    }
  }

  /**
   * Mark all notifications as read
   */
  public function markAllAsRead()
  {
    try {
      $user = Auth::guard('admin')->user();

      if (!$user) {
        return response()->json([
          'success' => false,
          'message' => 'User not authenticated'
        ], 401);
      }

      $user->unreadNotifications->markAsRead();

      return response()->json([
        'success' => true,
        'message' => 'All notifications marked as read'
      ]);
    } catch (\Exception $e) {
      Log::error('Error marking all notifications as read: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error marking all notifications as read'
      ], 500);
    }
  }

  /**
   * Get unread notification count
   */
  public function getUnreadCount()
  {
    try {
      $user = Auth::guard('admin')->user();

      if (!$user) {
        return response()->json([
          'success' => false,
          'message' => 'User not authenticated'
        ], 401);
      }

      $count = $user->unreadNotifications()->count();

      return response()->json([
        'success' => true,
        'count' => $count
      ]);
    } catch (\Exception $e) {
      Log::error('Error getting notification count: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error getting notification count'
      ], 500);
    }
  }
}
