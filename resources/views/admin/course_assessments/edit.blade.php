@extends('layouts.adminapp')

@section('content')
@include('partials.admin_header')

<div class="container">
    <h2>Edit Assessment for {{ $course->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.course_assessments.update', ['course' => $course->id, 'assessment' => $assessment->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Assessment Name -->
        <div class="form-group">
            <label for="name">Assessment Name</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $assessment->name }}" required>
        </div>

        <!-- Start Time -->
        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="datetime-local" name="start_time" class="form-control" id="start_time" value="{{ \Carbon\Carbon::parse($assessment->start_time)->format('Y-m-d\TH:i') }}" required>
        </div>

        <!-- End Time -->
        <div class="form-group">
            <label for="end_time">End Time</label>
            <input type="datetime-local" name="end_time" class="form-control" id="end_time" value="{{ \Carbon\Carbon::parse($assessment->end_time)->format('Y-m-d\TH:i') }}" required>
        </div>

        <!-- Questions Container -->
        <div id="questions-container">
            @foreach($assessment->questions as $index => $question)
            <div class="question">
                <div class="form-group">
                    <label for="question_{{ $index }}">Question {{ $index + 1 }}</label>
                    <input type="text" name="questions[{{ $index }}][text]" class="form-control" id="question_{{ $index }}" value="{{ $question['text'] }}" required>
                </div>

                <!-- Options Section -->
                <div class="form-group">
                    <label for="options">Options</label>
                    @foreach($question['options'] as $optionKey => $option)
                    <div class="option">
                        <label for="option_{{ $optionKey }}_{{ $index }}">{{ strtoupper($optionKey) }}:</label>
                        <input type="text" name="questions[{{ $index }}][options][{{ $optionKey }}][text]" class="form-control" id="option_{{ $optionKey }}_{{ $index }}" value="{{ $option['text'] }}" required>
                    </div>
                    @endforeach
                </div>

                <!-- Correct Option Dropdown -->
                <div class="form-group">
                    <label for="correct_option_{{ $index }}">Correct Option</label>
                    <select name="questions[{{ $index }}][correct]" class="form-control" id="correct_option_{{ $index }}" required>
                        <option value="A" {{ $question['correct'] == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ $question['correct'] == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ $question['correct'] == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ $question['correct'] == 'D' ? 'selected' : '' }}>D</option>
                    </select>
                </div>

                <!-- Question Value -->
                <div class="form-group">
                    <label for="value_{{ $index }}">Question Value</label>
                    <input type="number" name="questions[{{ $index }}][value]" class="form-control" id="value_{{ $index }}" value="{{ $question['value'] }}" required>
                </div>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Assessment</button>
    </form>
</div>
@endsection
