@extends('layouts.adminapp')

@section('content')
@include('partials.admin_header')

<div class="container">
    <h2>Assessment: {{ $assessment->name }} ({{ $course->name }})</h2>

    <p><strong>Start Time:</strong> {{ $assessment->start_time }}</p>
    <p><strong>End Time:</strong> {{ $assessment->end_time }}</p>

    <h3>Questions</h3>
    <ul>
        @foreach($assessment->questions as $index => $question)
        <li>
            <strong>Question {{ $index + 1 }}:</strong> {{ $question['text'] }}
            <ul>
                @foreach($question['options'] as $optionKey => $option)
                <li>{{ strtoupper($optionKey) }}: {{ $option['text'] }} @if($question['correct'] == $optionKey) <strong>(Correct)</strong> @endif</li>
                @endforeach
            </ul>
            <p><strong>Value:</strong> {{ $question['value'] }}</p>
        </li>
        @endforeach
    </ul>

    <a href="{{ route('admin.course_assessments.edit', ['course' => $course->id, 'assessment' => $assessment->id]) }}" class="btn btn-warning">Edit</a>
</div>
@endsection
