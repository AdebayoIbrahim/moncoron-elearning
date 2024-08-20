@extends('layouts.adminapp')

@section('content')
@include('partials.admin_header')

<div class="container mt-5">
    <h2>Manage Assessments</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Lesson</th>
                <th>Course</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assessments as $index => $assessment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $assessment->lesson->name }}</td>
                    <td>{{ $assessment->lesson->course->name }}</td>
                    <td>
                        @if($assessment->is_published)
                            <span class="badge badge-success">Published</span>
                        @else
                            <span class="badge badge-secondary">Unpublished</span>
                        @endif
                    </td>
                    <td>
                        <!-- Publish/Unpublish Button -->
                        @if($assessment->is_published)
                            <form action="{{ route('assessments.unpublish', $assessment->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">Unpublish</button>
                            </form>
                        @else
                            <form action="{{ route('assessments.publish', $assessment->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Publish</button>
                            </form>
                        @endif

                        <!-- Delete Button with Confirmation -->
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $assessment->id }})">Delete</button>

                        <!-- Delete Form -->
                        <form id="delete-form-{{ $assessment->id }}" action="{{ route('assessments.delete', $assessment->id) }}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="confirm" value="yes">
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function confirmDelete(assessmentId) {
        if (confirm('Are you sure you want to delete this assessment? This action cannot be undone.')) {
            document.getElementById('delete-form-' + assessmentId).submit();
        }
    }
</script>

@endsection
