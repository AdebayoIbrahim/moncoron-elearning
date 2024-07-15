@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <div class="card">
                <div class="card-header">
                    <h2>Add New Assessment for {{ $course->name }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.courses.assessments.store', $course->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div id="questions-container">
                            <div class="form-group question-group">
                                <label for="questions[0][text]">Question 1</label>
                                <input type="text" name="questions[0][text]" class="form-control" required>
                                <label for="questions[0][media]">Media</label>
                                <input type="file" name="questions[0][media]" class="form-control">
                                <div id="options-container-0">
                                    <div class="form-group option-group">
                                        <label for="questions[0][options][0][text]">Option 1</label>
                                        <input type="text" name="questions[0][options][0][text]" class="form-control" required>
                                        <label for="questions[0][options][0][media]">Media</label>
                                        <input type="file" name="questions[0][options][0][media]" class="form-control">
                                        <label for="questions[0][options][0][correct]">Correct</label>
                                        <input type="checkbox" name="questions[0][options][0][correct]" class="form-check-input">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary add-option" data-question="0">Add Option</button>
                            </div>
                        </div>
                        <button type="button" id="add-question" class="btn btn-secondary">Add Question</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
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
    const newQuestion = document.createElement('div');
    newQuestion.classList.add('form-group', 'question-group');
    newQuestion.innerHTML = `
        <label for="questions[${questionCount}][text]">Question ${questionCount + 1}</label>
        <input type="text" name="questions[${questionCount}][text]" class="form-control" required>
        <label for="questions[${questionCount}][media]">Media</label>
        <input type="file" name="questions[${questionCount}][media]" class="form-control">
        <div id="options-container-${questionCount}">
            <div class="form-group option-group">
                <label for="questions[${questionCount}][options][0][text]">Option 1</label>
                <input type="text" name="questions[${questionCount}][options][0][text]" class="form-control" required>
                <label for="questions[${questionCount}][options][0][media]">Media</label>
                <input type="file" name="questions[${questionCount}][options][0][media]" class="form-control">
                <label for="questions[${questionCount}][options][0][correct]">Correct</label>
                <input type="checkbox" name="questions[${questionCount}][options][0][correct]" class="form-check-input">
            </div>
        </div>
        <button type="button" class="btn btn-secondary add-option" data-question="${questionCount}">Add Option</button>
    `;
    container.appendChild(newQuestion);

    document.querySelectorAll('.add-option').forEach(button => {
        button.removeEventListener('click', addOption);
        button.addEventListener('click', addOption);
    });
});

function addOption(event) {
    const questionIndex = event.target.getAttribute('data-question');
    const container = document.getElementById(`options-container-${questionIndex}`);
    const optionCount = container.querySelectorAll('.option-group').length;
    const newOption = document.createElement('div');
    newOption.classList.add('form-group', 'option-group');
    newOption.innerHTML = `
        <label for="questions[${questionIndex}][options][${optionCount}][text]">Option ${optionCount + 1}</label>
        <input type="text" name="questions[${questionIndex}][options][${optionCount}][text]" class="form-control" required>
        <label for="questions[${questionIndex}][options][${optionCount}][media]">Media</label>
        <input type="file" name="questions[${questionIndex}][options][${optionCount}][media]" class="form-control">
        <label for="questions[${questionIndex}][options][${optionCount}][correct]">Correct</label>
        <input type="checkbox" name="questions[${questionIndex}][options][${optionCount}][correct]" class="form-check-input">
    `;
    container.appendChild(newOption);
}
</script>
@endsection
