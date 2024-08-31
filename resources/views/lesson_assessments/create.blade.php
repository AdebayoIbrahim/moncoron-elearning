@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')

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
                    <div class="custom-editor" id="custom-editor-0">
                        <div contenteditable="true" class="editor-content" id="editor-content-0"></div>
                        <input type="hidden" name="questions[0][question]" id="hidden-editor-content-0">
                    </div>
                </div>

                <!-- Options Section -->
                <div class="form-group">
                    <label for="options">Options</label>
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $option)
                        <div class="option">
                            <label for="option_{{ strtolower($option) }}_0">{{ $option }}:</label>
                            <div class="custom-editor" id="custom-editor-0-{{ strtolower($option) }}">
                                <div contenteditable="true" class="editor-content" id="editor-content-0-{{ strtolower($option) }}"></div>
                                <input type="hidden" name="questions[0][options][{{ $option }}]" id="hidden-editor-content-0-{{ strtolower($option) }}">
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
</div>

<script>
    function initializeEditor(id) {
        // Initialize any specific editor logic if necessary
        console.log('Initializing editor:', id);
        // For example, you could add specific toolbar actions here
    }

    function addQuestion(questionCount) {
        var container = document.getElementById('questions-container');
        var newQuestion = document.createElement('div');
        newQuestion.className = 'question mt-4';
        newQuestion.innerHTML = `
            <div class="form-group">
                <label for="question_${questionCount}">Question ${questionCount + 1}</label>
                <div class="custom-editor" id="custom-editor-${questionCount}">
                    <div contenteditable="true" class="editor-content" id="editor-content-${questionCount}"></div>
                    <input type="hidden" name="questions[${questionCount}][question]" id="hidden-editor-content-${questionCount}">
                </div>
            </div>
            <div class="form-group">
                <label for="options">Options</label>
                ${['A', 'B', 'C', 'D', 'E'].map(option => `
                    <div class="option">
                        <label for="option_${option.toLowerCase()}_${questionCount}">${option}:</label>
                        <div class="custom-editor" id="custom-editor-${questionCount}-${option.toLowerCase()}">
                            <div contenteditable="true" class="editor-content" id="editor-content-${questionCount}-${option.toLowerCase()}"></div>
                            <input type="hidden" name="questions[${questionCount}][options][${option}]" id="hidden-editor-content-${questionCount}-${option.toLowerCase()}">
                        </div>
                    </div>
                `).join('')}
            </div>
            <div class="form-group">
                <label for="correct_option_${questionCount}">Correct Option</label>
                <select name="questions[${questionCount}][correct_option]" class="form-control" id="correct_option_${questionCount}" required>
                    ${['A', 'B', 'C', 'D', 'E'].map(option => `
                        <option value="${option}">${option}</option>
                    `).join('')}
                </select>
            </div>
            <div class="form-group">
                <label for="value_${questionCount}">Question Value</label>
                <input type="number" name="questions[${questionCount}][value]" class="form-control" id="value_${questionCount}" required>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-question">Remove Question</button>
        `;
        container.appendChild(newQuestion);

        // Initialize editors for the new question and options
        initializeEditor(`editor-content-${questionCount}`);
        ['A', 'B', 'C', 'D', 'E'].forEach(option => {
            initializeEditor(`editor-content-${questionCount}-${option.toLowerCase()}`);
        });
    }

    document.getElementById('add-question').addEventListener('click', function () {
        var questionCount = document.getElementsByClassName('question').length;
        addQuestion(questionCount);
    });

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-question')) {
            event.target.closest('.question').remove();
        }
    });

    document.querySelector("form").addEventListener("submit", function () {
        document.querySelectorAll('.custom-editor').forEach(function(editor) {
            var editorContent = editor.querySelector('.editor-content').innerHTML;
            editor.querySelector('input[type="hidden"]').value = editorContent;
        });
    });

    // Initialize the first question editor
    initializeEditor('editor-content-0');
    ['A', 'B', 'C', 'D', 'E'].forEach(option => {
        initializeEditor(`editor-content-0-${option.toLowerCase()}`);
    });
</script>
@endsection
