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
            'message' => 'required|string',
        ]);

        // Ensure the lesson exists and belongs to the given course
        $lesson = CourseLesson::where('lesson_number', $lessonId)->where('course_id', $courseId)->firstOrFail();

        // Create and save a new chat message
        $message = ChatMessage::create([
            'user_id' => auth()->id(),
            'course_id' => $courseId,
            'lesson_number' => $lesson->lesson_number, 
            'message' => $request->message,
        ]);

        return response()->json($message);
    }
}