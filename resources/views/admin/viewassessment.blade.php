@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
@csrf
@vite(['resources/css/create-assessment/assessment.css',''])
 

<div class="container">
<div class="nav_header">
    <h4>
        Welcome Back,  Here is The Newly Created Lesson {{$lesson_id}} assessment  For Course "<i>{{$fetch_course_name}}</i>"
    </h4>
    <div class="pt-3 d-flex justify-content-end gap-3">
        <button type="button" class="btn btn-primary btn-md" >Edit</button>
        <button type="button" class="btn btn-secondary btn-md" disabled>Save Changes</button>
    </div>
</div>
{{-- question_area --}}
<div class="content_questions_review pb-sm-3">
     <!-- Time limit input -->
    <div class="form-group">
        <label for="time_limit">Assessment Time Limit (in minutes)</label>
        <input type="number" name="time_limit" class="form-control" id="time_limit" value={{$assessments["general_time_limit"]}}>
    </div>
    {{-- question_container --}}
    @foreach($assessments['questions'] as $question)
        <div id="question-container">

            <div class="question">
                <div class="form-group">
                    <label for="question_{{$loop->index}}">Question {{$question['id']}}</label>
                    <!-- Custom Editor for the Question -->
                    <div class="custom-editor" id="custom-editor-{{$loop->index}} ">
                        <div aria-details="content_container" class="editor-content parent_editor" id="editor-content-{{$loop->index}}">
                        {{-- 
                        check-if-questions-exist-as-suppose-to-be --}}
                        {{-- question_text --}}
                        @if(!empty($question['question_text']))
                            <p>{{$question['question_text']}}</p>
                        @endif
                        <!-- Media -->
                        @if(!empty($question['media']))
                        @if(!empty($question['media']['image_path']))
                        <img src="{{ $question['media']['image_path'] }}" alt="Question Image" >
                        @endif
                        @if(!empty($question['media']['audio_path']))
                        <audio controls style="height: 50px">
                        <source src="{{ $question['media']['audio_path'] }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                        </audio>
                        @endif
                        @if(!empty($question['media']['video_path']))
                        <video controls>
                        <source src="{{ $question['media']['video_path'] }}" type="video/mp4">
                        Your browser does not support the video tag.
                        </video>
                        @endif
                    @endif
                </div>
           </div>
            </div>
            <!-- Options Section -->
<div class="form-group mt-4 options_group">
    <label for="options">Options</label>
    
    @php
        // Define alphabetical labels
        $alphabet = ['A', 'B', 'C', 'D', 'E'];
    @endphp

    @foreach($question['options'] as $index => $option)
        <div class="option">
            <label for="option_{{ $option['id'] }}_{{$loop->index}}">
                {{ $alphabet[$index] }}:
            </label>
            <div class="custom-editor" id="custom-editor-{{$loop->index}}-{{ $option['id'] }}">
                <div class="editor-content" id="editor-content-{{$loop->index}}-{{ $option['id'] }}">
                    {{ $option['option_text'] }}
                </div>
                @if($option['is_correct'] === 'true')
                    <span class="badge bg-success">Correct</span>
                @endif
            </div>
        </div>
    @endforeach
</div>

        {{-- correct-options --}}
<div class="form-group">
    <label for="correct_option_{{$loop->index}}">Correct Option</label>
    <select name="questions[{{$loop->index}}][correct_option]" class="form-control correct_option" id="correct_option_{{$loop->index}}" required>
        @php
            // Define alphabetical labels
            $alphabet = ['A', 'B', 'C', 'D', 'E'];
        @endphp

        @foreach($question['options'] as $index => $option)
            <option value="{{ $option['id'] }}" {{ $option['is_correct'] === "true" ? 'selected' : '' }}>
                {{ $alphabet[$index] }}
            </option>
        @endforeach
    </select>
</div>


            </div>

        </div>
    @endforeach
</div>

</div>
