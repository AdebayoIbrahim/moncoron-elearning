@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <div class="card">
                <div class="card-header">
                    <h2>Assessment Result for {{ $assessment->name }}</h2>
                </div>
                <div class="card-body">
                    <p>Your Score: {{ $submission->score }}</p>
                    <ul>
                        @foreach ($assessment->questions as $questionIndex => $question)
                            <li>
                                <p>{{ $question['text'] }}</p>
                                @if (isset($question['media']) && is_string($question['media']))
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
                                <ul>
                                    @foreach ($question['options'] as $optionIndex => $option)
                                        <li>
                                            <p>{{ $option['text'] }}
                                                @if ($option['correct'])
                                                    <span class="badge bg-success">Correct</span>
                                                @endif
                                                @if (isset($submission->answers[$questionIndex]) && $submission->answers[$questionIndex] == $optionIndex)
                                                    <span class="badge {{ $option['correct'] ? 'bg-primary' : 'bg-danger' }}">Your Answer</span>
                                                @endif
                                            </p>
                                            @if (isset($option['media']) && is_string($option['media']))
                                                <div>
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
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
