<?php
namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseAssessmentController extends Controller
{
    // Display a listing of the resource.
    public function index(Course $course)
    {
        $assessments = $course->assessments; // Assuming you have a relationship defined in the Course model
        return view('course_assessments.index', compact('course', 'assessments'));
    }

    // Show the form for creating a new resource.
    public function create(Course $course)
    {
        return view('course_assessments.create', compact('course'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request, Course $course)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.media' => 'nullable|file|mimes:mp4,mp3',
            'questions.*.options' => 'required|array',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.options.*.media' => 'nullable|file|mimes:mp4,mp3',
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

        $courseAssessment = new CourseAssessment([
            'course_id' => $course->id,
            'name' => $validatedData['name'],
            'questions' => json_encode($questions),
        ]);
        $courseAssessment->save();

        return redirect()->route('courses.show', $course->id)
            ->with('success', 'Course assessment created successfully.');
    }

    // Display the specified resource.
    public function show(Course $course, CourseAssessment $assessment)
    {
        return view('course_assessments.show', compact('course', 'assessment'));
    }

    // Show the form for editing the specified resource.
    public function edit(Course $course, CourseAssessment $assessment)
    {
        return view('course_assessments.edit', compact('course', 'assessment'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, Course $course, CourseAssessment $assessment)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.media' => 'nullable|file|mimes:mp4,mp3',
            'questions.*.options' => 'required|array',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.options.*.media' => 'nullable|file|mimes:mp4,mp3',
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

        $assessment->update([
            'name' => $validatedData['name'],
            'questions' => json_encode($questions),
        ]);

        return redirect()->route('courses.show', $course->id)
            ->with('success', 'Course assessment updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy(Course $course, CourseAssessment $assessment)
    {
        $assessment->delete();

        return redirect()->route('courses.show', $course->id)
            ->with('success', 'Course assessment deleted successfully.');
    }
}
