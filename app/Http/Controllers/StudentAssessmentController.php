<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAssessmentController extends Controller
{
    public function show($courseId, $assessmentId)
    {
        $course = Course::find($courseId);
        $assessment = CourseAssessment::find($assessmentId);
        $assessment->questions = json_decode($assessment->questions, true);

        return view('student.assessments.show', compact('course', 'assessment'));
    }

    public function attempt(Request $request, $courseId, $assessmentId)
    {
        $course = Course::find($courseId);
        $assessment = CourseAssessment::find($assessmentId);
        $assessment->questions = json_decode($assessment->questions, true);

        $questionIndex = $request->input('question_index', 0);
        $answers = $request->session()->get('answers', []);

        if ($request->isMethod('post')) {
            $answers[$questionIndex] = $request->input('answer');
            $request->session()->put('answers', $answers);

            $questionIndex++;
        }

        if ($questionIndex >= count($assessment->questions)) {
            return redirect()->route('student.courses.assessments.result', [$courseId, $assessmentId]);
        }

        $question = $assessment->questions[$questionIndex];

        return view('student.assessments.attempt', compact('course', 'assessment', 'question', 'questionIndex'));
    }

    public function result($courseId, $assessmentId)
    {
        $course = Course::find($courseId);
        $assessment = CourseAssessment::find($assessmentId);
        $assessment->questions = json_decode($assessment->questions, true);
        $answers = session('answers', []);

        $score = 0;
        foreach ($assessment->questions as $index => $question) {
            if (isset($answers[$index]) && $question['options'][$answers[$index]]['correct']) {
                $score++;
            }
        }

        $totalQuestions = count($assessment->questions);
        $percentage = ($score / $totalQuestions) * 100;

        session()->forget('answers');

        if ($percentage >= 80) {
            // Add points to leaderboard
            $user = Auth::user();
            $user->leaderboard_points += 5;
            $user->save();

            return view('student.assessments.result', [
                'course' => $course,
                'assessment' => $assessment,
                'percentage' => $percentage,
                'message' => 'Congratulations! You passed the assessment.',
                'passed' => true,
            ]);
        } else {
            return view('student.assessments.result', [
                'course' => $course,
                'assessment' => $assessment,
                'percentage' => $percentage,
                'message' => 'You did not pass. Please try again.',
                'passed' => false,
            ]);
        }
    }
}
