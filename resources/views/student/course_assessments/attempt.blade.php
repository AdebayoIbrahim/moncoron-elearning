@extends('layouts.app')
@section('content')
@include('partials.admin_header')
<div class="container mt-5">
    <h2>Attempt Assessment for {{ $course->name }}</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('student.courses.assessments.submit', [$course->id, $assessment->id]) }}" method="POST">
        @csrf

        @foreach($assessment->questions as $index => $question)
            <div class="mb-4">
                <h5>{{ $index + 1 }}. {{ $question['text'] }}</h5>

                @foreach($question['options'] as $optionIndex => $option)
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="answers[{{ $index }}]" value="{{ $optionIndex }}" required>
                        <label class="form-check-label">{{ $option['text'] }}</label>
                    </div>
                @endforeach
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Submit Assessment</button>
    </form>
</div>
@endsection
