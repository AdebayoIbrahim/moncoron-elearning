@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->content }}</p>

    @if($post->image)
        <div class="image">
            <img src="{{ asset('storage/' . $post->image) }}" alt="Image" width="440" height="250">
        </div>
    @endif

    @if($post->video)
        <div class="video">
            <video controls width="440" height="250">
                <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4">
            </video>
        </div>
    @endif

    @if($post->audio)
        <div class="audio">
            <audio controls>
                <source src="{{ asset('storage/' . $post->audio) }}" type="audio/mp3">
            </audio>
        </div>
    @endif
</div>
@endsection
