<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Agora\src\RtcTokenBuilder2;

class AgoraController extends Controller
{
    public function generateToken(Request $request)
    {
        $appId = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channel_name;
        $uid = rand(1, 10000); // Random UID
        $role = RtcTokenBuilder2::ROLE_PUBLISHER;  // User can be publisher or subscriber
        $expireTimeInSeconds = 3600;  // Token valid for 1 hour
        $currentTimestamp = now()->timestamp;
        $privilegeExpireTime = $currentTimestamp + $expireTimeInSeconds;

        // Generate the token
        $token = RtcTokenBuilder2::buildTokenWithUid($appId, $appCertificate, $channelName, $uid, $role, $privilegeExpireTime);

        return response()->json(['token' => $token, 'uid' => $uid]);
    }
}
