@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Teachers</h1>
    <div class="row">
        @foreach ($teachers as $teacher)
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="{{ $teacher->profile_picture }}" class="card-img-top" alt="{{ $teacher->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $teacher->name }}</h5>
                        <p class="card-text">{{ Str::limit($teacher->biography, 100) }}</p>
                        <a href="{{ route('admin.dawah-posts.teacher-profile', $teacher->id) }}" class="btn btn-primary">View Profile</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
