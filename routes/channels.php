<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

/*
 |--------------------------------------------------------------------------
 | Broadcast Channels
 |--------------------------------------------------------------------------
 |
 | Here you may register all of the event broadcasting channels that your
 | application supports. The given channel authorization callbacks are
 | used to check if an authenticated user can listen to the channel.
 |
 */

Broadcast::channel('chat', function ($user) {
    return Auth::check();
});

Broadcast::channel('presence-chat', function ($user) {
    if (Auth::check()) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});
Broadcast::channel('webrtc', function ($user) {
    return Auth::check();
});
Broadcast::channel('presence-video-channel', function($user) {
    return ['id' => $user->id, 'name' => $user->name];
});
