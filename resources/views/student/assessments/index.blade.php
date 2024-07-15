@extends('layouts.app')
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
                    <h2>Assessments for {{ $course->name }}</h2>
                </div>
                <div class="card-body">
                    <ul>
                        @foreach($assessments as $assessment)
                            <li>
                                <a href="{{ route('student.courses.assessments.show', [$course->id, $assessment->id]) }}">Assessment {{ $assessment->id }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

