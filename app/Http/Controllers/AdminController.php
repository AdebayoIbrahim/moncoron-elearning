<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\Dawah;
use App\Models\DawahPost;
use App\Models\CourseLesson;
use App\Models\CourseAssessment;
use App\Models\UserLessonAssessment; // Add this line
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    $routeName = Route::currentRouteName();
    $routeNamePart = ucfirst(last(explode('.', $routeName)));

    return view('admin.dashboard', compact('user', 'adminusers', 'studentusers', 'lecturerusers', 'teacherusers', 'dawahs', 'courses', 'routeNamePart'));
}

    public function students()
    {
        $user = Auth::user();
        $studentusers = User::where('role', 'student')->get();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.students', compact('user', 'studentusers', 'routeNamePart'));
    }

    public function teachers()
    {
        $user = Auth::user();
        $teacherusers = User::where('role', 'teacher')->get();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.teachers', compact('user', 'teacherusers', 'routeNamePart'));
    }

    public function lecturers()
    {
        $user = Auth::user();
        $lecturerusers = User::where('role', 'lecturer')->get();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.lecturers', compact('user', 'lecturerusers', 'routeNamePart'));
    }

    public function registerStudent(Request $request)
    {
        $data = array_merge($request->all(), [
            'ref' => uniqid('USR_', true),
            'role' => 'student',
        ]);

        $emailExists = User::where('email', $data['email'])->exists();
        if ($emailExists) {
            return redirect()->back()->with('error', 'A User with this email already exists, please try again.');
        } else {
            User::create($data);
            return redirect()->back()->with('success', 'New Student Account has been created successfully.');
        }
    }

    public function registerTeacher(Request $request)
    {
        $data = array_merge($request->all(), [
            'ref' => uniqid('USR_', true),
            'role' => 'teacher',
        ]);

        $emailExists = User::where('email', $data['email'])->exists();
        if ($emailExists) {
            return redirect()->back()->with('error', 'A User with this email already exists, please try again.');
        } else {
            User::create($data);
            return redirect()->back()->with('success', 'New Teacher Account has been created successfully.');
        }
    }

    public function registerLecturer(Request $request)
    {
        $data = array_merge($request->all(), [
            'ref' => uniqid('USR_', true),
            'role' => 'lecturer',
        ]);

        $emailExists = User::where('email', $data['email'])->exists();
        if ($emailExists) {
            return redirect()->back()->with('error', 'A User with this email already exists, please try again.');
        } else {
            User::create($data);
            return redirect()->back()->with('success', 'New Lecturer Account has been created successfully.');
        }
    }

    public function courses()
    {
        $user = Auth::user();
        $courses = Course::all();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.courses', compact('user', 'routeNamePart', 'courses'));
    }

    public function registerCourse(Request $request)
    {
        $imagePath = null;

        if ($request->hasFile('image')) {
            Log::info('Image file detected.');
            $imagePath = $request->file('image')->store('images', 'public');
            Log::info('Image Path: ' . $imagePath);
        } else {
            Log::info('No image file uploaded.');
        }

        $data = array_merge($request->all(), [
            'reference' => uniqid('CS_', true),
            'image' => $imagePath,
        ]);

        $titleExists = Course::where('name', $data['name'])->exists();
        if ($titleExists) {
            return redirect()->back()->with('error', 'A Course with this Title already exists, please try again.');
        } else {
            Course::create($data);
            return redirect()->back()->with('success', 'New Course was created successfully.');
        }
    }

    public function editCourse($id)
    {
        $user = Auth::user();
        $course = Course::find($id);
        $routeNamePart = 'Courses';

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return view('admin.courseview', compact('user', 'routeNamePart', 'course'));
    }

    public function viewCourse($id)
    {
        $user = Auth::user();
        $course = Course::find($id);
        $lessons = $course->lessons()->get();
        $routeNamePart = 'Courses';
        session(['current_course_id' => $id]);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return view('admin.courseview', compact('user', 'routeNamePart', 'course', 'lessons'));
    }

    public function deleteCourse($id)
    {
        Course::find($id)->delete();
        return redirect()->back()->with('success', 'Course was deleted successfully.');
    }

    public function fetchCourse($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return response()->json($course);
    }

    public function updateCourse(Request $request)
    {
        $id = $request->id;
        $updatecourse = Course::find($id)->update($request->all());
        Log::info('update info: ' . $updatecourse);
        Log::info('course id: ' . $id);

        return redirect()->route('admin.courses')->with('success', 'Course was updated successfully');
    }

    public function addLesson(Request $request)
    {
        Log::info('Request data:', $request->all());

        $request->validate([
            'video' => 'nullable|file|mimes:mp4,avi,mkv|max:51200',  // 50 MB max size
            'audio' => 'nullable|file|mimes:mp3,wav|max:51200',
        ]);

        $videoPath = null;
        $audioPath = null;

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            Log::info('Video file detected and is valid.');
            $video = $request->file('video');
            $originalFileName = $video->getClientOriginalName();
            $uniqueFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . time() . '.' . $video->getClientOriginalExtension();
            $videoPath = $video->storeAs('videos', $uniqueFileName, 'public');
            Log::info('Video Path: ' . $videoPath);
        } else {
            Log::info('No valid video file uploaded.');
        }

        if ($request->hasFile('audio') && $request->file('audio')->isValid()) {
            Log::info('Audio file detected and is valid.');
            $audio = $request->file('audio');
            $originalFileName = $audio->getClientOriginalName();
            $uniqueFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . time() . '.' . $audio->getClientOriginalExtension();
            $audioPath = $audio->storeAs('audios', $uniqueFileName, 'public');
            Log::info('Audio Path: ' . $audioPath);
        } else {
            Log::info('No valid audio file uploaded.');
        }

        $data = array_merge($request->all(), [
            'video' => $videoPath,
            'audio' => $audioPath,
            'status' => 0,
        ]);

        Log::info('Merged data:', $data);

        $titleExists = CourseLesson::where('course_id', $data['course_id'])
            ->where('name', $data['name'])
            ->exists();
        if ($titleExists) {
            return redirect()->back()->with('error', 'This Lesson with this Title for this Course already exists, please try again.');
        } else {
            CourseLesson::create($data);
            return redirect()->back()->with('success', 'New Lesson was created successfully.');
        }
    }

    // Course Assessment Methods

    public function assessments($courseId)
    {
        $user = Auth::user();
        $course = Course::find($courseId);
        $assessments = $course->assessments; // Assuming you have a relationship defined in the Course model
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.assessments.index', compact('user', 'course', 'assessments', 'routeNamePart'));
    }

    public function createAssessment($courseId)
    {
        $user = Auth::user();
        $course = Course::find($courseId);
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.assessments.create', compact('user', 'course', 'routeNamePart'));
    }

    public function storeAssessment(Request $request, $courseId)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'duration' => 'required|integer|min:1',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.media' => 'nullable|file|mimes:mp4,mp3,jpg,jpeg,png,gif',
            'questions.*.options' => 'required|array|max:5',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.options.*.media' => 'nullable|file|mimes:mp4,mp3,jpg,jpeg,png,gif',
            'questions.*.options.*.correct' => 'sometimes|boolean',
        ]);

        // Initialize an array to store questions
        $questions = [];

        // Loop through each question and process it
        foreach ($validatedData['questions'] as $index => $question) {
            // Check if the question has a media file and store it
            if (isset($question['media'])) {
                $question['media'] = $question['media']->store('questions', 'public');
            }

            // Loop through each option of the question and process it
            foreach ($question['options'] as $optionIndex => $option) {
                // Check if the option has a media file and store it
                if (isset($option['media'])) {
                    $question['options'][$optionIndex]['media'] = $option['media']->store('options', 'public');
                }

                // Check if the option is marked as correct
                $question['options'][$optionIndex]['correct'] = isset($option['correct']) ? true : false;
            }

            // Add the processed question to the questions array
            $questions[] = $question;
        }

        // Create a new course assessment with the processed questions
        $courseAssessment = new CourseAssessment([
            'course_id' => $courseId,
            'name' => $validatedData['name'],
            'duration' => $validatedData['duration'],
            'questions' => json_encode($questions), // Ensure the questions are stored as JSON
        ]);

        // Save the course assessment to the database
        $courseAssessment->save();

        // Redirect back to the assessments page with a success message
        return redirect()->route('admin.courses.assessments', $courseId)
            ->with('success', 'Course assessment created successfully.');
    }

    public function showAssessment($courseId, $assessmentId)
    {
        $user = Auth::user();
        $course = Course::find($courseId);
        $assessment = CourseAssessment::find($assessmentId);

        if (!$assessment) {
            return redirect()->back()->with('error', 'Assessment not found.');
        }

        $assessment->questions = json_decode($assessment->questions, true);

        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.assessments.show', compact('user', 'course', 'assessment', 'routeNamePart'));
    }

    public function editAssessment($courseId, $assessmentId)
    {
        $user = Auth::user();
        $course = Course::find($courseId);
        $assessment = CourseAssessment::find($assessmentId);
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        $assessment->questions = json_decode($assessment->questions, true);

        return view('admin.assessments.edit', compact('user', 'course', 'assessment', 'routeNamePart'));
    }

    public function updateAssessment(Request $request, $courseId, $assessmentId)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'duration' => 'required|integer|min:1',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.media' => 'nullable|file|mimes:mp4,mp3,jpg,jpeg,png,gif',
            'questions.*.options' => 'required|array|max:5',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.options.*.media' => 'nullable|file|mimes:mp4,mp3,jpg,jpeg,png,gif',
            'questions.*.options.*.correct' => 'sometimes|boolean',
        ]);

        $questions = [];

        foreach ($validatedData['questions'] as $index => $question) {
            if (isset($question['media'])) {
                $question['media'] = $question['media']->store('questions', 'public');
            }

            foreach ($question['options'] as $optionIndex => $option) {
                if (isset($option['media'])) {
                    $question['options'][$optionIndex]['media'] = $option['media']->store('options', 'public');
                }
                $question['options'][$optionIndex]['correct'] = isset($option['correct']) ? true : false;
            }

            $questions[] = $question;
        }

        $assessment = CourseAssessment::find($assessmentId);
        $assessment->update([
            'name' => $validatedData['name'],
            'duration' => $validatedData['duration'],
            'questions' => json_encode($questions), // Ensure the questions are stored as JSON
        ]);

        return redirect()->route('admin.courses.assessments', $courseId)
            ->with('success', 'Course assessment updated successfully.');
    }

    public function deleteAssessment($courseId, $assessmentId)
    {
        $assessment = CourseAssessment::find($assessmentId);
        $assessment->delete();

        return redirect()->route('admin.courses.assessments', $courseId)
            ->with('success', 'Course assessment deleted successfully.');
    }

    public function showAssignCourseForm()
{
    $courses = Course::all();
    $lecturers = User::where('role', 'lecturer')->get();
    $teachers = User::where('role', 'teacher')->get();
    $assignments = Course::with('users')->get();
    $routeName = Route::currentRouteName();
    $routeNamePart = ucfirst(last(explode('.', $routeName)));

    return view('admin.assign-course', compact('courses', 'lecturers', 'teachers', 'assignments', 'routeNamePart'));
}

    public function assignCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $course = Course::find($request->course_id);

        if ($course->users()->where('user_id', $request->user_id)->count() >= 2) {
            return redirect()->back()->with('error', 'A course cannot have more than two lecturers/teachers.');
        }

        $course->users()->attach($request->user_id, ['role' => User::find($request->user_id)->role]);

        return redirect()->route('admin.assign-course')->with('success', 'Course assigned successfully.');
    }

    public function unassignCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $course = Course::find($request->course_id);
        $course->users()->detach($request->user_id);

        return redirect()->route('admin.assign-course')->with('success', 'Course unassigned successfully.');
    }

    public function manageUsers(Request $request)
    {
        $status = $request->query('status');
        $query = User::query();

        if ($status) {
            $query->where('status', $status === 'suspended' ? 0 : 1);
        }

        $users = $query->get();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.manage-users', compact('users', 'routeNamePart'));
    }

    public function suspendUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 0;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User suspended successfully.');
    }

    public function unsuspendUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 1;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User unsuspended successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
    public function showDawahPosts(Request $request, $dawahId)
    {
        $dawah = Dawah::findOrFail($dawahId);
        $mediaType = $request->query('media_type');

        $query = $dawah->posts();
        if ($mediaType) {
            $query->where('media_type', $mediaType);
        }

        $posts = $query->get();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.dawah-posts', compact('dawah', 'posts', 'routeNamePart', 'mediaType'));
    }

    public function createDawahPostForm($dawahId)
    {
        $dawah = Dawah::findOrFail($dawahId);
        return view('admin.create-dawah-post', compact('dawah'));
    }

    public function createDawahPost(Request $request)
    {
        $request->validate([
            'dawah_id' => 'required|exists:dawahs,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'media' => 'nullable|file|mimes:mp4,mp3,jpg,jpeg,png,gif',
        ]);

        $mediaPath = null;
        $mediaType = 'text';

        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $mediaPath = $media->store('dawah_posts', 'public');

            $mimeType = $media->getMimeType();
            if (strstr($mimeType, 'video/')) {
                $mediaType = 'video';
            } elseif (strstr($mimeType, 'audio/')) {
                $mediaType = 'audio';
            } elseif (strstr($mimeType, 'image/')) {
                $mediaType = 'image';
            }
        }

        $dawahPost = new DawahPost([
            'dawah_id' => $request->dawah_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
        ]);

        $dawahPost->save();

        return redirect()->route('admin.dawah-posts', $request->dawah_id)->with('success', 'Post created successfully.');
    }


    public function studentGrades()
{
    $routeName = Route::currentRouteName();
    $routeNamePart = ucfirst(last(explode('.', $routeName)));

    // Fetch all students
    $students = User::where('role', 'student')->get();

    // Fetch courses, lessons, and grades for each student
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
}