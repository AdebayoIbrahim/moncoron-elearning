@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')

<div class="container">
    <h1>{{ $routeNamePart }}</h1>
    <div class="lesson-content">
        <h2>{{ $lesson->title }} - Assessment</h2>
        @if($assessment)
            <form action="{{ route('student.assessments.submit', ['courseId' => $lesson->course_id, 'lessonId' => $lessonId]) }}" method="POST">
                @csrf
                @foreach(json_decode($assessment->questions, true) as $index => $question)
                    <div class="form-group mb-4">
                        <label>{{ $index + 1 }}. {{ $question['question'] }}</label>
                        @foreach($question['options'] as $option)
                            <div class="form-check">
                                <input type="radio" name="answers[{{ $index }}]" value="{{ $option }}" class="form-check-input" id="option-{{ $index }}-{{ $loop->index }}" required>
                                <label class="form-check-label" for="option-{{ $index }}-{{ $loop->index }}">{{ $option }}</label>
                            </div>
                        @endforeach
                        @if(isset($question['media']))
                            @foreach($question['media'] as $media)
                                @if(str_contains($media, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ Storage::url($media) }}" class="img-fluid mb-2" alt="Question Image">
                                @elseif(str_contains($media, ['.mp3']))
                                    <audio controls class="mb-2">
                                        <source src="{{ Storage::url($media) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @elseif(str_contains($media, ['.mp4', '.avi']))
                                    <video controls class="mb-2">
                                        <source src="{{ Storage::url($media) }}" type="video/mp4">
                                        Your browser does not support the video element.
                                    </video>
                                @endif
                            @endforeach
                        @endif
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
