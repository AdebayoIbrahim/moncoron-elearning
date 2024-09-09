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
        $courses = Course::all();
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));

        return view('admin.courses', compact('user', 'routeNamePart', 'courses'));
    }

    public function registerCourse(Request $request)
    {
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;

        $data = array_merge($request->all(), [
            'reference' => uniqid('CS_', true),
            'image' => $imagePath,
        ]);

        if (Course::where('name', $data['name'])->exists()) {
            return redirect()->back()->with('error', 'A Course with this Title already exists.');
        }

        Course::create($data);
        return redirect()->back()->with('success', 'New Course created successfully.');
    }

    public function addcourseLessons(Request $request, $courseid) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:20480',
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
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('lessons/images','public') : null;
        $videoPath = $request->hasFile('video') ? $request->file('video')->store("lessons/video",'public') : null;
        $audioPath = $request->hasFile('audio') ? $request->file('audio')->store("lessons/audio",'public') : null;
    

        // add-lesson-numer-filed-to-auto-increment-manually
        // this-after-validation
        $lastsession = CourseLesson::where('course_id',$courseid)->orderBy('lesson_number','desc')->first();
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

    public function editAssessment($courseId, $assessmentId)
    {
        $course = Course::findOrFail($courseId);
        $assessment = CourseAssessment::findOrFail($assessmentId);
        $assessment->questions = json_decode($assessment->questions, true);
        $routeNamePart = ucfirst(last(explode('.', Route::currentRouteName())));

        return view('admin.course_assessments.edit', compact('course', 'assessment', 'routeNamePart'));
    }

    public function updateAssessment(Request $request, $courseId, $assessmentId)
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

        $assessment = CourseAssessment::findOrFail($assessmentId);
        $assessment->update([
            'name' => $validatedData['name'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'questions' => json_encode($validatedData['questions']),
        ]);

        return redirect()->route('admin.course_assessments.index', $courseId)->with('success', 'Assessment updated successfully.');
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

public function fetchCourse($id)
{
      // Find the course by its ID
      $course = Course::find($id);
    //   check-if-lesson-exist
      
        $routeName = "CourseView";


      // Check if the course exists
      if (!$course) {
          return response()->json(['error' => 'Course not found'], 404);
      };

      $lessons = $course->lessons;
  
      // Return the course data as a JSON response
      // return response()->json($course);
  
      // Return the Blade view and pass the course data to it
    return view('admin.courseview', ['course' => $course,'lessons' => $lessons,'routeNamePart' => $routeName]);
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