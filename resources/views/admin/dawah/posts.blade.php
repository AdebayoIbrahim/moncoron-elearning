@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Dawah Posts</h1>
    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teacher')
        <a href="{{ route(Auth::user()->role . '.dawah.create-post') }}" class="btn btn-primary mb-3">Create New Post</a>
    @endif
    <form method="GET" action="{{ route(Auth::user()->role . '.dawah.posts') }}">
        <div class="mb-3">
            <select name="type" class="form-control" onchange="this.form.submit()">
                <option value="">Filter by Type</option>
                <option value="text">Text</option>
                <option value="video">Video</option>
                <option value="audio">Audio</option>
            </select>
        </div>
    </form>
    <ul class="list-group">
        @foreach ($posts as $post)
            <li class="list-group-item">
                <h3>{{ $post->title }}</h3>
                <p>{{ $post->content }}</p>
                <small>{{ $post->created_at->format('d M, Y') }}</small>
                @if ($post->type == 'video')
                    <p><a href="{{ $post->content }}" target="_blank">Watch Video</a></p>
                @elseif ($post->type == 'audio')
                    <p><a href="{{ $post->content }}" target="_blank">Listen to Audio</a></p>
                @endif
            </li>
        @endforeach
    </ul>
</div>
@endsection
