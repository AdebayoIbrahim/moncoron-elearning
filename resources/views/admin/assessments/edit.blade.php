@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Assessment for {{ $course->name }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.courses.assessments.update', [$course->id, $assessment->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="assessment-name">Assessment Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $assessment->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="assessment-duration">Duration (in minutes)</label>
                            <input type="number" name="duration" class="form-control" value="{{ $assessment->duration }}" required>
                        </div>
                        <div id="questions-container">
                            @foreach ($assessment->questions as $questionIndex => $question)
                                <div class="form-group question-group" data-question="{{ $questionIndex }}">
                                    <label for="questions[{{ $questionIndex }}][text]">Question {{ $questionIndex + 1 }}</label>
                                    <input type="text" name="questions[{{ $questionIndex }}][text]" class="form-control" value="{{ $question['text'] }}" required>
                                    <label for="questions[{{ $questionIndex }}][media]">Media</label>
                                    <input type="file" name="questions[{{ $questionIndex }}][media]" class="form-control">
                                    <div id="options-container-{{ $questionIndex }}">
                                        @foreach ($question['options'] as $optionIndex => $option)
                                            <div class="form-group option-group">
                                                <label for="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][text]">Option {{ chr(65 + $optionIndex) }}</label>
                                                <input type="text" name="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][text]" class="form-control" value="{{ $option['text'] }}" required>
                                                <label for="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][media]">Media</label>
                                                <input type="file" name="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][media]" class="form-control">
                                                <label for="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][correct]">Correct</label>
                                                <input type="radio" name="questions[{{ $questionIndex }}][correct]" value="{{ $optionIndex }}" class="form-check-input" @if($option['correct']) checked @endif>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-secondary add-option" data-question="{{ $questionIndex }}">Add Option</button>
                                    <button type="button" class="btn btn-danger remove-question" data-question="{{ $questionIndex }}">Remove Question</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-question" class="btn btn-secondary">Add Question</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.getElementById('add-question').addEventListener('click', function() {
    const container = document.getElementById('questions-container');
    const questionCount = container.querySelectorAll('.question-group').length;
    const alphabet = 'ABCDE';
    const newQuestion = document.createElement('div');
    newQuestion.classList.add('form-group', 'question-group');
    newQuestion.setAttribute('data-question', questionCount);
    newQuestion.innerHTML = `
        <label for="questions[${questionCount}][text]">Question ${questionCount + 1}</label>
        <input type="text" name="questions[${questionCount}][text]" class="form-control" required>
        <label for="questions[${questionCount}][media]">Media</label>
        <input type="file" name="questions[${questionCount}][media]" class="form-control">
        <div id="options-container-${questionCount}">
            <div class="form-group option-group">
                <label for="questions[${questionCount}][options][0][text]">Option A</label>
                <input type="text" name="questions[${questionCount}][options][0][text]" class="form-control" required>
                <label for="questions[${questionCount}][options][0][media]">Media</label>
                <input type="file" name="questions[${questionCount}][options][0][media]" class="form-control">
                <label for="questions[${questionCount}][options][0][correct]">Correct</label>
                <input type="radio" name="questions[${questionCount}][correct]" value="0" class="form-check-input">
            </div>
        </div>
        <button type="button" class="btn btn-secondary add-option" data-question="${questionCount}">Add Option</button>
        <button type="button" class="btn btn-danger remove-question" data-question="${questionCount}">Remove Question</button>
    `;
    container.appendChild(newQuestion);

    document.querySelectorAll('.add-option').forEach(button => {
        button.removeEventListener('click', addOption);
        button.addEventListener('click', addOption);
    });

    document.querySelectorAll('.remove-question').forEach(button => {
        button.removeEventListener('click', removeQuestion);
        button.addEventListener('click', removeQuestion);
    });
});

function addOption(event) {
    const questionIndex = event.target.getAttribute('data-question');
    const container = document.getElementById(`options-container-${questionIndex}`);
    const optionCount = container.querySelectorAll('.option-group').length;
    if (optionCount < 5) {
        const alphabet = 'ABCDE';
        const newOption = document.createElement('div');
        newOption.classList.add('form-group', 'option-group');
        newOption.innerHTML = `
            <label for="questions[${questionIndex}][options][${optionCount}][text]">Option ${alphabet[optionCount]}</label>
            <input type="text" name="questions[${questionIndex}][options][${optionCount}][text]" class="form-control" required>
            <label for="questions[${questionIndex}][options][${optionCount}][media]">Media</label>
            <input type="file" name="questions[${questionIndex}][options][${optionCount}][media]" class="form-control">
            <label for="questions[${questionIndex}][options][${optionCount}][correct]">Correct</label>
            <input type="radio" name="questions[${questionIndex}][correct]" value="${optionCount}" class="form-check-input">
        `;
        container.appendChild(newOption);
    }
}

function removeQuestion(event) {
    const questionIndex = event.target.getAttribute('data-question');
    const questionElement = document.querySelector(`.question-group[data-question="${questionIndex}"]`);
    questionElement.remove();
}

document.querySelectorAll('.add-option').forEach(button => {
    button.addEventListener('click', addOption);
});

document.querySelectorAll('.remove-question').forEach(button => {
    button.addEventListener('click', removeQuestion);
});
</script>
@endsection
