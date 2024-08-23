@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <div class="card">
                <div class="card-header">
                    <h2>View Assessment for {{ $course->name }}</h2>
                </div>
                <div class="card-body">
                    <h3>{{ $assessment->name }}</h3>
                    <p>Duration: {{ $assessment->duration }} minutes</p>
                    <div id="questions-container">
                        @foreach($assessment->questions as $questionIndex => $question)
                            <div class="form-group question-group" data-question="{{ $questionIndex }}">
                                <h4>Question {{ $questionIndex + 1 }}</h4>
                                <div>{!! $question['text'] !!}</div>
                                <div id="options-container-{{ $questionIndex }}">
                                    @foreach($question['options'] as $optionIndex => $option)
                                        <div class="form-group option-group">
                                            <label>Option {{ chr(65 + $optionIndex) }}</label>
                                            <div>{{ $option['text'] }}</div>
                                            <div>{{ $option['correct'] ? 'Correct' : '' }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
