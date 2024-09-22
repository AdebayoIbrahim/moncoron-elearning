<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\LessonAssessment;
use App\Models\UserLessonAssessment;
use App\Models\CourseLesson;
use App\Models\Course;

use App\Models\UserCourseLesson;
use App\Models\User;
use PDF;
use Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class LessonAssessmentController extends Controller
{
    public function index($courseId)
    {
        $assessments = LessonAssessment::whereHas('lesson', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();

        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('lesson_assessments.index', compact('assessments', 'courseId', 'routeNamePart'));
    }

    public function create($courseId, $lessonId)
    {
        $lesson = CourseLesson::where("lesson_number", $lessonId)->where("course_id", $courseId)->firstOrFail();
        // $routeName = Route::currentRouteName();
        // $routeNamePart = ucfirst(last(explode('.', $routeName)));
        $routeNamePart = "AssessmentCreate";

        return view('lesson_assessments.create', compact('courseId', 'lessonId', 'routeNamePart'));
    }

    public function store(Request $request, $courseId, $lessonId)
    {
        // Find the lesson by its ID
        $lesson = CourseLesson::findOrFail($lessonId);

        // Validate the request data
        $request->validate([
            'questions' => 'required|array',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array',
            'questions.*.options.*' => 'required|string',
            'questions.*.correct_option' => 'required|string',
            'questions.*.value' => 'required|numeric',
            'questions.*.media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp3,mp4,avi',
            'time_limit' => 'required|integer'
        ]);

        // Process the questions and handle media uploads
        $questions = $request->questions;

        foreach ($questions as $index => $question) {
            if (isset($question['media'])) {
                foreach ($question['media'] as $mediaIndex => $media) {
                    $path = $media->store('question_media');
                    $questions[$index]['media'][$mediaIndex] = $path;
                }
            }
        }

        // Save the lesson assessment
        LessonAssessment::create([
            'lesson_id' => $lessonId,
            'questions' => json_encode($questions),
            'time_limit' => $request->time_limit
        ]);

        // Redirect to the lesson's chat index or lesson detail
        return redirect()->route('chat.index', ['course_id' => $courseId, 'lesson_id' => $lessonId])
            ->with('success', 'Assessment created successfully.');
    }
    public function show($courseId, $lessonId)
    {
        $assessment = LessonAssessment::where('lesson_id', $lessonId)->firstOrFail();
        $lesson = CourseLesson::findOrFail($lessonId);
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('student.lesson_assessment', compact('assessment', 'lesson', 'courseId', 'lessonId', 'routeNamePart'));
    }

    public function submitAssessment(Request $request, $courseId, $lessonId)
    {
        $assessment = LessonAssessment::where('lesson_id', $lessonId)->firstOrFail();

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
            'time_taken' => 'required|integer'
        ]);

        if ($request->time_taken > $assessment->time_limit) {
            return redirect()->route('lessons.assessment', ['courseId' => $courseId, 'lessonId' => $lessonId])
                ->with('error', 'You have exceeded the time limit for this assessment.');
        }

        $totalScore = 0;
        $maxScore = 0;
        $questions = json_decode($assessment->questions, true);

        foreach ($questions as $index => $question) {
            $value = $question['value'];
            $maxScore += $value;
            if ($request->answers[$index] == $question['correct_option']) {
                $totalScore += $value;
            }
        }

        $finalScore = ($totalScore / $maxScore) * 100;

        UserLessonAssessment::create([
            'user_id' => auth()->id(),
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'answers' => json_encode($request->answers),
            'score' => $finalScore,
            'time_taken' => $request->time_taken
        ]);

        return redirect()->route('student.assessments.result', ['courseId' => $courseId, 'lessonId' => $lessonId])
            ->with('success', 'Assessment submitted and graded.');
    }

    public function showAssessmentResult($courseId, $lessonId)
    {
        // Fetch the latest assessment result for the current user
        $userAssessment = UserLessonAssessment::where('user_id', auth()->id())
            ->where('course_id', $courseId)
            ->where('lesson_id', $lessonId)
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        // Fetch the current lesson and the next lesson
        $lesson = CourseLesson::findOrFail($lessonId);
        $nextLesson = CourseLesson::where('course_id', $courseId)
            ->where('id', '>', $lessonId)
            ->orderBy('id', 'asc')
            ->first();

        // Check if all lessons have been completed
        $allLessonsCompleted = $nextLesson ? false : true;

        // Define the current route name part for use in the view
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));

        // Set routes for navigation
        $nextLessonRoute = $nextLesson ? route('student.lessons.show', ['courseId' => $courseId, 'lessonId' => $nextLesson->id]) : null;
        $currentLessonRoute = route('student.chat.index', ['courseId' => $courseId, 'lessonId' => $lessonId]);
        $retakeLessonRoute = route('student.lessons.show', ['courseId' => $courseId, 'lessonId' => $lessonId]);
        $retakeAssessmentRoute = route('student.assessments.take', ['courseId' => $courseId, 'lessonId' => $lessonId]);

        // Add certificate route if needed
        $certificateRoute = $allLessonsCompleted ? route('student.certificates.download', ['courseId' => $courseId]) : null;

        // Check if the student passed the assessment
        $assessmentPassed = $userAssessment->score >= 70;

        // Return the view with the necessary data
        return view('student.assessment_result', compact(
            'userAssessment',
            'lesson',
            'nextLesson',
            'assessmentPassed',
            'allLessonsCompleted',
            'routeNamePart',
            'courseId',
            'lessonId',
            'nextLessonRoute',
            'currentLessonRoute',
            'retakeLessonRoute',
            'retakeAssessmentRoute',
            'certificateRoute'
        ));
    }

    private function generateCertificate(User $user, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $existingCertificate = FacadesStorage::exists('certificates/' . $user->id . '/' . $course->id . '.pdf');

        if (!$existingCertificate) {
            $certificate = PDF::loadView('certificates.completion', ['user' => $user, 'course' => $course]);
            FacadesStorage::put('certificates/' . $user->id . '/' . $course->id . '.pdf', $certificate->output());
        }
    }

    public function destroy($courseId, $id)
    {
        $assessment = LessonAssessment::findOrFail($id);
        $assessment->delete();

        return redirect()->route('lesson_assessments.index', $courseId)
            ->with('success', 'Assessment deleted successfully.');
    }

    public function viewAssessmentsByLesson($course_id, $lesson_id)
    {
        $assessments = LessonAssessment::where('lesson_id', $lesson_id)
            ->whereHas('lesson', function ($query) use ($course_id) {
                $query->where('course_id', $course_id);
            })
            ->get();

        $routeNamePart = 'Assessments for Lesson';

        return view('lesson_assessments.view_by_lesson', compact('assessments', 'course_id', 'lesson_id', 'routeNamePart'));
    }

    public function viewAssessmentsByCourse($course_id)
    {
        $assessments = LessonAssessment::whereHas('lesson', function ($query) use ($course_id) {
            $query->where('course_id', $course_id);
        })
            ->get();

        $routeNamePart = 'Assessments for Course';

        return view('lesson_assessments.view_by_course', compact('assessments', 'course_id', 'routeNamePart'));
    }

    public function showStudentAssessment($courseId, $lessonId)
    {
        $lesson = CourseLesson::findOrFail($lessonId);
        $assessment = LessonAssessment::where('lesson_id', $lessonId)->first();

        if (!$assessment) {
            return redirect()->back()->with('error', 'No assessment found for this lesson.');
        }

        // Check if the student has already passed the assessment
        $existingAssessment = UserLessonAssessment::where('user_id', auth()->id())
            ->where('course_id', $courseId)
            ->where('lesson_id', $lessonId)
            ->orderBy('created_at', 'desc')
            ->first();

        // If the student has already passed, redirect them to the result page
        if ($existingAssessment && $existingAssessment->score >= 70) {
            return redirect()->route('student.assessments.result', ['courseId' => $courseId, 'lessonId' => $lessonId])
                ->with('warning', 'You have already passed this assessment. Proceed to the next lesson.');
        }

        // If they have not passed, show the assessment
        return view('student.assessment_take', compact('lesson', 'assessment', 'courseId', 'lessonId'));
    }


    public function submitStudentAssessment(Request $request, $courseId, $lessonId)
    {
        $assessment = LessonAssessment::where('lesson_id', $lessonId)->firstOrFail();

        $existingAssessment = UserLessonAssessment::where('user_id', auth()->id())
            ->where('course_id', $courseId)
            ->where('lesson_id', $lessonId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($existingAssessment && $existingAssessment->score >= 70) {
            return redirect()->route('student.assessments.result', ['courseId' => $courseId, 'lessonId' => $lessonId])
                ->with('warning', 'You have already passed this assessment. Proceed to the next lesson.');
        }

        $request->validate([
            'answers' => 'required|array',
            'time_taken' => 'required|integer'
        ]);

        $totalScore = 0;
        $maxScore = 0;
        $questions = json_decode($assessment->questions, true);

        if (!$questions || empty($questions)) {
            return redirect()->back()->with('error', 'There was an issue loading the questions. Please try again.');
        }

        foreach ($questions as $index => $question) {
            $value = $question['value'] ?? 0;
            $correctOption = $question['correct_option'] ?? null;
            $maxScore += $value;

            if (!$correctOption) {
                return redirect()->back()->with('error', 'A correct option is missing for one of the questions.');
            }

            if (isset($request->answers[$index]) && $request->answers[$index] == $correctOption) {
                $totalScore += $value;
            }
        }

        $finalScore = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;

        UserLessonAssessment::create([
            'user_id' => auth()->id(),
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'answers' => json_encode($request->answers),
            'score' => $finalScore,
            'time_taken' => $request->time_taken
        ]);

        return redirect()->route('student.assessments.result', ['courseId' => $courseId, 'lessonId' => $lessonId])
            ->with('success', 'Assessment submitted and graded.');
    }

    public function edit($courseId, $lessonId, $id)
    {
        $lesson = CourseLesson::findOrFail($lessonId);
        $assessment = LessonAssessment::findOrFail($id);

        return view('lesson_assessments.edit', compact('lesson', 'assessment', 'courseId', 'lessonId'));
    }

    public function manageAssessments()
    {
        $assessments = LessonAssessment::with('lesson.course')->get();

        // Extract the route name part for consistency
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.manage_assessments', compact('assessments', 'routeNamePart'));
    }

    // Publish assessment
    public function publishAssessment($id)
    {
        $assessment = LessonAssessment::findOrFail($id);
        $assessment->is_published = true;
        $assessment->save();

        return redirect()->back()->with('success', 'Assessment published successfully.');
    }

    // Unpublish assessment
    public function unpublishAssessment($id)
    {
        $assessment = LessonAssessment::findOrFail($id);
        $assessment->is_published = false;
        $assessment->save();

        return redirect()->back()->with('success', 'Assessment unpublished successfully.');
    }

    // Delete assessment with confirmation
    public function deleteAssessmentWithConfirmation(Request $request, $id)
    {
        if ($request->input('confirm') === 'yes') {
            $assessment = LessonAssessment::findOrFail($id);
            $assessment->delete();

            return redirect()->back()->with('success', 'Assessment deleted successfully.');
        }

        return redirect()->back()->with('error', 'Assessment deletion cancelled.');
    }
}
