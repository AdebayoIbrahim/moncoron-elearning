<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\CourseLesson;

class ChatmessagesController extends Controller
{
    public function fetchMessages($courseId, $lessonId)
    {
        // Ensure the lesson exists and belongs to the given course
        $lesson = CourseLesson::where('lesson_number', $lessonId)->where('course_id', $courseId) ->firstOrFail();
        
        
        // Fetch messages associated with the lesson
        $messages = ChatMessage::where('course_id', $courseId) ->where('lesson_number', $lesson->lesson_number)->with('user')->get();

        
        return response()->json($messages);
    }

    public function sendMessage(Request $request, $courseId, $lessonId)
    {
        $request->validate([
            'message' => 'nullable|string',
            'audio' => 'nullable|file|mimes: mp3,webm,wav'
        ]);

        // Ensure the lesson exists and belongs to the given course
        $lesson = CourseLesson::where('lesson_number', $lessonId)->where('course_id', $courseId)->firstOrFail();
        $audiopath = $request->hasFile('audio') ? $request->file('audio')->store("lessons/audichats","public") : null;

        


        // Create and save a new chat message
        $message = ChatMessage::create([
            'user_id' => auth()->id(),
            'course_id' => $courseId,
            'lesson_number' => $lesson->lesson_number, 
            'message' => $request->message ?? null,
            'audio' => $audiopath
        ]);

        return response()->json($message);
    }
}