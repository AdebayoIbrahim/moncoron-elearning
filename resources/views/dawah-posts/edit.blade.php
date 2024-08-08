@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Edit Dawah Post</h1>
    <form action="{{ route('admin.dawah-posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5">{{ $post->content }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image">
            @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" alt="Image" width="100">
            @endif
        </div>

        <div class="mb-3">
            <label for="video" class="form-label">Video</label>
            <input type="file" class="form-control" id="video" name="video">
            @if($post->video)
                <video controls width="100">
                    <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4">
                </video>
            @endif
        </div>

        <div class="mb-3">
            <label for="audio" class="form-label">Audio</label>
            <input type="file" class="form-control" id="audio" name="audio">
            @if($post->audio)
                <audio controls>
                    <source src="{{ asset('storage/' . $post->audio) }}" type="audio/mp3">
                </audio>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
</div>
@endsection
