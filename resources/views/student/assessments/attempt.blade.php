@extends('layouts.app')
@section('content')
@include('partials.admin_header')

<div class="container-fluid">
    <div class="row">
        @include('partials.admin_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <div class="card">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session('error') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card-header">
                    <h2>Attempt Assessment for {{ $course->name }}</h2>
                    <div id="timer" class="float-right"></div>
                </div>
                <div class="card-body">
                    <form id="assessment-form" action="{{ route('student.courses.assessments.submit', [$course->id, $assessment->id]) }}" method="POST">
                        @csrf
                        <div id="question-container">
                            @foreach ($assessment->questions as $questionIndex => $question)
                                <div class="question" data-question-index="{{ $questionIndex }}" style="display: none;">
                                    <p>{{ $questionIndex + 1 }}. {{ $question['text'] }}</p>
                                    @if (isset($question['media']))
                                        <div>
                                            @if (preg_match('/\.(mp4|avi|mkv)$/i', $question['media']))
                                                <video controls>
                                                    <source src="{{ Storage::url($question['media']) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @elseif (preg_match('/\.(mp3|wav)$/i', $question['media']))
                                                <audio controls>
                                                    <source src="{{ Storage::url($question['media']) }}" type="audio/mp3">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            @else
                                                <img src="{{ Storage::url($question['media']) }}" alt="Question Media" class="img-fluid">
                                            @endif
                                        </div>
                                    @endif
                                    <ul style="list-style-type: none;">
                                        @foreach ($question['options'] as $optionIndex => $option)
                                            <li>
                                                <label>
                                                    {{ chr(65 + $optionIndex) }}. 
                                                    <input type="radio" name="questions[{{ $questionIndex }}]" value="{{ $optionIndex }}" required>
                                                    {{ $option['text'] }}
                                                </label>
                                                @if (isset($option['media']))
                                                    <div>
                                                        @if (preg_match('/\.(mp4|avi|mkv)$/i', $option['media']))
                                                            <video controls>
                                                                <source src="{{ Storage::url($option['media']) }}" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        @elseif (preg_match('/\.(mp3|wav)$/i', $option['media']))
                                                            <audio controls>
                                                                <source src="{{ Storage::url($option['media']) }}" type="audio/mp3">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        @else
                                                            <img src="{{ Storage::url($option['media']) }}" alt="Option Media" class="img-fluid">
                                                        @endif
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="prev-question" class="btn btn-secondary" style="display: none;">Previous Question</button>
                        <button type="button" id="next-question" class="btn btn-primary">Next Question</button>
                        <button type="submit" id="submit-assessment" class="btn btn-success" style="display: none;">Submit Assessment</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentQuestionIndex = 0;
    const questions = document.querySelectorAll('.question');
    const totalQuestions = questions.length;
    const duration = {{ $assessment->duration * 60 }}; // Duration in seconds

    function showQuestion(index) {
        questions.forEach((question, idx) => {
            question.style.display = (idx === index) ? 'block' : 'none';
        });

        document.getElementById('prev-question').style.display = (index > 0) ? 'inline-block' : 'none';
        document.getElementById('next-question').style.display = (index < totalQuestions - 1) ? 'inline-block' : 'none';
        document.getElementById('submit-assessment').style.display = (index === totalQuestions - 1) ? 'inline-block' : 'none';
    }

    document.getElementById('next-question').addEventListener('click', function() {
        if (currentQuestionIndex < totalQuestions - 1) {
            currentQuestionIndex++;
            showQuestion(currentQuestionIndex);
        }
    });

    document.getElementById('prev-question').addEventListener('click', function() {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            showQuestion(currentQuestionIndex);
        }
    });

    function startTimer(duration, display) {
        let timer = duration, minutes, seconds;
        const interval = setInterval(function() {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = "Time Remaining: " + minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(interval);
                document.getElementById('assessment-form').submit();
            }
        }, 1000);
    }

    showQuestion(currentQuestionIndex);
    startTimer(duration, document.getElementById('timer'));
});
</script>
@endsection
