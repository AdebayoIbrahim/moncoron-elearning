<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\Course;
use App\Models\Course_Lessons;
use App\Models\Subscriptions;
use App\Models\UserCourseLesson;
use App\Models\CourseAssessment;
use App\Models\CourseAssessmentSubmission;
use App\Models\CourseLesson;
use App\Models\Lessonassessment;
use App\Models\lessonassessmentresults;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Unicodeveloper\Paystack\Facades\Paystack;
use Illuminate\Support\Facades\Log;


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

        // morechecks-for-completition-or-not
        // $newlessonchecks = lessonassessmentresults::where('course_id')
        $usrid = auth()->user()->id;
        $newlessonslist = User::find($usrid)->userAssessmentresult;



        // fetch-related-lessons
        $lessons = $course->lessons;
        if (!$newlessonslist) {
            foreach ($lessons as  $lesson) {
                if ($lesson->lesson_number === 1) {

                    $lesson->is_accessible = true;
                } else {
                    $lesson->is_accessible = false;
                }
            }
        } else {
            // if_asessment_already-exist-get_thelastorgreatest-id
            $max_lesson = lessonassessmentresults::where('lesson_id', $newlessonslist->max('lesson_id'))->first();

            // $max_lesson = $newlessonslist->max('lesson_id');
            if ($max_lesson->status === "Passed") {
                $next_id  = (int) $max_lesson->lesson_id + 1;

                foreach ($lessons as $lesson) {
                    if ($lesson->lesson_number <= $next_id) {
                        $lesson->is_accessible = true;
                    } else {
                        $lesson->is_accessible = false;
                    }
                }
            } else {
                // then-all-lesson-before-shouldntbe accessible
                foreach ($lessons as $lesson) {
                    if ($lesson->lesson_number <= $max_lesson->lesson_id) {
                        $lesson->is_accessible = true;
                    } else {
                        $lesson->is_accessible = false;
                    }
                }
            }
        }
        return view('student.courseview', ['course' => $course, 'lessons' => $lessons, 'routeNamePart' => $routeName]);
    }

    // completion-and-course-and-verificatin
    public function courseCompletion($courseId)
    {

        $relatedcourse = Course::find($courseId);

        if (!$relatedcourse) {
            return redirect('/courses')->with('error', 'Course not found.');
        }
        // Fetch-user-id
        $usrid = auth()->user()->id;
        // fetch-related-lesson-for-the-course
        $lessonslist = $relatedcourse->lessons;

        //   fetchlast-lesson-for-the-course-whichreturns-theid
        $lastlesson = $lessonslist->max('lesson_number');

        // Fetch-ifuser-cpmleteslast-assessment-for the lesson
        $lastassessment = lessonassessmentresults::where('course_id', $courseId)->where('lesson_id', $lastlesson)->first();

        if (!$lastassessment) {
            return redirect('/courses/${courseId}')->with('error', 'Seems You haven\'t completed the course, Your certificate is not ready!');
        };

        // pass-to-thenext-if-user-has-the-last-assessment-nrecord
        // check-if-user-passes-thelastifinrecord
        if (!$lastassessment->status === "Passed") {
            return redirect('/courses/{courseId}')->with('error', 'Seems You haven\'t Passed the Last lesson, Take The Assessment and claim Your  certification');
        }


        // find-the-user-certificates-and-pass-to-the-view
        $certificateuser = User::find($usrid)->userCertificates;
        // fetch-username-inrelation-to-certificate
        $certificateusername = User::find($certificateuser->student_id)->name;


        $certificatedate = $this->getCreatedAtAttribute($certificateuser->created_at);

        // if-user-passed-thelast-course-then-give access
        // fetch-whats-needed-inthe-certification-details

        return view('student.coursecertification', [
            'routeNamePart' => 'Course Completion',
            'certificate_ref' => $certificateuser->reference_id,
            'certificate_name' => $certificateusername,
            'certificate_date' => $certificatedate,
            'coursename' => $relatedcourse->name,
        ]);
    }

    // helper-to-format-isodate
    protected function getCreatedAtAttribute($date)
    {
        return \Carbon\Carbon::parse($date)->format('M d Y');
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

        $Questions = json_decode($questionsexist->questions, true);

        $routeNamePart = "CBT Test";
        return view('student.assessmenttake', compact('Questions', 'routeNamePart'));
    }

    // submit-assessments
    public function submitlessonAssessment(Request $request, $course_id, $lessonid)
    {
        $validated = $request->validate([
            'answers' => 'array | required'
        ]);

        // type-check-lesson
        $Lessonpresent = CourseLesson::where('lesson_number', $lessonid)->first();

        if (!$Lessonpresent) {
            return redirect('/courses')->with('error', 'Lesson-not-found');
        }

        // if-its-thelast-lesson
        $islasttakenlesson = false;

        // initializing-score
        $totalscore = 0;
        // intialize-points
        $totalpoints = 0;
        // filter-the-questions-answers-from-thedb
        $matchedQuestions = json_decode(Lessonassessment::where('course_id', $course_id)->where('lesson_id', $lessonid)->first()->questions, true);

        // loop-throug-questand-calc
        foreach ($matchedQuestions['questions'] as $question) {
            // get-the-id-of-question
            $matchquestid = $question['id'];

            // filter-correct-option
            $correctoption = collect($question['options'])->firstWhere("is_correct", 'true');
            // then-filter-the-correct-matched question
            $studentanswer = collect($validated['answers'])->firstWhere('question_id', $matchquestid);
            //   mark-it-if-correct
            // basically-ids-is-uesd for making -since some -options-containds-files
            if (isset($studentanswer) && $studentanswer['selected_option']  === $correctoption['id']) {
                // if true add the scorescast-data-if-needed
                $totalscore += (int) $question['points'];
            } else {
                $totalscore += 0;
            }

            $totalpoints += (int) $question['points'];
        }

        // Calculate percentage score
        if ($totalpoints > 0) {
            $percentageScore = ($totalscore / $totalpoints) * 100;
        } else {
            $percentageScore = 0;
        }

        // check-if-user-already-passed-before
        $usrasslst = auth()->user()->userAssessmentresult;
        if (!$usrasslst->isEmpty()) {
            $checkerfunction = $usrasslst->where('lesson_id', $lessonid)->where('status', 'Passed')->first();

            if ($checkerfunction) {
                return response()->json([
                    'statustext' => 'redirect',
                    'url' => "/courses/{$course_id}",
                    'message' => 'You already passed this Lesson! Go to the next. lesson'
                ], 200);
            }
        }


        $pass_score = 60;
        lessonassessmentresults::updateOrCreate(
            [
                'course_id' => $course_id,
                'lesson_id' => $lessonid,
                'student_id' => auth()->user()->id,
            ],
            [
                'answers' => json_encode($validated['answers']),
                'score' => $percentageScore,
                'status' => round($percentageScore) >= $pass_score ? "Passed" : "Failed",
            ]
        );
        $user_id = auth()->user()->id;

        // check-if-the-lesson-taken-is-the-last-one
        if ((int) $Lessonpresent->max('lesson_number') === (int) $lessonid && round($percentageScore) >= $pass_score) {
            // certificate-reference-id
            $referenceId = 'CERT-' . uniqid() . $course_id . $lessonid . $user_id;
            // first-add-the-user-certificate-to-db
            Certification::create([
                'student_id' => $user_id,
                'course_id' => $course_id,
                'reference_id' => $referenceId
            ]);

            return redirect('/courses/' . $course_id . '/coursecompletion');
        }

        if (round($percentageScore) >= $pass_score) {
            $message = "Congratulations! You passed the assessment with a score of <span style='color: blue; font-weight: bold;'>" . round($percentageScore) . "%</span>.";
            return response()->json(['statustext' => 'passed', 'message' => $message, 'url' => "/courses/{$course_id}",], 200);
        } else {
            $message = "Unfortunately, you did not pass the assessment. Your score is <span style='color: red; font-weight: bold;'>" . round($percentageScore) . "%</span>.";
            return response()->json(['statustext' => 'failed', 'message' => $message], 200);
        }
    }
}
