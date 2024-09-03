<!-- resources/views/editor.blade.php -->

@extends('layouts.app')
@vite(['resources/css/custom-editor/editor.css', 'resources/js/custom-editor/editor.js'])

@section('content')
<div class="container">
    <h1>Create/Edit Content</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- <form action="{{ route('editor.save') }}" method="POST"> -->
    @csrf

    <!-- Editor Container -->
    <div id="editor-container">
        <!-- Toolbar -->
        <div id="editor-toolbar">
            <button type="button" id="bold-btn" title="Bold"><i class="fas fa-bold"></i></button>
            <button type="button" id="italic-btn" title="Italic"><i class="fas fa-italic"></i></button>
            <button type="button" id="underline-btn" title="Underline"><i class="fas fa-underline"></i></button>
            <button type="button" id="h1-btn" title="Heading 1"><i class="fas fa-heading"></i> 1</button>
            <button type="button" id="h2-btn" title="Heading 2"><i class="fas fa-heading"></i> 2</button>
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
            <p>{!! old('content', $existingContent) !!}</p>
        </div>
    </div>

    <!-- Hidden Input to Store Editor Content -->

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary mt-3" aria-details="submit-button">Save Content</button>
    <!-- </form> -->
</div>

<!-- Include Editor CSS
<link rel="stylesheet" href="{{ mix('css/custom-editor/editor.css')}}"/> -->

<!-- Include Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Include Editor JavaScript -->
<!-- <script src="{{ mix('js/custom-editor/editor.js') }}"></script> -->

<!-- Script to Handle Form Submission -->
<script>
// document.querySelector("form").addEventListener("submit", function (e) {
//     // Set the hidden input's value to the editor's HTML content
//     document.querySelector("#editor-content").value = document.querySelector("#custom-editor").innerHTML;
// });
</script>
@endsection