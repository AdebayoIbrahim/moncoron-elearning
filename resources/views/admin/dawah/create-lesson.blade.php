@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Create Lesson for Dawah Course: {{ $dawah->title }}</h1>
    <form action="{{ route('admin.dawah.store-lesson', $dawah->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="resource_type" class="form-label">Resource Type</label>
            <input type="text" name="resource_type" id="resource_type" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="source" class="form-label">Source</label>
            <input type="text" name="source" id="source" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="resource_url" class="form-label">Resource URL</label>
            <input type="text" name="resource_url" id="resource_url" class="form-control">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="order" class="form-label">Order</label>
            <input type="number" name="order" id="order" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create Lesson</button>
    </form>
</div>
@endsection
