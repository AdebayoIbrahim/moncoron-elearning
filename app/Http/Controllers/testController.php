<?php


// app/Http/Controllers/TestController.php
namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\User;
use App\Models\Message;

class TestController extends Controller
{
    public function sendMessage()
    {
        $user = User::first(); // or however you get a user
        $message = new Message(['content' => 'Hello World']);
        event(new MessageSent($user, $message));
        return response()->json(['status' => 'Broadcast sent!']);
    }
}
