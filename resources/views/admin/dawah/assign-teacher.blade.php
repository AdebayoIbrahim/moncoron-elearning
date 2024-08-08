@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Assign Teacher to Dawah Course: {{ $dawah->title }}</h1>
    <form action="{{ route('admin.dawah.assign-teacher', $dawah->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="user_id" class="form-label">Select Teacher</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assign Teacher</button>
    </form>
</div>
@endsection
