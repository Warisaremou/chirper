<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->noContent();
    }

    public function markAsRead(string $id)
    {
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
        return response()->noContent();
    }

    public function subscribe(Request $request)
    {
        [$endpoint, $key, $token, $contentEncoding] = [
            $request->input('endpoint'),
            $request->input('key'),
            $request->input('token'),
            $request->input('encoding')
        ];

        auth()->user()->updatePushSubscription($endpoint, $key, $token, $contentEncoding);
    }

    public function unsubscribe(Request $request)
    {
        auth()->user()->deletePushSubscription($request->input('endpoint'));
    }
}
