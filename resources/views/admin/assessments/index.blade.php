@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin_sidebar')
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
                    <h2>
                        Course Assessments for {{ $course->name }} <button type="button" class="btn btn-primary pull-right" id="addAssessment"><i class="fa fa-plus"></i> Add New Assessment</button>
                    </h2>
                </div>
                <div class="card-body">
                    @if($assessments->isEmpty())
                        <p>No assessments available.</p>
                    @else
                        <table class="table-bordered table table-striped">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Assessment ID</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assessments as $assessment)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$assessment->id}}</td>
                                    <td>
                                        <div class="dropdown text-center">
                                            <a href="#" class="d-block link-body-emphasis text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="fa fa-ellipsis-v"></span>
                                            </a>
                                            <ul class="dropdown-menu text-small">
                                                <li><a class="dropdown-item" href="{{ route('admin.courses.assessments.show', [$course->id, $assessment->id]) }}"><i class="fa fa-eye"></i> View</a></li>
                                                <li><a class="dropdown-item editAssessment" href="{{ route('admin.courses.assessments.edit', [$course->id, $assessment->id]) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                                                <li>
                                                    <form action="{{ route('admin.courses.assessments.destroy', [$course->id, $assessment->id]) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Add New Assessment Modal -->
<div class="modal fade" id="addAssessmentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 text-bold text-primary" id="exampleModalLabel">Add New Assessment</h1>
        <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.courses.assessments.store', $course->id) }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
        <div id="questions-container">
            <div class="form-group">
                <label for="questions[0][text]">Question 1</label>
                <input type="text" name="questions[0][text]" class="form-control" required>
                <label for="questions[0][media]">Media</label>
                <input type="file" name="questions[0][media]" class="form-control">
                <div id="options-container-0">
                    <div class="form-group">
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Add New Assessment -->

<script>
document.getElementById('addAssessment').addEventListener('click', function() {
    const modal = new bootstrap.Modal(document.getElementById('addAssessmentModal'));
    modal.show();
});

document.getElementById('add-question').addEventListener('click', function() {
    const container = document.getElementById('questions-container');
    const questionCount = container.children.length;
    const newQuestion = document.createElement('div');
    newQuestion.classList.add('form-group');
    newQuestion.innerHTML = `
        <label for="questions[${questionCount}][text]">Question ${questionCount + 1}</label>
        <input type="text" name="questions[${questionCount}][text]" class="form-control" required>
        <label for="questions[${questionCount}][media]">Media</label>
        <input type="file" name="questions[${questionCount}][media]" class="form-control">
        <div id="options-container-${questionCount}">
            <div class="form-group">
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
    const optionCount = container.children.length;
    const newOption = document.createElement('div');
    newOption.classList.add('form-group');
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

