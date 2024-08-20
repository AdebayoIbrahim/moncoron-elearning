@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')

<div class="container">
    <h1>Assessments for Course: {{ $courseId }}</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create New Assessment Button -->
    <a href="{{ route('assessments.create', ['courseId' => $courseId, 'lessonId' => $lessonId]) }}" class="btn btn-primary mb-3">Create New Assessment</a>

    <!-- Assessments Table -->
    @if($assessments->isEmpty())
        <p>No assessments found for this course.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lesson</th>
                    <th>Questions (Preview)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    @foreach ($assessments as $assessment)
        <tr>
            <td>{{ $assessment->id }}</td>
            <td>{{ $assessment->lesson_id }}</td>
            <td>
                @php
                    $questions = json_decode($assessment->questions, true);
                @endphp
                @if ($questions)
                    <ol>
                        @foreach ($questions as $key => $question)
                            <li>
                                <strong>{{ $question['question'] }}</strong>
                                <ul>
                                    @foreach ($question['options'] as $option)
                                        <li>{{ $option }}</li>
                                    @endforeach
                                </ul>
                                <p>Correct Answer: {{ $question['correct_option'] }}</p>
                                <p>Value: {{ $question['value'] }} Points</p>
                            </li>
                        @endforeach
                    </ol>
                @else
                    No Questions
                @endif
            </td>
            <td>
                <a href="{{ route('lesson_assessments.edit', ['courseId' => $courseId, 'lessonId' => $assessment->lesson_id, 'id' => $assessment->id]) }}" class="btn btn-primary">Edit</a>
            </td>
        </tr>
    @endforeach
</tbody>

        </table>
    @endif
</div>
@endsection
