<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Option;
use App\Models\GeneralSetting;

class LessonAssessmentsController extends Controller
{
    public function store(Request $request)
    {
        // Retrieve course and lesson IDs from query params
        $courseId = $request->query('courseId');
        $lessonId = $request->query('lessonId');

        // Validate the incoming data
        $request->validate([
            'general_time_limit' => 'nullable|integer',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.points' => 'nullable|integer',
            'questions.*.media' => 'array',
            'questions.*.media.image_path' => 'nullable|string',
            'questions.*.media.audio_path' => 'nullable|string',
            'questions.*.media.video_path' => 'nullable|string',
            'questions.*.options' => 'required|array',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.options.*.media' => 'array',
            'questions.*.options.*.media.image_path' => 'nullable|string',
            'questions.*.options.*.media.audio_path' => 'nullable|string',
            'questions.*.options.*.is_correct' => 'required|boolean',
        ]);

        // Store or update general settings (time limit)
        GeneralSetting::updateOrCreate(
            ['id' => 1], 
            ['time_limit' => $request->general_time_limit]
        );

        // Process each question
        foreach ($request->questions as $questionData) {
            $question = Question::create([
                'question_text' => $questionData['question_text'],
                'points' => $questionData['points'] ?? null,
                'image_path' => $questionData['media']['image_path'] ?? null,
                'audio_path' => $questionData['media']['audio_path'] ?? null,
                'video_path' => $questionData['media']['video_path'] ?? null,
                'course_id' => $courseId,
                'lesson_id' => $lessonId,
            ]);

            // Process each option for the question
            foreach ($questionData['options'] as $optionData) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['option_text'],
                    'image_path' => $optionData['media']['image_path'] ?? null,
                    'audio_path' => $optionData['media']['audio_path'] ?? null,
                    'is_correct' => $optionData['is_correct'],
                ]);
            }
        }

        return response()->json(['message' => 'Questions and options saved successfully.'], 200);
    }
}