@extends('layouts.app')

@section('content')
@include('partials.admin_header')

<div class="container mt-5">
    <h2>Assessment Result for Lesson: {{ $lesson->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if ($assessmentPassed)
                <h3 class="text-success">Congratulations! You passed the assessment with a score of {{ $userAssessment->score }}%!</h3>
                <p>Well done! You are now ready to move on to the next lesson.</p>

                @if (!$allLessonsCompleted && $nextLesson)
                    <a href="{{ $nextLessonRoute }}" class="btn btn-primary">Proceed to Next Lesson: {{ $nextLesson->name }}</a>
                @elseif ($allLessonsCompleted)
                    <p>You have completed all lessons in this course. Great job!</p>
                    @if (isset($certificateRoute))
                        <a href="{{ $certificateRoute }}" class="btn btn-success">Download Your Certificate</a>
                    @endif
                    <a href="{{ $currentLessonRoute }}" class="btn btn-primary">Return to Lesson Chat</a>
                @endif
            @else
                <h3 class="text-danger">You scored {{ $userAssessment->score }}%. You need at least 70% to pass.</h3>
                <p>Don't worry, you can try again! Please review the lesson and retake the assessment.</p>
                
                <a href="{{ $retakeLessonRoute }}" class="btn btn-danger">Retake Lesson</a>
                <a href="{{ $retakeAssessmentRoute }}" class="btn btn-warning">Retake Assessment</a>
            @endif
        </div>
    </div>
</div>
@endsection
