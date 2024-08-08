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
            <a href="{{ route('admin.dawah-posts.teacher-profile', ['id' => $teacher->id, 'media_type' => 'audio']) }}" class="btn btn-info">Audio Lectures</a>
            <a href="{{ route('admin.dawah-posts.teacher-profile', ['id' => $teacher->id, 'media_type' => 'video']) }}" class="btn btn-info">Video Lectures</a>
        </div>
    </div>
    <div class="row mt-4">
        @if($posts && !$posts->isEmpty())
            @foreach ($posts as $post)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                            @if ($post->type === 'video' && $post->video)
                                <video controls width="100%">
                                    <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4">
                                </video>
                            @elseif ($post->type === 'audio' && $post->audio)
                                <audio controls>
                                    <source src="{{ asset('storage/' . $post->audio) }}" type="audio/mp3">
                                </audio>
                            @elseif ($post->type === 'image' && $post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Image" class="img-fluid">
                            @endif
                            <p>Posted on {{ $post->created_at->format('M d, Y') }}</p>
                            <a href="{{ route('admin.dawah-posts.show', $post->id) }}" class="btn btn-primary">View Post</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p>No posts found.</p>
        @endif
    </div>
</div>
@endsection
