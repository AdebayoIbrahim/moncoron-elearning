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
<div class="content_questions_review">
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
                    </div>
                    <!-- <input type="hidden" name="questions[0][question]" id="hidden-editor-content-0"> -->
                </div>
                </div>
            </div>

        </div>
    @endforeach
</div>

</div>
