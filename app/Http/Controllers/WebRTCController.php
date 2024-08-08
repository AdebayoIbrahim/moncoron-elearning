<?php


// app/Http/Controllers/WebRTCController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\OfferReceived;
use App\Events\AnswerReceived;
use App\Events\IceCandidateReceived;

class WebRTCController extends Controller
{
    public function sendOffer(Request $request)
    {
        broadcast(new OfferReceived($request->offer));
        return response()->json(['status' => 'Offer sent!']);
    }

    public function sendAnswer(Request $request)
    {
        broadcast(new AnswerReceived($request->answer));
        return response()->json(['status' => 'Answer sent!']);
    }

    public function sendIceCandidate(Request $request)
    {
        broadcast(new IceCandidateReceived($request->candidate));
        return response()->json(['status' => 'ICE candidate sent!']);
    }
}
