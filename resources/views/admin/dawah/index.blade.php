@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Dawah Courses</h1>
    <a href="{{ route('admin.dawah.create') }}" class="btn btn-primary mb-3">Create New Dawah Course</a>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Type</th>
                <th>Age Group</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dawahs as $dawah)
                <tr>
                    <td>{{ $dawah->title }}</td>
                    <td>{{ $dawah->slug }}</td>
                    <td>{{ $dawah->description }}</td>
                    <td>{{ $dawah->type }}</td>
                    <td>{{ $dawah->age_group }}</td>
                    <td>{{ $dawah->status }}</td>
                    <td>
                        <a href="{{ route('admin.dawah.assign-teacher-form', $dawah->id) }}" class="btn btn-warning btn-sm">Assign Teacher</a>
                        <a href="{{ route('admin.dawah.create-lesson', $dawah->id) }}" class="btn btn-info btn-sm">Add Lesson</a>
                        <a href="{{ route('admin.dawah.edit', $dawah->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <form action="{{ route('admin.dawah.delete', $dawah->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                        </form>
                        <a href="{{ route('admin.dawah.view-lessons', $dawah->id) }}" class="btn btn-success btn-sm">View Lessons</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
