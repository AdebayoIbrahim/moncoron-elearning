@extends('layouts.app')
@section('content')
@include('partials.admin_header')
<div class="container-fluid">
    <div class="row">
    @include('partials.student_sidebar')
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
                        Course Assessments for {{ $course->name }}
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
                                    <th>Assessment Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assessments as $assessment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('student.courses.assessments.show', [$course->id, $assessment->id]) }}">
                                            {{ $assessment->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('student.courses.assessments.show', [$course->id, $assessment->id]) }}" class="btn btn-primary">
                                            Take Assessment
                                        </a>
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
@endsection
