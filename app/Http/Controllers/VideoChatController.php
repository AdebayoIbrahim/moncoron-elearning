<?php

namespace App\Http\Controllers;

use App\Events\StartVideoChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoChatController extends Controller
{
    public function index()
    {
        $routeNamePart = 'video-chat';
        return view('video-chat.index', compact('routeNamePart'));
    }

    public function callUser(Request $request)
    {
        $data['userToCall'] = $request->user_to_call;
        $data['signalData'] = $request->signal_data;
        $data['from'] = Auth::id();
        $data['type'] = 'incomingCall';

        broadcast(new StartVideoChat($data))->toOthers();
        return response()->json(['status' => 'Calling User!']);
    }

    public function acceptCall(Request $request)
    {
        $data['signal'] = $request->signal;
        $data['to'] = $request->to;
        $data['type'] = 'callAccepted';

        broadcast(new StartVideoChat($data))->toOthers();
    }

    public function sendSignal(Request $request)
    {
        $data['signal'] = $request->signal_data;
        $data['to'] = $request->to;
        $data['type'] = 'signal';

        broadcast(new StartVideoChat($data))->toOthers();
        return response()->json(['status' => 'Signal Sent!']);
    }
}
