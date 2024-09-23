<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\Dawah;
use App\Models\DawahPost;
use App\Models\CourseLesson;
use App\Models\CourseAssessment;
use App\Models\UserLessonAssessment;
use App\Models\User;
use App\Models\Lessonassessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// notifications
// this-d-for-general
use App\Notifications\Generalnotify;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $adminusers = User::where('role', 'admin')->get();
        $studentusers = User::where('role', 'student')->get();
        $lecturerusers = User::where('role', 'lecturer')->get();
        $teacherusers = User::where('role', 'teacher')->get();
        $dawahs = Dawah::all();
        $courses = Course::all();
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));

        return view('admin.dashboard', compact('user', 'adminusers', 'studentusers', 'lecturerusers', 'teacherusers', 'dawahs', 'courses', 'routeNamePart'));
    }

    public function students()
    {
        $user = Auth::user();
        $studentusers = User::where('role', 'student')->get();
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));

        return view('admin.students', compact('user', 'studentusers', 'routeNamePart'));
    }

    public function teachers()
    {
        $user = Auth::user();
        $teacherusers = User::where('role', 'teacher')->get();
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));

        return view('admin.teachers', compact('user', 'teacherusers', 'routeNamePart'));
    }

    public function lecturers()
    {
        $user = Auth::user();
        $lecturerusers = User::where('role', 'lecturer')->get();
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));

        return view('admin.lecturers', compact('user', 'lecturerusers', 'routeNamePart'));
    }

    public function courses()
    {
        $user = Auth::user();
        $courses = Course::normal()->get();
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));

        return view('admin.courses', compact('user', 'routeNamePart', 'courses'));
    }

    // fetch-premium-course
    public function getPremiumcourses()
    {
        $user = Auth::user();
        $special =  Course::special()->get();  // Calls the scopeSpecial method
        return view('admin.courses', ['courses' => $special, 'routeNamePart' => 'Special', "user" => $user]);
    }

    public function registerCourse(Request $request)
    {
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;

        $request->validate([
            // type_check_fornormal_or_special
            'course_type' => 'required|in:normal,special'
        ]);
        $data = array_merge($request->all(), [
            'reference' => uniqid('CS_', true),
            'image' => $imagePath,
        ]);

        if (Course::where('name', $data['name'])->exists()) {
            return redirect()->back()->with('error', 'A Course with this Title already exists.');
        }

        $createdcourse = Course::create($data);

        // send-a-notification-to-allusers
        $users = User::all();

        // check-user-who-sent-the-notificatin
        $currentuser = auth()->user();


        // srap-attached-link
        $attachedlink =  (string) '/admin/courses/' . $createdcourse->id;

        // log-check-course-link
        Log::info('Attached Link: ' . $attachedlink);

        foreach ($users as $user) {
            $user->notify(new Generalnotify('🎉 New Course Added! Explore and boost your skills now.', $currentuser, $attachedlink));
        }

        return redirect()->back()->with('success', 'New Course created successfully.');
    }
    public function Addlessonassessment(Request $request, $courseid, $lessonid)
    {
        // Validate input
        $validated = $request->validate([
            'questions' => 'required|array',
            'general_time_limit' => 'required|integer'
        ]);

        $course = Course::find($courseid);

        // Validate if course and lesson exist
        $lesson = CourseLesson::where('course_id', $courseid)->where('lesson_number', $lessonid)->first();

        if (!$lesson) {
            return response()->json(['Course or Lesson not Found'], 404);
        }

        // Get and prepare questions data
        $questionsData = $validated['questions'];
        // Add the general_time_limit to the questions data
        $formattedData = [
            'general_time_limit' => $validated['general_time_limit'],
            'questions' => []
        ];

        foreach ($questionsData as &$question) {
            // Process options
            if (isset($question['options']) && is_array($question['options'])) {
                foreach ($question['options'] as &$option) {
                    // Handle media upload for options
                    $option['media']['image_path'] = $this->handleFileUpload($option['media']['image_path'] ?? null, 'images');
                    $option['media']['audio_path'] = $this->handleFileUpload($option['media']['audio_path'] ?? null, 'audio');
                    $option['media']['video_path'] = $this->handleFileUpload($option['media']['video_path'] ?? null, 'video');
                }
            }

            // Handle media upload for questions
            $question['media']['image_path'] = $this->handleFileUpload($question['media']['image_path'] ?? null, 'images');
            $question['media']['audio_path'] = $this->handleFileUpload($question['media']['audio_path'] ?? null, 'audio');
            $question['media']['video_path'] = $this->handleFileUpload($question['media']['video_path'] ?? null, 'video');
            $formattedData['questions'][] = $question;
        }

        // Convert formatted data to JSON
        $questionsJson = json_encode($formattedData);
        // Create a new LessonAssessment record
        $assessment = Lessonassessment::create([
            'course_id' => $courseid,
            'lesson_id' => $lessonid,
            'questions' => $questionsJson,
        ]);

        return response()->json($assessment, 200);
    }



    // show_crestesd_asessment
    public function PreviewAssessment(Request $request, $courseid, $lessonid)
    {

        $lesson = CourseLesson::where('lesson_number', $lessonid)->where('course_id', $courseid)->firstOrFail();

        // get_course_name
        $course_name = Course::where('id', $courseid)->firstOrFail();
        $fetch_course_name = $course_name->name;
        // fetch_related_assessment
        $assessments = Lessonassessment::where('course_id', $lesson->course_id)->where('lesson_id', $lesson->lesson_number)->first();

        $questionsData = json_decode($assessments->questions, true);

        $routeNamePart = "ManageAssessment";

        return view('admin.viewassessment', [
            'assessments' => $questionsData,
            'fetch_course_name' => $course_name->name,
            'lesson_id' => $assessments->lesson_id,
            'routeNamePart' => $routeNamePart
        ]);
    }
    // handle-file-uploadfor-lessonassessment
    protected function handleFileUpload($file, $type)
    {
        if ($file) {
            $path = $file->store('lessonsassessments/uploads/' . $type, 'public');
            return $path;
        }
        return null;
    }

    public function UpdateLessonAssessment(Request $request, $courseid, $lessonid)
    {
        // Validate input
        $validated = $request->validate([
            'questions' => 'required|array',
            'general_time_limit' => 'required|integer'
        ]);
        // Validate if course and lesson exist
        $lesson = CourseLesson::where('course_id', $courseid)->where('lesson_number', $lessonid)->first();

        if (!$lesson) {
            return response()->json(['Course or Lesson not Found'], 404);
        }

        // Validate if the assessment exists
        $assessment = Lessonassessment::where('course_id', $courseid)->where('lesson_id', $lessonid)->first();

        //   disable-assessment-id-check-since-an assessmentbelongstoa lesson  ->where('id', $assessmentId)

        if (!$assessment) {
            return response()->json(['Assessment not Found'], 404);
        }

        // Get and prepare questions data
        $questionsData = $validated['questions'];

        // Add the general_time_limit to the questions data
        $formattedData = [
            'general_time_limit' => $validated['general_time_limit'],
            'questions' => []
        ];

        foreach ($questionsData as &$question) {
            // Process options
            if (isset($question['options']) && is_array($question['options'])) {
                foreach ($question['options'] as &$option) {
                    // Handle media upload for options
                    $option['media']['image_path'] = $this->handleFileUpload($option['media']['image_path'] ?? null, 'images');
                    $option['media']['audio_path'] = $this->handleFileUpload($option['media']['audio_path'] ?? null, 'audio');
                    $option['media']['video_path'] = $this->handleFileUpload($option['media']['video_path'] ?? null, 'video');
                }
            }

            // Handle media upload for questions
            $question['media']['image_path'] = $this->handleFileUpload($question['media']['image_path'] ?? null, 'images');
            $question['media']['audio_path'] = $this->handleFileUpload($question['media']['audio_path'] ?? null, 'audio');
            $question['media']['video_path'] = $this->handleFileUpload($question['media']['video_path'] ?? null, 'video');
            $formattedData['questions'][] = $question;
        }

        // Convert formatted data to JSON
        $questionsJson = json_encode($formattedData);
        // Create a new LessonAssessment record
        $assessment->update([
            'course_id' => $courseid,
            'lesson_id' => $lessonid,
            'questions' => $questionsJson,
        ]);

        return response()->json($assessment, 201);
    }
    // outdated-assessments  controllers-below

    public function manageAssessments($courseId)
    {
        $course = Course::findOrFail($courseId);
        $assessments = $course->assessments;
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));

        return view('admin.course_assessments.index', compact('course', 'assessments', 'routeNamePart'));
    }

    public function createAssessment($courseId)
    {
        $course = Course::findOrFail($courseId);
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));

        return view('admin.course_assessments.create', compact('course', 'routeNamePart'));
    }

    public function storeAssessment(Request $request, $courseId)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.correct' => 'required|string',
        ]);

        $course = Course::findOrFail($courseId);

        $assessment = new CourseAssessment([
            'course_id' => $course->id,
            'name' => $validatedData['name'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'questions' => json_encode($validatedData['questions']),
        ]);

        $assessment->save();

        return redirect()->route('admin.course_assessments.index', $courseId)->with('success', 'Assessment created successfully.');
    }

    public function deleteAssessment(Request $request, $id)
    {
        if ($request->confirm === 'yes') {
            CourseAssessment::findOrFail($id)->delete();
            return redirect()->back()->with('success', 'Assessment deleted successfully.');
        }

        return redirect()->back()->with('error', 'Assessment deletion canceled.');
    }

    public function publishAssessment($id)
    {
        $assessment = CourseAssessment::findOrFail($id);
        $assessment->is_published = true;
        $assessment->save();

        return redirect()->back()->with('success', 'Assessment published successfully.');
    }

    public function unpublishAssessment($id)
    {
        $assessment = CourseAssessment::findOrFail($id);
        $assessment->is_published = false;
        $assessment->save();

        return redirect()->back()->with('success', 'Assessment unpublished successfully.');
    }

    public function showAssessment($courseId, $assessmentId)
    {
        $course = Course::findOrFail($courseId);
        $assessment = CourseAssessment::findOrFail($assessmentId);
        $assessment->questions = json_decode($assessment->questions, true);
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));

        return view('admin.course_assessments.show', compact('course', 'assessment', 'routeNamePart'));
    }

    public function studentGrades()
    {
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));
        $students = User::where('role', 'student')->get();

        foreach ($students as $student) {
            $student->courses = Course::whereHas('userCourseLessons', function ($query) use ($student) {
                $query->where('user_id', $student->id);
            })->get();

            foreach ($student->courses as $course) {
                $course->lessons = CourseLesson::where('course_id', $course->id)->get();

                foreach ($course->lessons as $lesson) {
                    $lesson->grade = UserLessonAssessment::where('user_id', $student->id)
                        ->where('lesson_id', $lesson->id)
                        ->first();
                }
            }
        }

        return view('admin.student_grades', compact('students', 'routeNamePart'));
    }

    public function assignCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $course = Course::findOrFail($request->course_id);

        if ($course->users()->where('user_id', $request->user_id)->count() >= 2) {
            return redirect()->back()->with('error', 'A course cannot have more than two lecturers/teachers.');
        }

        $course->users()->attach($request->user_id, ['role' => User::findOrFail($request->user_id)->role]);

        return redirect()->route('admin.assign-course')->with('success', 'Course assigned successfully.');
    }

    public function unassignCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $course = Course::findOrFail($request->course_id);
        $course->users()->detach($request->user_id);

        return redirect()->route('admin.assign-course')->with('success', 'Course unassigned successfully.');
    }

    public function fetchCourse($courseid)
    {
        // Find the course by its ID
        $course = Course::find($courseid);
        //   check-if-lesson-exist

        $routeName = "CourseView";
        // Check if the course exists
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        };

        // check-fi-user-has-course-access
        // course-access-is now handled in middleware

        $lessons = $course->lessons;

        // Return the course data as a JSON response
        // return response()->json($course);

        // Return the Blade view and pass the course data to it
        return view('admin.courseview', ['course' => $course, 'lessons' => $lessons, 'routeNamePart' => $routeName]);
    }

    // viewlessons-controller
    public function fetchLesson($courseid, $lessonid)
    {
        $course = Course::find($courseid);

        // validate-course-id
        if (!$course) {
            return response()->json(['error' => 'Course not Found', 404]);
        }

        // check-fi-user-has-course-access
        if ($course->course_type === "special") {
            // continue-tocheck-priviledge
            if (auth()->user()->user_type != 'premium') {
                return redirect('dashboard')->with('error', 'You have no acess to this course');
            }
        }
        // check-for-lessons
        $checklesson = Lessonassessment::where("course_id", $courseid)->where("lesson_id", $lessonid)->first();

        $hasasessment = $checklesson ? true : false;
        // lesson-validate-the-id
        $lesson = CourseLesson::where('course_id', $courseid)->where('lesson_number', $lessonid)->first();

        if (!$lesson) {
            return response()->json(['error' => 'Lesson not found'], 404);
        }

        // continue-f-all-is-well
        return view('admin.lessonview', ['course' => $course, 'lesson' => $lesson, 'routeNamePart' => 'LessonView', 'hasassessment' => $hasasessment]);
    }

    // add-lessons-toourse
    public function addcourseLessons(Request $request, $courseid)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:20480',
            'audio' => 'nullable|file|mimes:mp3,wav|max:10240',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:10240', // 
            'status' => 'nullable|string'
        ]);

        // Check if the course exists before proceeding
        $course = Course::find($courseid);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404); // Return 404 if the course doesn't exist
        }

        // Handle file upload for image
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('lessons/images', 'public') : null;
        $videoPath = $request->hasFile('video') ? $request->file('video')->store("lessons/video", 'public') : null;
        $audioPath = $request->hasFile('audio') ? $request->file('audio')->store("lessons/audio", 'public') : null;


        // add-lesson-numer-filed-to-auto-increment-manually
        // this-after-validation
        $lastsession = CourseLesson::where('course_id', $courseid)->orderBy('lesson_number', 'desc')->first();
        $nextlessonnumber = $lastsession ? $lastsession->lesson_number + 1 : 1;

        // Create new lesson and link it to the course
        $lesson = new CourseLesson();
        $lesson->course_id = $courseid;
        $lesson->name = $request->name;
        $lesson->description = $request->description;
        $lesson->video = $videoPath;
        $lesson->audio = $audioPath;
        $lesson->status = $request->status;
        $lesson->image = $imagePath;
        $lesson->lesson_number = $nextlessonnumber;
        $lesson->save();

        return response()->json($lesson, 201);
    }


    public function assessments($courseId)
    {
        $user = Auth::user();
        $course = Course::find($courseId);

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        // Assuming that there's a relationship between Course and CourseAssessment
        $assessments = $course->assessments;

        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.course_assessments.index', compact('user', 'course', 'assessments', 'routeNamePart'));
    }
}
