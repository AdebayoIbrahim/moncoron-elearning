<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LessonAssessmentNew;
use Illuminate\Support\Facades\Storage;

class LessonsAssessmentsNew extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'courseId' => 'required|exists:courses,id',
            'lessonId' => 'required|exists:lessons,id',
            'general_time_limit' => 'nullable|integer',
            'questions' => 'required|array',
        ]);

        // Extract courseId and lessonId from query parameters
        $courseId = $request->query('courseId');
        $lessonId = $request->query('lessonId');

        // Handle file uploads
        $questionsData = $validated['questions'];
        foreach ($questionsData as &$question) {
            foreach ($question['options'] as &$option) {
                $option['media']['image_path'] = $this->handleFileUpload($request->file('option_images')[$option['id']] ?? null, 'images');
                $option['media']['audio_path'] = $this->handleFileUpload($request->file('option_audio')[$option['id']] ?? null, 'audio');
                $option['media']['video_path'] = $this->handleFileUpload($request->file('option_video')[$option['id']] ?? null, 'video');
            }
            $question['media']['image_path'] = $this->handleFileUpload($request->file('question_images')[$question['id']] ?? null, 'images');
            $question['media']['audio_path'] = $this->handleFileUpload($request->file('question_audio')[$question['id']] ?? null, 'audio');
            $question['media']['video_path'] = $this->handleFileUpload($request->file('question_video')[$question['id']] ?? null, 'video');
        }

        // Create a new LessonAssessmentNew record
        $assessment = LessonAssessmentNew::create([
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'questions' => $questionsData,
        ]);

        return response()->json($assessment, 201);
    }

    private function handleFileUpload($file, $type)
    {
        if ($file) {
            $path = $file->store('uploads/' . $type, 'public');
            return $path;
        }
        return null;
    }
}