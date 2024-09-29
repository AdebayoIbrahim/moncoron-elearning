<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Course_Lessons;
use App\Models\Subscriptions;
use App\Models\UserCourseLesson;
use App\Models\CourseAssessment;
use App\Models\CourseAssessmentSubmission;
use App\Models\CourseLesson;
use App\Models\Lessonassessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Unicodeveloper\Paystack\Facades\Paystack;

class MainController extends Controller
{
    // Student Dashboard
    public function dashboard()
    {
        $user = Auth::user();
        $courses = Course::all();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName))) ?: 'Dashboard';
        $course = '';

        return view('student.dashboard', compact('user', 'routeNamePart', 'courses', 'course'));
    }

    // Student Courses
    public function courses()
    {
        $user = Auth::user();
        // scope-general-courses
        $courses = Course::normal()->get();
        $routeName = Route::currentRouteName();
        $mycourses = Course::whereIn('id', $user->subscriptions->pluck('course_id'))->get();
        $routeNamePart = ucfirst(last(explode('.', $routeName))) ?: 'Courses';

        return view('student.courses', compact('user', 'routeNamePart', 'courses', 'mycourses'));
    }
    // Show Course Details
    public function showcourse($courseid)
    {
        // Find the course by its ID
        $course = Course::find($courseid);
        //   check-if-lesson-exist

        $routeName = "CourseView";
        // Check if the course exists
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        };

        // fetch-related-lessons
        $lessons = $course->lessons;
        // Return the Blade view and pass the course data to it
        return view('student.courseview', ['course' => $course, 'lessons' => $lessons, 'routeNamePart' => $routeName]);
    }

    // showlessons-in a specific-course
    public function showlessons($courseid, $lessonid)
    {
        // checking-coursexistence-not-needed-sinceitshabdled-bymiddleware-andwouldgotonotfoundifso
        //andnochecks-foruser-type-sinceof premium-sinceits hadled in middleware

        $course = Course::find($courseid);
        // check-for-lessons
        $checklesson = Lessonassessment::where("course_id", $courseid)->where("lesson_id", $lessonid)->first();

        $hasasessment = $checklesson ? true : false;
        // lesson-validate-the-id
        $lesson = CourseLesson::where('course_id', $courseid)->where('lesson_number', $lessonid)->first();

        if (!$lesson) {
            return response()->json(['error' => 'Lesson not found'], 404);
        }

        // continue-if-all-is-well
        return view('student.lessonview', ['course' => $course, 'lesson' => $lesson, 'routeNamePart' => 'LessonView', 'hasassessment' => $hasasessment]);
    }

    // Update Lesson Progress
    public function updateProgress(Request $request)
    {
        $user = Auth::user();
        $lessonId = $request->lesson_id;
        $courseId = $request->course_id;

        UserCourseLesson::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $courseId,
                'lesson_id' => $lessonId
            ],
            [
                'completed' => true
            ]
        );

        return response()->json(['success' => true]);
    }

    // Handle Payment
    public function handlePayment(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|string',
            'email' => 'required|email',
            'amount' => 'required|numeric',
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        $existingSubscription = Subscriptions::where('student_id', auth()->id())
            ->where('course_id', $validated['course_id'])
            ->first();

        if ($existingSubscription) {
            return response()->json(['status' => false, 'message' => 'You are already enrolled in this course.']);
        }

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', 'https://api.paystack.co/transaction/verify/' . $validated['reference'], [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
                ],
                'verify' => false,
            ]);

            $paymentDetails = json_decode($response->getBody()->getContents(), true);

            if ($paymentDetails['status'] && $paymentDetails['data']['status'] === 'success') {
                Subscriptions::create([
                    'student_id' => auth()->id(),
                    'course_id' => $validated['course_id'],
                    'active' => 1,
                    'expires_at' => now()->addMonths(1),
                    'amount' => $validated['amount'],
                    'reference' => $validated['reference']
                ]);

                return response()->json(['status' => true, 'message' => 'Payment successful']);
            } else {
                return response()->json(['status' => false, 'message' => 'Payment verification failed']);
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return response()->json(['status' => false, 'message' => 'Payment verification failed', 'error' => $e->getMessage()]);
        }
    }

    // Handle Gateway Callback
    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();
        if ($paymentDetails['status'] && $paymentDetails['data']['status'] === 'success') {
            Subscriptions::create([
                'student_id' => auth()->id(),
                'course_id' => request()->input('course_id'),
                'active' => 1,
                'expires_at' => now()->addMonths(1)
            ]);

            return redirect()->route('courses')->with('success', 'Payment successful!');
        } else {
            return redirect()->route('courses')->with('error', 'Payment verification failed.');
        }
    }

    // Student Profile
    public function profile()
    {
        $user = Auth::user();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName))) ?: 'Profile';
        $course = '';

        return view('student.profile', compact('user', 'routeNamePart', 'course'));
    }

    // Assessments
    public function assessments(Course $course)
    {
        $user = Auth::user();
        if (!$user->courses->contains($course->id)) {
            return redirect()->route('student.courses')->with('error', 'You are not registered for this course.');
        }

        // Check if the student has completed all lessons
        $completedLessons = UserCourseLesson::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->count();

        // Ensure lessons is treated as an array
        $lessons = is_array($course->lessons) ? $course->lessons : json_decode($course->lessons, true);

        if ($completedLessons !== count($lessons)) {
            return redirect()->route('student.courses')->with('error', 'You must complete all lessons before taking the assessment.');
        }

        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName))) ?: 'Assessments';
        $assessments = json_decode($course->assessments, true);

        return view('student.assessments.index', compact('course', 'assessments', 'routeNamePart'));
    }

    // Show Assessment
    // public function showAssessment(Course $course, CourseAssessment $assessment)
    // {
    //     $user = Auth::user();
    //     if (!$user->courses->contains($course->id)) {
    //         return redirect()->route('student.courses')->with('error', 'You are not registered for this course.');
    //     }

    //     $assessment->questions = json_decode($assessment->questions, true);  // Ensure questions are decoded to an array

    //     $routeName = Route::currentRouteName();
    //     $routeNamePart = ucfirst(last(explode('.', $routeName))) ?: 'Assessment';

    //     return view('student.assessments.attempt', compact('course', 'assessment', 'routeNamePart'));
    // }

    // student-takes-assessments
    public function takeAssessment($course_id, $lessonid)
    {
        $Lessonpresent = CourseLesson::where('lesson_number', $lessonid);

        if (!$Lessonpresent) {
            return redirect('/courses')->with('error', 'Lesson-not-found');
        }

        // fetch-assessment-questions
        $questionsexist = Lessonassessment::where('lesson_id', $lessonid)->first();
        if (!$questionsexist) {
            return redirect('/courses')->with('error', 'Assessment not ready for this course');
        }

        $Questions = $questionsexist->questions;

        return view('student.assessmenttake', compact('Questions'));
    }











    // Attempt Assessment
    public function attemptAssessment(Request $request, Course $course, CourseAssessment $assessment)
    {
        $user = Auth::user();
        if (!$user->courses->contains($course->id)) {
            return redirect()->route('student.courses')->with('error', 'You are not registered for this course.');
        }

        $assessment->questions = json_decode($assessment->questions, true);
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName))) ?: 'Assessment';

        // Retrieve previous attempts
        $previousAttempts = CourseAssessmentSubmission::where('user_id', $user->id)
            ->where('course_assessment_id', $assessment->id)
            ->count();

        if ($previousAttempts >= 3) {
            return redirect()->route('student.courses.assessments', $course->id)
                ->with('error', 'You have reached the maximum number of attempts for this assessment.');
        }

        return view('student.assessments.attempt', compact('course', 'assessment', 'routeNamePart', 'previousAttempts'));
    }

    // Submit Assessment
    public function submitAssessment(Request $request, Course $course, CourseAssessment $assessment)
    {
        $validatedData = $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|integer',
        ]);

        $user = Auth::user();
        $answers = $validatedData['questions'];
        $score = 0;

        // Ensure questions is treated as an array
        $questions = is_array($assessment->questions) ? $assessment->questions : json_decode($assessment->questions, true);

        if (!is_array($questions)) {
            return redirect()->route('student.courses.assessments', $course->id)
                ->with('error', 'There was an issue with the assessment format. Please contact support.');
        }

        $totalQuestions = count($questions);

        foreach ($questions as $questionIndex => $question) {
            if (!isset($answers[$questionIndex])) {
                continue;
            }

            $selectedOptionIndex = $answers[$questionIndex];

            if (isset($question['options'][$selectedOptionIndex]['correct']) && $question['options'][$selectedOptionIndex]['correct']) {
                $score += 2; // Each correct answer gives 2 points
            }
        }

        $percentageScore = ($score / ($totalQuestions * 2)) * 100;

        CourseAssessmentSubmission::create([
            'user_id' => $user->id,
            'course_assessment_id' => $assessment->id,
            'answers' => json_encode($answers),
            'score' => $score,
        ]);

        if ($percentageScore >= 70) {
            // Award leaderboard points
            $user->increment('leaderboard_points', 5);

            // Generate certificate
            $this->generateCertificate($user, $course);
        }

        return redirect()->route('student.courses.assessments', $course->id)
            ->with('success', "Assessment submitted successfully. You scored {$percentageScore}%");
    }
}
