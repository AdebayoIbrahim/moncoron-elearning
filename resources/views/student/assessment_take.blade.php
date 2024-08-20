@extends('layouts.app')
@section('content')
@include('partials.admin_header')

<div class="container">
    <h2>Take Assessment for Lesson: {{ $lesson->name }}</h2>
    
    <form action="{{ route('student.assessments.submit', ['courseId' => $courseId, 'lessonId' => $lessonId]) }}" method="POST">
        @csrf

        <div id="timer" class="mb-3">
            <h3>Time Left: <span id="countdown">00:00</span></h3>
        </div>

        @foreach (json_decode($assessment->questions, true) as $index => $question)
            <div class="question mb-4">
                <h4>{{ $index + 1 }}. {{ $question['question'] }}</h4>

                @if (isset($question['media']))
                    @foreach ($question['media'] as $media)
                        @if (str_contains($media, ['.jpg', '.jpeg', '.png', '.gif']))
                            <img src="{{ Storage::url($media) }}" class="img-fluid mb-2" alt="Question Image">
                        @elseif (str_contains($media, ['.mp3']))
                            <audio controls class="mb-2">
                                <source src="{{ Storage::url($media) }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        @elseif (str_contains($media, ['.mp4', '.avi']))
                            <video controls class="mb-2">
                                <source src="{{ Storage::url($media) }}" type="video/mp4">
                                Your browser does not support the video element.
                            </video>
                        @endif
                    @endforeach
                @endif

                <div class="form-group">
                    @foreach ($question['options'] as $optionKey => $option)
                        <div class="form-check">
                            <input type="radio" name="answers[{{ $index }}]" value="{{ $optionKey }}" class="form-check-input" required>
                            <label class="form-check-label">{{ $optionKey }}: {{ $option }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <input type="hidden" name="time_taken" id="time_taken" value="0">

        <button type="submit" class="btn btn-primary">Submit Assessment</button>
    </form>
</div>

<script>
    let totalTime = {{ $assessment->time_limit * 60 }}; // Convert minutes to seconds
    const countdownElement = document.getElementById('countdown');
    const timeTakenElement = document.getElementById('time_taken');
    let interval;

    function startTimer() {
        interval = setInterval(function () {
            let minutes = Math.floor(totalTime / 60);
            let seconds = totalTime % 60;
            countdownElement.innerText = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            timeTakenElement.value = {{ $assessment->time_limit * 60 }} - totalTime;
            if (totalTime <= 0) {
                clearInterval(interval);
                document.forms[0].submit(); // Auto-submit form when time runs out
            }

            totalTime--;
        }, 1000);
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        startTimer();
    });
</script>
@endsection
