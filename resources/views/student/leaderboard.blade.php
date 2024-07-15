@extends('layouts.app')
@section('content')
@include('partials.header')
<<div class="container">
    <h2>Leaderboard</h2>
    <form method="GET" action="{{ route('student.leaderboard') }}">
        <div class="row mb-4">
            <div class="col-md-4">
                <select name="course_id" class="form-control">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="country" class="form-control">
                    <option value="">All Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>Country</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaderboard as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $entry->user->name }}</td>
                    <td>{{ $entry->country }}</td>
                    <td>{{ $entry->points }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection