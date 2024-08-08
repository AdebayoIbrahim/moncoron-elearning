@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h2>Assessment for Lesson {{ $assessment->lesson_id }}</h2>
    <form action="{{ route('assessments.submit', $assessment->lesson_id) }}" method="POST">
        @csrf
        @foreach(json_decode($assessment->questions) as $index => $question)
            <div class="question">
                <h4>{{ $index + 1 }}. {{ $question->question }}</h4>
                @foreach($question->options as $option)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answers[{{ $index }}]" value="{{ $option }}" required>
                        <label class="form-check-label">
                            {{ $option }}
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Submit Assessment</button>
    </form>
</div>
@endsection
