@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <img src="{{ $teacher->profile_picture }}" class="img-fluid" alt="{{ $teacher->name }}">
        </div>
        <div class="col-md-9">
            <h1>{{ $teacher->name }}</h1>
            <p>{{ $teacher->biography }}</p>
            <a href="#" class="btn btn-info">Audio Lecture</a>
            <a href="#" class="btn btn-info">Video Lecture</a>
        </div>
    </div>
    <div class="row mt-4">
        @foreach ($posts as $post)
            <div class="col-md-4">
                <div class="card mb-3">
                    @if ($post->type == 'image')
                        <img src="{{ asset('storage/' . $post->attachment) }}" class="card-img-top" alt="{{ $post->title }}">
                    @elseif ($post->type == 'video')
                        <video controls class="w-100">
                            <source src="{{ asset('storage/' . $post->attachment) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @elseif ($post->type == 'audio')
                        <audio controls class="w-100">
                            <source src="{{ asset('storage/' . $post->attachment) }}" type="audio/mp3">
                            Your browser does not support the audio element.
                        </audio>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ $post->content }}</p>
                        <p class="card-text"><small class="text-muted">{{ $post->created_at->format('d M Y') }}</small></p>
                        <a href="{{ route('admin.dawah-posts.show', $post->id) }}" class="btn btn-primary">View</a>
                        <a href="{{ route('admin.dawah-posts.edit', $post->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.dawah-posts.destroy', $post->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
