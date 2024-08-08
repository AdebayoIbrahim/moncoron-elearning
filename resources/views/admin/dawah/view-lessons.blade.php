@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Lessons for Dawah Course: {{ $dawah->title }}</h1>
    <a href="{{ route('admin.dawah.create-lesson', $dawah->id) }}" class="btn btn-primary mb-3">Add New Lesson</a>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Resource Type</th>
                <th>Source</th>
                <th>Resource URL</th>
                <th>Description</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lessons as $lesson)
                <tr>
                    <td>{{ $lesson->title }}</td>
                    <td>{{ $lesson->resource_type }}</td>
                    <td>{{ $lesson->source }}</td>
                    <td>{{ $lesson->resource_url }}</td>
                    <td>{{ $lesson->description }}</td>
                    <td>{{ $lesson->order }}</td>
                    <td>
                        <a href="{{ route('admin.dawah.edit-lesson', ['dawahId' => $dawah->id, 'lessonId' => $lesson->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.dawah.delete-lesson', ['dawahId' => $dawah->id, 'lessonId' => $lesson->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this lesson?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
