@extends('layouts.adminapp')

@section('content')
<div class="container mt-5">
    <h2>Assessment Result for {{ $assessment->name }}</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Score:</strong> {{ $submission->score }}%</p>
            <p><strong>Submission Time:</strong> {{ $submission->created_at->format('Y-m-d H:i:s') }}</p>

            <h4>Answers:</h4>
            @php
                $questions = json_decode($assessment->questions, true);
                $studentAnswers = json_decode($submission->answers, true);
            @endphp

            @foreach($questions as $index => $question)
                <div class="mb-3">
                    <p><strong>Question {{ $index + 1 }}:</strong> {{ $question['text'] }}</p>

                    <ul>
                        @foreach($question['options'] as $optionKey => $option)
                            <li>
                                <strong>{{ strtoupper($optionKey) }}:</strong> {{ $option['text'] }}

                                @if($question['correct'] == $optionKey)
                                    <span class="text-success">(Correct Answer)</span>
                                @endif

                                @if(isset($studentAnswers[$index]) && $studentAnswers[$index] == $optionKey)
                                    @if($studentAnswers[$index] == $question['correct'])
                                        <span class="text-success">(Student's Answer - Correct)</span>
                                    @else
                                        <span class="text-danger">(Student's Answer - Incorrect)</span>
                                    @endif
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
