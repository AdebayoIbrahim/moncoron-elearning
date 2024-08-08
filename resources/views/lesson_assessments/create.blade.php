@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h2>Create Assessment for Lesson {{ $lessonId }}</h2>
    <form action="{{ route('assessments.store', $lessonId) }}" method="POST">
        @csrf
        <div id="questions-container">
            <div class="question">
                <div class="form-group">
                    <label for="question">Question</label>
                    <input type="text" name="questions[0][question]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="options">Options</label>
                    <input type="text" name="questions[0][options][]" class="form-control" required>
                    <input type="text" name="questions[0][options][]" class="form-control" required>
                    <input type="text" name="questions[0][options][]" class="form-control" required>
                    <input type="text" name="questions[0][options][]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="answer">Answer</label>
                    <input type="text" name="questions[0][answer]" class="form-control" required>
                </div>
            </div>
        </div>
        <button type="button" id="add-question">Add Question</button>
        <button type="submit" class="btn btn-primary">Create Assessment</button>
    </form>
</div>

<script>
    document.getElementById('add-question').addEventListener('click', function () {
        var container = document.getElementById('questions-container');
        var questionCount = container.getElementsByClassName('question').length;
        var newQuestion = document.createElement('div');
        newQuestion.className = 'question';
        newQuestion.innerHTML = `
            <div class="form-group">
                <label for="question">Question</label>
                <input type="text" name="questions[${questionCount}][question]" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="options">Options</label>
                <input type="text" name="questions[${questionCount}][options][]" class="form-control" required>
                <input type="text" name="questions[${questionCount}][options][]" class="form-control" required>
                <input type="text" name="questions[${questionCount}][options][]" class="form-control" required>
                <input type="text" name="questions[${questionCount}][options][]" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="answer">Answer</label>
                <input type="text" name="questions[${questionCount}][answer]" class="form-control" required>
            </div>
        `;
        container.appendChild(newQuestion);
    });
</script>
@endsection
