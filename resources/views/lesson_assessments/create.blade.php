@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
@vite(['resources/js/create-assessment/assessment.js','resources/css/create-assessment/assessment.css'])
<div class="container">
    <h2>Create Assessment for Lesson {{ $lessonId }}</h2>

    <!-- Success and Error Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('assessments.store', ['courseId' => $courseId, 'lessonId' => $lessonId]) }}" method="POST">
        @csrf

        <!-- Time limit input -->
        <div class="form-group">
            <label for="time_limit">Assessment Time Limit (in minutes)</label>
            <input type="number" name="time_limit" class="form-control" id="time_limit" required>
        </div>

        <!-- Questions Container -->
        <div id="questions-container">
            <div class="question">
                <div class="form-group">
                    <label for="question_0">Question 1</label>
                    <!-- Custom Editor for the Question -->
                    <div class="custom-editor" id="custom-editor-0 ">
                        <div aria-details="content_container " class="editor-content parent_editor"
                            id="editor-content-0 ">
                        </div>
                        <!-- <input type="hidden" name="questions[0][question]" id="hidden-editor-content-0"> -->
                    </div>
                </div>

                <!-- Options Section -->
                <div class="form-group mt-4">
                    <label for="options">Options</label>
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $option)
                    <div class="option">
                        <label for="option_{{ strtolower($option) }}_0">{{ $option }}:</label>
                        <div class="custom-editor" id="custom-editor-0-{{ strtolower($option) }}">
                            <div class="editor-content" id="editor-content-0-{{ strtolower($option) }}"></div>
                            <!-- <input type="hidden" name="questions[0][options][{{ $option }}]"
                                id="hidden-editor-content-0-{{ strtolower($option) }}"> -->
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Correct Option Dropdown -->
                <div class="form-group">
                    <label for="correct_option_0">Correct Option</label>
                    <select name="questions[0][correct_option]" class="form-control" id="correct_option_0" required>
                        @foreach(['A', 'B', 'C', 'D', 'E'] as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Question Value -->
                <div class="form-group">
                    <label for="value_0">Question Value</label>
                    <input type="number" name="questions[0][value]" class="form-control" id="value_0" required>
                </div>

                <!-- Remove question button -->
                <button type="button" class="btn btn-danger btn-sm remove-question">Remove Question</button>
            </div>
        </div>

        <!-- Add question button -->
        <button type="button" id="add-question" class="btn btn-secondary mt-3">Add Question</button>
        <button type="submit" class="btn btn-primary mt-3">Create Assessment</button>



    </form>

    <button id="text_modal">Testmodal</button>

</div>

<!-- modal_popup -->
<div class="modal modal-lg fade" id="editore_modal_overlay" tabindex="-1" aria-labelledby="Editor_modal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex justify-content-between" style="width: 100%">
                    <h1 class="modal-title fs-5 text-bold text-primary" id="Editor_modal">Edit Question</h1>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-success">Done</button>
                        <button type="button" class="btn btn-danger">Close</button>
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
                            <button type="button" id="underline-btn" title="Underline"><i
                                    class="fas fa-underline"></i></button>
                            <button type="button" id="h1-btn" title="Heading 1"><i class="fas fa-heading"></i>
                                1</button>
                            <button type="button" id="h2-btn" title="Heading 2"><i class="fas fa-heading"></i>
                                2</button>
                            <button type="button" id="ul-btn" title="Unordered List"><i
                                    class="fas fa-list-ul"></i></button>
                            <button type="button" id="ol-btn" title="Ordered List"><i
                                    class="fas fa-list-ol"></i></button>
                            <button type="button" id="blockquote-btn" title="Blockquote"><i
                                    class="fas fa-quote-right"></i></button>
                            <button type="button" id="link-btn" title="Insert Link"><i class="fas fa-link"></i></button>
                            <button type="button" id="undo-btn" title="Undo"><i class="fas fa-undo"></i></button>
                            <button type="button" id="redo-btn" title="Redo"><i class="fas fa-redo"></i></button>
                            <input type="color" id="text-color-picker" title="Text Color">
                            <input type="color" id="bg-color-picker" title="Background Color">

                            <!-- Icons for Image and Video Uploads -->
                            <button type="button" id="image-icon" title="Insert Image"><i
                                    class="fas fa-image"></i></button>
                            <button type="button" id="video-icon" title="Insert Video"><i
                                    class="fas fa-video"></i></button>
                            <button type="button" id="audio-icon" title="Insert Audio">
                                <i class="fa-solid fa-music"></i></button>

                            <!-- Hidden file inputs -->
                            <input type="file" id="image-upload" accept="image/*" style="display: none;">
                            <input type="file" id="video-upload" accept="video/*" style="display: none;">
                            <input type="file" id="audio-upload" accept="audio/*" style="display: none;">

                            <button type="button" id="table-btn" title="Insert Table"><i
                                    class="fas fa-table"></i></button>
                            <button type="button" id="video-url-btn" title="Embed Video URL"><i
                                    class="fas fa-link"></i></button>
                        </div>

                        <!-- Editable Area -->
                        <div id="custom-editor" contenteditable="true" aria-details="content_placeholder">
                            <p>
                            </p>
                        </div>
                    </div>
                    <!-- Hidden Input to Store Editor Content -->
                    <input type="hidden" name="content" id="editor-content">
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
</script>
@endsection