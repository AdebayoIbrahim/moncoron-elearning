<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LessonAssessment;
use App\Models\UserLessonAssessment;
use App\Models\CourseLesson;

class LessonAssessmentController extends Controller
{
    public function index($courseId)
    {
        $assessments = LessonAssessment::whereHas('lesson', function($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();

        return view('lesson_assessments.index', compact('assessments', 'courseId'));
    }

    public function create($courseId, $lessonId)
    {
        return view('lesson_assessments.create', compact('courseId', 'lessonId'));
    }

    public function store(Request $request, $courseId, $lessonId)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array',
            'questions.*.options.*' => 'required|string',
            'questions.*.answer' => 'required|string',
        ]);

        LessonAssessment::create([
            'lesson_id' => $lessonId,
            'questions' => json_encode($request->questions),
        ]);

        return redirect()->route('lessons.show', ['course_id' => $courseId, 'id' => $lessonId])->with('success', 'Assessment created successfully.');
    }

    public function show($courseId, $lessonId)
    {
        $assessment = LessonAssessment::where('lesson_id', $lessonId)->first();
        if (!$assessment) {
            return redirect()->route('lessons.show', ['course_id' => $courseId, 'id' => $lessonId])->with('error', 'Assessment not found.');
        }
        return view('lesson_assessments.show', compact('assessment', 'courseId', 'lessonId'));
    }

    public function submitAssessment(Request $request, $courseId, $lessonId)
    {
        $assessment = LessonAssessment::where('lesson_id', $lessonId)->firstOrFail();

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
        ]);

        UserLessonAssessment::create([
            'user_id' => auth()->id(),
            'course_id' => $assessment->lesson->course_id,
            'lesson_id' => $lessonId,
            'answers' => json_encode($request->answers),
        ]);

        return redirect()->route('lessons.show', ['course_id' => $courseId, 'id' => $lessonId])->with('success', 'Assessment submitted successfully.');
    }

    public function destroy($courseId, $id)
    {
        $assessment = LessonAssessment::findOrFail($id);
        $assessment->delete();

        return redirect()->route('lesson_assessments.index', $courseId)->with('success', 'Assessment deleted successfully.');
    }
}
