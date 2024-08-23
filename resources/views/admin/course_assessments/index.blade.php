@extends('layouts.adminapp')

@section('content')
@include('partials.admin_header')

<div class="container">
    <h2>Course Assessments for {{ $course->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.course_assessments.create', $course->id) }}" class="btn btn-primary mb-3">Create New Assessment</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assessments as $index => $assessment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $assessment->name }}</td>
                <td>{{ $assessment->start_time }}</td>
                <td>{{ $assessment->end_time }}</td>
                <td>
                    <a href="{{ route('admin.course_assessments.show', ['course' => $course->id, 'assessment' => $assessment->id]) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('admin.course_assessments.edit', ['course' => $course->id, 'assessment' => $assessment->id]) }}" class="btn btn-warning btn-sm">Edit</a>

                    <!-- Delete with Confirmation -->
                    <form action="{{ route('admin.course_assessments.delete', ['course' => $course->id, 'assessment' => $assessment->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this assessment?');">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
