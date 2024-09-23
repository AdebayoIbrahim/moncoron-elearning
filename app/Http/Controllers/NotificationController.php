<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->unreadNotifications;

        return view('notifications.index', compact('notifications'));
    }

    // mark-all-as-read
    public function markAllasRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['Marked as read succssful'], 200);
    }
}
