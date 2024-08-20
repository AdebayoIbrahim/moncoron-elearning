@extends('layouts.adminapp')

@section('content')
@include('partials.admin_header')

<div class="container mt-5">
    <h2>Student Grades</h2>

    @foreach($students as $student)
        <div class="card mt-3">
            <div class="card-header">
                <h4>{{ $student->name }}</h4>
            </div>
            <div class="card-body">
                @foreach($student->courses as $course)
                    <div class="mt-2">
                        <h5>Course: {{ $course->name }}</h5>
                        <ul class="list-group">
                            @foreach($course->lessons as $lesson)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Lesson: {{ $lesson->name }}</strong>
                                    </div>
                                    <div>
                                        @if($lesson->grade)
                                            <span class="badge bg-success">Score: {{ $lesson->grade->score }}%</span>
                                        @else
                                            <span class="badge bg-warning">Not graded</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
