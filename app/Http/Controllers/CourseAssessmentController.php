<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAssessment;
use App\Models\CourseAssessmentSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseAssessmentController extends Controller
{
    // Display all assessments for a course
    public function index(Course $course)
    {
        $assessments = CourseAssessment::where('course_id', $course->id)->get();
        return view('admin.course_assessments.index', compact('course', 'assessments'));
    }

    // Show form to create a new assessment for a course
    public function create(Course $course)
    {
        return view('admin.course_assessments.create', compact('course'));
    }

    // Store a new assessment in the database
    public function store(Request $request, Course $course)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration' => 'required|integer|min:1',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.value' => 'required|integer|min:1', // New field for question value
            'questions.*.options' => 'required|array|max:5',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.correct' => 'required|string',
        ]);
    
        $questions = [];
        foreach ($validatedData['questions'] as $index => $question) {
            $questions[] = [
                'text' => $question['text'],
                'value' => $question['value'], // Save question value
                'correct' => $question['correct'],
                'options' => $question['options']
            ];
        }
    
        CourseAssessment::create([
            'course_id' => $course->id,
            'name' => $validatedData['name'],
            'questions' => json_encode($questions),
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'duration' => $validatedData['duration'],
        ]);
    
        return redirect()->route('admin.courses.assessments.index', $course->id)
            ->with('success', 'Course assessment created successfully.');
    }
    

    // Show a specific assessment
    public function show(Course $course, CourseAssessment $assessment)
    {
        $assessment->questions = json_decode($assessment->questions, true);
        return view('student.course_assessments.show', compact('course', 'assessment'));
    }

    // Show form to edit a course assessment
    public function edit(Course $course, CourseAssessment $assessment)
    {
        $assessment->questions = json_decode($assessment->questions, true);
        return view('admin.course_assessments.edit', compact('course', 'assessment'));
    }

    // Update the assessment
    public function update(Request $request, Course $course, CourseAssessment $assessment)
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

        $assessment->update([
            'name' => $validatedData['name'],
            'questions' => json_encode($validatedData['questions']),
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
        ]);

        return redirect()->route('admin.courses.assessments.index', $course->id)
            ->with('success', 'Course assessment updated successfully.');
    }

    // Delete an assessment
    public function destroy(Course $course, CourseAssessment $assessment)
    {
        $assessment->delete();
        return redirect()->route('admin.courses.assessments.index', $course->id)
            ->with('success', 'Course assessment deleted successfully.');
    }

    // Show the assessment attempt page for students
    public function attempt(Course $course, CourseAssessment $assessment)
    {
        $currentDate = Carbon::now();

        // Check if assessment is within available time window
        if ($currentDate->lt(Carbon::parse($assessment->start_time)) || $currentDate->gt(Carbon::parse($assessment->end_time))) {
            return redirect()->back()->with('error', 'The assessment is not available at this time.');
        }

        $assessment->questions = json_decode($assessment->questions, true);

        return view('student.course_assessments.attempt', compact('course', 'assessment'));
    }

    // Submit the assessment attempt
    public function submit(Request $request, Course $course, CourseAssessment $assessment)
    {
        $validatedData = $request->validate([
            'answers' => 'required|array',
        ]);

        // Calculate score and save the result
        $totalScore = 0;
        $questions = json_decode($assessment->questions, true);

        foreach ($questions as $index => $question) {
            if (isset($validatedData['answers'][$index]) && $validatedData['answers'][$index] == $question['correct']) {
                $totalScore++;
            }
        }

        $score = ($totalScore / count($questions)) * 100;

        // Store assessment submission
        CourseAssessmentSubmission::create([
            'user_id' => auth()->id(),
            'assessment_id' => $assessment->id,
            'score' => $score,
            'answers' => json_encode($validatedData['answers']),
        ]);

        return redirect()->route('student.courses.assessments.show', [$course, $assessment])
                         ->with('success', 'Assessment submitted successfully.');
    }

    // Admin view of assessment result
    public function showResult(CourseAssessment $assessment, $submissionId)
    {
        $submission = CourseAssessmentSubmission::findOrFail($submissionId);
        return view('admin.course_assessments.result', compact('assessment', 'submission'));
    }
}
