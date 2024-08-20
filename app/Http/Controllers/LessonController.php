<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseLesson;
use App\Models\UserCourseLesson;
use App\Models\LessonAssessment;
use App\Models\UserLessonAssessment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function show($course_id, $id)
    {
        $lesson = CourseLesson::findOrFail($id);
        $studentMedia = $this->getStudentMedia($lesson->id);
        $routeNamePart = 'Lesson Details'; // Define this variable here

        // Check if the previous lesson is completed
        $previousLessonCompleted = $this->isPreviousLessonCompleted($course_id, $id);

        return view('lessons.show', compact('lesson', 'studentMedia', 'routeNamePart', 'previousLessonCompleted'));
    }

    public function uploadStudentMedia(Request $request, $course_id, $id)
    {
        $lesson = CourseLesson::findOrFail($id);
        $userCourseLesson = UserCourseLesson::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'course_id' => $lesson->course_id,
                'lesson_id' => $lesson->id,
            ]
        );

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('student_media', 'public');
            $userCourseLesson->file_path = $path;
        }

        if ($request->has('audio_data')) {
            $audioData = $request->input('audio_data');
            $decodedAudioData = $this->decodeBase64($audioData);
            if ($decodedAudioData) {
                $fileName = uniqid() . '.webm';
                $filePath = 'student_media/' . $fileName;
                Storage::disk('public')->put($filePath, $decodedAudioData);
                $userCourseLesson->audio_path = $filePath;
            }
        }

        $userCourseLesson->save();

        return redirect()->route('lessons.show', ['course_id' => $course_id, 'id' => $id])->with('success', 'Media uploaded successfully!');
    }

    private function getStudentMedia($lessonId)
    {
        return UserCourseLesson::where('lesson_id', $lessonId)->with('user')->get();
    }

    private function isPreviousLessonCompleted($course_id, $current_lesson_id)
    {
        $previousLesson = CourseLesson::where('course_id', $course_id)
            ->where('id', '<', $current_lesson_id)
            ->orderBy('id', 'desc')
            ->first();

        if ($previousLesson) {
            $progress = UserCourseLesson::where('user_id', Auth::id())
                ->where('course_id', $course_id)
                ->where('lesson_id', $previousLesson->id)
                ->where('completed', true)
                ->first();

            return $progress !== null;
        }

        return true; // If no previous lesson, return true
    }

    private function checkCourseCompletion($course_id)
    {
        $totalLessons = CourseLesson::where('course_id', $course_id)->count();
        $completedLessons = UserCourseLesson::where('user_id', Auth::id())
            ->where('course_id', $course_id)
            ->where('completed', true)
            ->count();

        if ($totalLessons == $completedLessons) {
            // Handle course completion, e.g., notify the user, award a certificate, etc.
        }
    }

    private function decodeBase64($data)
    {
        if (strpos($data, ';base64,') !== false) {
            list(, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            return base64_decode($data);
        }

        return null;
    }

    // Methods for handling assessments
    public function showAssessment($course_id, $lesson_id)
    {
        $lesson = CourseLesson::findOrFail($lesson_id);
        $assessment = LessonAssessment::where('lesson_id', $lesson_id)->first();
        $routeNamePart = 'Lesson Assessment'; // Add routeNamePart for view

        return view('lessons.assessment', compact('lesson', 'assessment', 'routeNamePart'));
    }

    public function submitAssessment(Request $request, $course_id, $lesson_id)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $lesson = CourseLesson::findOrFail($lesson_id);
        $assessment = LessonAssessment::where('lesson_id', $lesson_id)->first();
        $userAssessment = new UserLessonAssessment();
        $userAssessment->user_id = Auth::id();
        $userAssessment->lesson_id = $lesson_id;
        $userAssessment->course_id = $course_id;
        $userAssessment->answers = json_encode($request->input('answers'));
        $userAssessment->save();

        // Check if assessment is passed (assuming 70% pass mark)
        $totalQuestions = count(json_decode($assessment->questions, true));
        $correctAnswers = $this->checkAnswers($request->input('answers'), $assessment->questions);
        $score = ($correctAnswers / $totalQuestions) * 100;

        if ($score >= 70) {
            $userCourseLesson = UserCourseLesson::where([
                'user_id' => Auth::id(),
                'course_id' => $course_id,
                'lesson_id' => $lesson_id,
            ])->first();

            $userCourseLesson->completed = true;
            $userCourseLesson->save();

            $this->checkCourseCompletion($course_id);

            return redirect()->route('lessons.show', ['course_id' => $course_id, 'id' => $lesson_id])->with('success', 'Assessment passed, lesson completed!');
        } else {
            return redirect()->route('lessons.assessment', ['course_id' => $course_id, 'lesson_id' => $lesson_id])->with('error', 'Assessment failed. Please try again.');
        }
    }

    private function checkAnswers($userAnswers, $correctAnswers)
    {
        $correctAnswersArray = json_decode($correctAnswers, true);
        $correctCount = 0;

        foreach ($userAnswers as $questionId => $userAnswer) {
            if (isset($correctAnswersArray[$questionId]) && $correctAnswersArray[$questionId] == $userAnswer) {
                $correctCount++;
            }
        }

        return $correctCount;
    }

    public function completeLesson($course_id, $id)
    {
        $userCourseLesson = UserCourseLesson::where('user_id', Auth::id())
            ->where('course_id', $course_id)
            ->where('lesson_id', $id)
            ->firstOrFail();

        $userCourseLesson->completed = true;
        $userCourseLesson->save();

        $this->checkCourseCompletion($course_id);

        return redirect()->route('lessons.show', ['course_id' => $course_id, 'id' => $id])->with('success', 'Lesson completed successfully!');
    }
}
