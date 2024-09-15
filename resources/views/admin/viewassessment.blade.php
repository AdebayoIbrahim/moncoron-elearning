@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
@csrf
@vite(['resources/css/create-assessment/assessment.css','resources/js/create-assessment/assessment.js','resources/js/custom-editor/editor.js'])


<div class="container">
    <div class="nav_header">
        <h4>
            Welcome Back, Here is The Newly Created Lesson {{$lesson_id}} assessment For Course "<i>{{$fetch_course_name}}</i>"
        </h4>
        <div class="pt-3 d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-primary btn-md">Edit</button>
            <button type="button" class="btn btn-secondary btn-md " id="create_assessment " disabled>Save Changes</button>
        </div>
    </div>
    {{-- question_area --}}
    <div class="content_questions_review pb-sm-1" id="questions-container">
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
                            <img src="{{ asset('storage/' . $question['media']['image_path']) }}" alt="Question Image" class="pop_upload_file">
                            @endif
                            @if(!empty($question['media']['audio_path']))
                            <audio controls style="height: 50px" src="{{ asset('storage/' . $question['media']['audio_path']) }}">
                            </audio>
                            @endif
                            @if(!empty($question['media']['video_path']))
                            <video controls class="pop_upload_file" src="{{ asset('storage/' . $question['media']['video_path']) }}">
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
                        <label for="option_{{ strtolower ($alphabet[$index]) }}_{{ $loop->index}}">
                            {{ $alphabet[$index] }}:
                        </label>
                        <div class="custom-editor" id="custom-editor-{{$loop->index}}-{{ $option['id'] }}">
                            <div class="editor-content {{$option['is_correct'] === true ? 'correct_option_ticked' : ''}}" id="editor-content-{{$loop->index}}-{{ $option['id'] }}">
                                {{-- if-text --}}
                                @if(!empty($option['option_text']))
                                <p>{{ $option['option_text'] }}</p>
                                @endif

                                <!-- Media -->
                                @if(!empty($option['media']))
                                @if(!empty($option['media']['image_path']))
                                <img src="{{ asset('storage/' . $option['media']['image_path']) }}" alt="Question Image">
                                @endif
                                @if(!empty($option['media']['audio_path']))
                                <audio controls style="height: 50px" src="{{ asset('storage/' . $option['media']['audio_path']) }}">
                                </audio>
                                @endif
                                @if(!empty($option['media']['video_path']))
                                <video controls src="{{ asset('storage/' .$option['media']['video_path']) }}">
                                </video>
                                @endif
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
                    <!-- Question Value -->
                    <div class="form-group pt-2">
                        <label for="value_{{$loop->index}}">Question Point</label>
                        <input type="number" name="questions[{{$loop->index}}][value]" class="form-control question_value" id="value_{{$loop->index}}" value={{$question['points']}} required>
                    </div>
                    <!-- Remove question button -->
                    <button type="button" class="btn btn-danger btn-sm remove-question mt-3">Remove Question</button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Add question button -->
        <div class="d-flex gap-3 pb-sm-2">
            <button type="button" id="add-question" class="btn btn-secondary mt-3">Add Question</button>
        </div>
    </div>


    {{-- modal-if-edit --}}
    <!-- modal_popup -->
    <div class="modal modal-lg fade" id="editore_modal_overlay" tabindex="-1" aria-labelledby="Editor_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex justify-content-between" style="width: 100%">
                        <h1 class="modal-title fs-5 text-bold text-primary" id="Editor_modal">Edit Question</h1>
                        <div class="d-flex gap-3">
                            <button type="button" class="btn btn-success" id="editor_done">Done</button>
                            <button type="button" class="btn btn-danger" id="close_modal">Close</button>
                        </div>

                    </div>

                </div>

                <div class="modal-body">
                    <div class="container">
                        <!-- Editor Container -->
                        <div id="editor-container">
                            <!-- Toolbar -->
                            <div id="editor-toolbar">
                                <button type="button" id="bold-btn" title="Bold"><i class="fas fa-bold"></i></button>
                                <button type="button" id="italic-btn" title="Italic"><i class="fas fa-italic"></i></button>
                                <button type="button" id="underline-btn" title="Underline"><i class="fas fa-underline"></i></button>
                                <button type="button" id="h1-btn" title="Heading 1"><i class="fas fa-heading"></i>
                                    1</button>
                                <button type="button" id="h2-btn" title="Heading 2"><i class="fas fa-heading"></i>
                                    2</button>
                                <button type="button" id="ul-btn" title="Unordered List"><i class="fas fa-list-ul"></i></button>
                                <button type="button" id="ol-btn" title="Ordered List"><i class="fas fa-list-ol"></i></button>
                                <button type="button" id="blockquote-btn" title="Blockquote"><i class="fas fa-quote-right"></i></button>
                                <button type="button" id="link-btn" title="Insert Link"><i class="fas fa-link"></i></button>
                                <button type="button" id="undo-btn" title="Undo"><i class="fas fa-undo"></i></button>
                                <button type="button" id="redo-btn" title="Redo"><i class="fas fa-redo"></i></button>
                                <input type="color" id="text-color-picker" title="Text Color">
                                <input type="color" id="bg-color-picker" title="Background Color">

                                <!-- Icons for Image and Video Uploads -->
                                <button type="button" id="image-icon" title="Insert Image"><i class="fas fa-image"></i></button>
                                <button type="button" id="video-icon" title="Insert Video"><i class="fas fa-video"></i></button>
                                <button type="button" id="audio-icon" title="Insert Audio">
                                    <i class="fa-solid fa-music"></i></button>

                                <!-- Hidden file inputs -->
                                <input type="file" id="image-upload" accept="image/*" style="display: none;">
                                <input type="file" id="video-upload" accept="video/*" style="display: none;">
                                <input type="file" id="audio-upload" accept="audio/*" style="display: none;">

                                <button type="button" id="table-btn" title="Insert Table"><i class="fas fa-table"></i></button>
                                <button type="button" id="video-url-btn" title="Embed Video URL"><i class="fas fa-link"></i></button>
                            </div>

                            <!-- Editable Area -->
                            <div id="custom-editor" contenteditable="true" aria-details="content_placeholder">
                                <!-- //TODO : a p element from js here -->
                                {{-- <p>{!! old('content', $existingContent ?? 'Start Typing') !!}</p> --}}
                            </div>
                        </div>
                        <!-- </form> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
