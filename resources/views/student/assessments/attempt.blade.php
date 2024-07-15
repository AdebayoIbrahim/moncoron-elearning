@extends('layouts.app')
@section('content')
@include('partials.admin_header')
<div class="container-fluid">
    <div class="row">
        @include('partials.student_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <div class="card">
                <div class="card-header">
                    <h2>Attempt Assessment for {{ $course->name }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('student.courses.assessments.submit', [$course->id, $assessment->id]) }}" method="post">
                        @csrf
                        <div id="questions-container">
                            @foreach ($assessment->questions as $questionIndex => $question)
                                <div class="form-group mb-4">
                                    <label>{{ $questionIndex + 1 }}. {{ $question['text'] }}</label>
                                    @if (isset($question['media']))
                                        <div>
                                            @if (str_contains($question['media'], '.mp4'))
                                                <video controls>
                                                    <source src="{{ Storage::url($question['media']) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @elseif (str_contains($question['media'], '.mp3'))
                                                <audio controls>
                                                    <source src="{{ Storage::url($question['media']) }}" type="audio/mp3">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            @endif
                                        </div>
                                    @endif
                                    @foreach ($question['options'] as $optionIndex => $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="questions[{{ $questionIndex }}]" value="{{ $optionIndex }}" id="question{{ $questionIndex }}option{{ $optionIndex }}" required>
                                            <label class="form-check-label" for="question{{ $questionIndex }}option{{ $optionIndex }}">
                                                {{ $option['text'] }}
                                                @if (isset($option['media']))
                                                    @if (str_contains($option['media'], '.mp4'))
                                                        <video controls>
                                                            <source src="{{ Storage::url($option['media']) }}" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @elseif (str_contains($option['media'], '.mp3'))
                                                        <audio controls>
                                                            <source src="{{ Storage::url($option['media']) }}" type="audio/mp3">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    @endif
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Assessment</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection