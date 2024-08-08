@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Create New Dawah Post</h1>
    <form method="POST" action="{{ route(Auth::user()->role . '.dawah.store-post') }}">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" id="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" class="form-control" id="content" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" class="form-control" id="type" required>
                <option value="text">Text</option>
                <option value="video">Video</option>
                <option value="audio">Audio</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
