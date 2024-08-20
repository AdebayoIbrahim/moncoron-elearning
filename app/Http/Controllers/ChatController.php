<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\CourseLesson;
use App\Models\UserCourseLesson;
use App\Models\LessonAssessment;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index($course_id, $lesson_id)
{
    // Retrieve the lesson content
    $lesson = CourseLesson::where('course_id', $course_id)->findOrFail($lesson_id);

    // Check if the user has viewed the lesson
    $userCourseLesson = UserCourseLesson::where('course_id', $course_id)
        ->where('lesson_id', $lesson_id)
        ->where('user_id', Auth::id())
        ->first();

    // Retrieve the messages
    $messages = Message::with('user')
        ->where('course_id', $course_id)
        ->where('lesson_id', $lesson_id)
        ->where('user_id', Auth::id()) // Filter for messages by the current user
        ->get();

    // Check if the user has sent any messages
    $hasSentMessages = $messages->isNotEmpty();

    // Retrieve the assessment for the lesson
    $assessment = LessonAssessment::where('lesson_id', $lesson_id)->first();

    // Determine if the assessment should be unlocked
    $isAssessmentUnlocked = $userCourseLesson && $hasSentMessages && $assessment;

    $routeName = Route::currentRouteName();
    $routeNamePart = ucfirst(last(explode('.', $routeName)));

    return view('chat.index', compact('lesson', 'userCourseLesson', 'messages', 'assessment', 'isAssessmentUnlocked', 'routeNamePart', 'course_id', 'lesson_id'));
}


public function sendMessage(Request $request, $course_id, $lesson_id)
{
    $user = Auth::user();

    // Set a default message if it's empty and audio is present
    $messageText = $request->input('message');
    if (!$messageText && $request->has('audio_data')) {
        $messageText = 'Audio Message';
    }

    $messageData = [
        'message' => $messageText,
        'course_id' => $course_id,
        'lesson_id' => $lesson_id
    ];

    // Handle file uploads
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('uploads', 'public');

        $fileType = $file->getMimeType();
        if (strstr($fileType, 'image/')) {
            $fileType = 'image';
        } elseif (strstr($fileType, 'video/')) {
            $fileType = 'video';
        } elseif (strstr($fileType, 'audio/')) {
            $fileType = 'audio';
        } else {
            $fileType = 'file';
        }

        $messageData['file_path'] = $filePath;
        $messageData['file_type'] = $fileType;
    }

    // Handle Base64-encoded audio data
    if ($request->has('audio_data') && !empty($request->input('audio_data'))) {
    
        $audioData = $request->input('audio_data');

      
        $audioData = preg_replace('#^data:audio/\w+;base64,#i', '', $audioData);

        // Decode the Base64 data
        $audioBinary = base64_decode($audioData);


        $fileName = uniqid() . '.webm';
        $filePath = 'chat_files/' . $fileName;

        Storage::disk('public')->put($filePath, $audioBinary);

  
        $messageData['file_path'] = $filePath;
        $messageData['file_type'] = 'audio';
    }

    // Create the message
    $message = $user->messages()->create($messageData);

    // Broadcast the message
    broadcast(new MessageSent($user, $message))->toOthers();

    // Notify users (if needed)
    $this->notifyUsers($course_id, $lesson_id, $message);

    return response()->noContent();
}



    private function notifyUsers($course_id, $lesson_id, $message)
    {
        $lesson = CourseLesson::where('course_id', $course_id)->findOrFail($lesson_id);

        // Notify lecturers and teachers
        $users = User::whereIn('id', $lesson->course->lecturers()->pluck('id')->merge($lesson->course->teachers()->pluck('id'))->all())->get();

        foreach ($users as $user) {
            $user->notify(new NewMessageNotification($message));
        }
    }

    public function sendCallSignal(Request $request, $course_id, $lesson_id)
    {
        $user = Auth::user();
        $signalData = $request->input('signal_data');
        $callType = $request->input('call_type');

        broadcast(new MessageSent($user, new Message(), $callType, $signalData))->toOthers();

        return response()->json(['status' => 'Call Signal Sent!']);
    }

    public function deleteMessage(Request $request, $courseId, $lessonId)
    {
        $messageId = $request->input('message_id');
        $message = Message::findOrFail($messageId);

        // Check if the user is an admin or the owner of the message
        if (Auth::user()->isAdmin() || Auth::id() == $message->user_id) {
            // Delete the associated file if it exists
            if ($message->file_path) {
                $filePath = public_path('storage/' . $message->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file from the filesystem
                }
            }

            $message->delete(); // Delete the message from the database

            return response()->json(['status' => 'Message deleted successfully']);
        } else {
            return response()->json(['status' => 'Unauthorized'], 403);
        }
    }
}
