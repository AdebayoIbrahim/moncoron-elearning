@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Create Dawah Course</h1>
    <form action="{{ route('admin.dawah.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" name="type" id="type" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="age_group" class="form-label">Age Group</label>
            <input type="number" name="age_group" id="age_group" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <input type="number" name="status" id="status" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Course</button>
    </form>
</div>
@endsection
