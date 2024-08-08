@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Lesson Assessments</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($assessments->isEmpty())
        <p>No assessments found.</p>
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
                        <td>{{ Str::limit(json_encode($assessment->questions), 50) }}</td>
                        <td>
                            <form action="{{ route('lesson_assessments.destroy', ['courseId' => $courseId, 'lesson_assessment' => $assessment->id]) }}" method="POST">
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
