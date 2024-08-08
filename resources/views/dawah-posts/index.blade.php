@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Dawah Posts</h1>
    <a href="{{ route('admin.dawah-posts.create') }}" class="btn btn-primary mb-3">Create New Post</a>

    <div class="media-filters mb-3">
        <a href="{{ route('admin.dawah-posts.index') }}" class="btn btn-primary">All</a>
        <a href="{{ route('admin.dawah-posts.index', ['media_type' => 'video']) }}" class="btn btn-primary">Videos</a>
        <a href="{{ route('admin.dawah-posts.index', ['media_type' => 'audio']) }}" class="btn btn-primary">Audios</a>
        <a href="{{ route('admin.dawah-posts.index', ['media_type' => 'image']) }}" class="btn btn-primary">Images</a>
        <a href="{{ route('admin.dawah-posts.index', ['media_type' => 'text']) }}" class="btn btn-primary">Texts</a>
    </div>

    <div class="media-content">
        @foreach ($posts as $post)
            <div class="post mb-4">
                <h2>{{ $post->title }}</h2>
                <p>{{ $post->content }}</p>

                @if($post->image)
                    <div class="image mb-3">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="Image" class="img-fluid">
                    </div>
                @endif

                @if($post->video)
                    <div class="video mb-3">
                        <video controls width="440" height="250">
                            <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4">
                        </video>
                    </div>
                @endif

                @if($post->audio)
                    <div class="audio mb-3">
                        <audio controls>
                            <source src="{{ asset('storage/' . $post->audio) }}" type="audio/mp3">
                        </audio>
                    </div>
                @endif

                <p>Posted on {{ $post->created_at->format('M d, Y') }}</p>
                <a href="{{ route('admin.dawah-posts.edit', $post->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('admin.dawah-posts.destroy', $post->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
