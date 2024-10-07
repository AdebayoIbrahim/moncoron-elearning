@extends('layouts.app')
@section('content')
@include('partials.header')
@vite(['resources/css/create-assessment/assessment.css','resources/js/custom-editor/editor.js','resources/js/courseview/course.js'])
@csrf

<div class="container-fluid">

    <div class="row">
        @include('partials.student_sidebar')
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
                    <h2 class="text-bold">
                        {{$course->name}} <a class="btn btn-primary pull-right" href="/courses"><i class="fa fa-arrow-left"></i> Go Back</a>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">{{$course->description}}</div>
                    <hr />
                    <div class="d-flex justify-content-between">
                        <h2 class=" text-bold ">Lessons </h2>
                        {{-- no add- lesson-forstdent --}}
                    </div>

                    @if($lessons->isEmpty())
                    <h2 class="text-danger text  pt-5 ">No lessons are available for this course yet!!!</h2>
                    @else
                    <div class="lesson_layer">
                        <div class="d-flex  align-center flex-wrap pt-3 gap-3">
                            @foreach($lessons as $lesson)
                            <div class="container_lesson_body" data-attribute={{$lesson->is_accessible ? "accessible" : "pending"}}>
                                <div class=" container_content">
                                    <div style="font-weight: 450;font-size: 1.2rem; color :rgba(5, 30, 45, 1) ">
                                        1.{{$loop->index + 1}} LESSON
                                    </div>
                                    <div class="target_holder" style="display:none">
                                        {{$lesson->lesson_number}}
                                    </div>
                                    <!-- lesson-name -->
                                    <h3 class="container_text">{{$lesson->name}}</h3>
                                    <!-- comments-if-avvailable -->
                                    <div class="d-flex align-items-center gap-5">
                                        <p class="container_text mt-2">
                                            {{$lesson->comment ?? 0}} Comment
                                        </p>
                                        @if($lesson->is_accessible)
                                        <i class="fa-solid fa-circle-play styled-icon play-btn"></i>
                                        @else
                                        <i class="fa-solid fa-lock styled-icon"></i>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="btn_side">
                            <button id="next_btn_lesson" type="button">NEXT</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
    </div>
    </main>
</div>
</div>

<!-- modal_popup -->
<div class=" modal modal-lg fade" id="editore_modal_overlay_lesson" tabindex="-1" aria-labelledby="Editor_modal_lessons" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex justify-content-between" style="width: 100%">
                    <h1 class="modal-title fs-5 text-bold text-primary" id="Editor_modal">
                        Add Lessons</h1>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-success" id="all_lesson_done">Done</button>
                        <button type="button" class="btn btn-danger" id="close_modal_lesson">Close</button>
                    </div>

                </div>

            </div>

            <div class="modal-body">
                <div class="container">
                    <!-- Editor Container -->
                    <div class="lessonname pt-1 pb-2">
                        <input class="form-control" id="lesson_name" type="text" placeholder="Input Lesson Name" aria-label="input lesson name" />
                    </div>
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
                            <p>{!! old('content', $existingContent ?? 'Lesson Description
                                Goes Here') !!}</p>
                        </div>
                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
