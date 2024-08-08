@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')

    <div class="container">
        <h1>{{ $routeNamePart }}</h1>
        <div class="lesson-content">
            <h2>{{ $lesson->title }} - Assessment</h2>
            @if($assessment)
                <form action="{{ route('lessons.submitAssessment', ['course_id' => $lesson->course_id, 'lesson_id' => $lesson->id]) }}" method="POST">
                    @csrf
                    @foreach(json_decode($assessment->questions, true) as $index => $question)
                        <div class="form-group">
                            <label>{{ $index + 1 }}. {{ $question['question'] }}</label>
                            @foreach($question['options'] as $option)
                                <div class="form-check">
                                    <input type="radio" name="answers[{{ $index }}]" value="{{ $option }}" class="form-check-input" id="option-{{ $index }}-{{ $loop->index }}">
                                    <label class="form-check-label" for="option-{{ $index }}-{{ $loop->index }}">{{ $option }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary">Submit Assessment</button>
                </form>
            @else
                <p>No assessment available for this lesson.</p>
            @endif
        </div>
    </div>
@endsection
