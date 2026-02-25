<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display all notifications.
     */
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('customer.notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead($notification_id): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($notification_id);

        $notification->markAsRead();

        return back()->with('success', 'تم تعليم الإشعار كمقروء');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()
            ->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'تم تعليم جميع الإشعارات كمقروءة');
    }

    /**
     * Delete a notification.
     */
    public function destroy($notification_id): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($notification_id);

        $notification->delete();

        return back()->with('success', 'تم حذف الإشعار');
    }

    /**
     * Delete all notifications.
     */
    public function destroyAll(): RedirectResponse
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'تم حذف جميع الإشعارات');
    }
}
