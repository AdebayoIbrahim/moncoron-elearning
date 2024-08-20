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

    <form action="{{ route('assessments.store', ['courseId' => $courseId, 'lessonId' => $lessonId]) }}" method="POST" enctype="multipart/form-data">
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
                    <input type="text" name="questions[0][question]" class="form-control" id="question_0" required>
                </div>

                <!-- Media for Question -->
                <div class="form-group">
                    <label for="question_media_0">Attach Media (optional)</label>
                    <input type="file" name="questions[0][media][]" class="form-control-file" id="question_media_0" multiple>
                </div>

                <!-- Options Section -->
                <div class="form-group">
                    <label for="options">Options</label>
                    <div class="option">
                        <label for="option_a_0">A:</label>
                        <input type="text" name="questions[0][options][A]" class="form-control" id="option_a_0" required>
                        <input type="file" name="questions[0][option_media][A]" class="form-control-file">
                    </div>
                    <div class="option">
                        <label for="option_b_0">B:</label>
                        <input type="text" name="questions[0][options][B]" class="form-control" id="option_b_0" required>
                        <input type="file" name="questions[0][option_media][B]" class="form-control-file">
                    </div>
                    <div class="option">
                        <label for="option_c_0">C:</label>
                        <input type="text" name="questions[0][options][C]" class="form-control" id="option_c_0" required>
                        <input type="file" name="questions[0][option_media][C]" class="form-control-file">
                    </div>
                    <div class="option">
                        <label for="option_d_0">D:</label>
                        <input type="text" name="questions[0][options][D]" class="form-control" id="option_d_0" required>
                        <input type="file" name="questions[0][option_media][D]" class="form-control-file">
                    </div>
                    <div class="option">
                        <label for="option_e_0">E:</label>
                        <input type="text" name="questions[0][options][E]" class="form-control" id="option_e_0">
                        <input type="file" name="questions[0][option_media][E]" class="form-control-file">
                    </div>
                </div>

                <!-- Correct Option Dropdown -->
                <div class="form-group">
                    <label for="correct_option_0">Correct Option</label>
                    <select name="questions[0][correct_option]" class="form-control" id="correct_option_0" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
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
    document.getElementById('add-question').addEventListener('click', function () {
        var container = document.getElementById('questions-container');
        var questionCount = container.getElementsByClassName('question').length;
        var newQuestion = document.createElement('div');
        newQuestion.className = 'question mt-4';
        newQuestion.innerHTML = `
            <div class="form-group">
                <label for="question_${questionCount}">Question ${questionCount + 1}</label>
                <input type="text" name="questions[${questionCount}][question]" class="form-control" id="question_${questionCount}" required>
            </div>
            <div class="form-group">
                <label for="question_media_${questionCount}">Attach Media (optional)</label>
                <input type="file" name="questions[${questionCount}][media][]" class="form-control-file" id="question_media_${questionCount}" multiple>
            </div>
            <div class="form-group">
                <label for="options">Options</label>
                <div class="option">
                    <label for="option_a_${questionCount}">A:</label>
                    <input type="text" name="questions[${questionCount}][options][A]" class="form-control" id="option_a_${questionCount}" required>
                    <input type="file" name="questions[${questionCount}][option_media][A]" class="form-control-file">
                </div>
                <div class="option">
                    <label for="option_b_${questionCount}">B:</label>
                    <input type="text" name="questions[${questionCount}][options][B]" class="form-control" id="option_b_${questionCount}" required>
                    <input type="file" name="questions[${questionCount}][option_media][B]" class="form-control-file">
                </div>
                <div class="option">
                    <label for="option_c_${questionCount}">C:</label>
                    <input type="text" name="questions[${questionCount}][options][C]" class="form-control" id="option_c_${questionCount}" required>
                    <input type="file" name="questions[${questionCount}][option_media][C]" class="form-control-file">
                </div>
                <div class="option">
                    <label for="option_d_${questionCount}">D:</label>
                    <input type="text" name="questions[${questionCount}][options][D]" class="form-control" id="option_d_${questionCount}" required>
                    <input type="file" name="questions[${questionCount}][option_media][D]" class="form-control-file">
                </div>
                <div class="option">
                    <label for="option_e_${questionCount}">E:</label>
                    <input type="text" name="questions[${questionCount}][options][E]" class="form-control" id="option_e_${questionCount}">
                    <input type="file" name="questions[${questionCount}][option_media][E]" class="form-control-file">
                </div>
            </div>
            <div class="form-group">
                <label for="correct_option_${questionCount}">Correct Option</label>
                <select name="questions[${questionCount}][correct_option]" class="form-control" id="correct_option_${questionCount}" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                </select>
            </div>
            <div class="form-group">
                <label for="value_${questionCount}">Question Value</label>
                <input type="number" name="questions[${questionCount}][value]" class="form-control" id="value_${questionCount}" required>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-question">Remove Question</button>
        `;
        container.appendChild(newQuestion);
    });

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-question')) {
            event.target.closest('.question').remove();
        }
    });
</script>
@endsection
