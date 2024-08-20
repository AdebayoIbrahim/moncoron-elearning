@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')

<div class="container">
    <h1>Assessments for Course {{ $course_id }}</h1>

    @if($assessments->isEmpty())
        <p>No assessments found for this course.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lesson ID</th>
                    <th>Questions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assessments as $assessment)
                    <tr>
                        <td>{{ $assessment->id }}</td>
                        <td>{{ $assessment->lesson_id }}</td>
                        <td>{{ Str::limit(json_encode($assessment->questions), 2000) }}</td>
                        <td>
                            <!-- Ensure that the correct lessonId is passed here -->
                            <a href="{{ route('lesson_assessments.show', ['courseId' => $course_id, 'lessonId' => $assessment->lesson_id, 'id' => $assessment->id]) }}" class="btn btn-info">View</a>
                            <form action="{{ route('lesson_assessments.destroy', ['courseId' => $course_id, 'lessonId' => $assessment->lesson_id, 'id' => $assessment->id]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
