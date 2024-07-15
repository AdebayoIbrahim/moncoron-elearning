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
                    <h2>Assessment for {{ $course->name }}</h2>
                </div>
                <div class="card-body">
                    @if (!empty($assessment->questions))
                        @foreach ($assessment->questions as $question)
                            <div>
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
                                    @foreach ($question['options'] as $option)
                                        <li>
                                            <p>{{ $option['text'] }}</p>
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
                            </div>
                        @endforeach
                    @else
                        <p>No questions found for this assessment.</p>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

