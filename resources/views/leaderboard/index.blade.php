@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Leaderboard</h1>
    <form method="GET" action="{{ route('leaderboard.index') }}">
        <div class="row">
            <div class="col-md-4">
                <select name="filter" class="form-control" onchange="this.form.submit()">
                    <option value="overall" {{ $filter == 'overall' ? 'selected' : '' }}>Overall</option>
                    <option value="course" {{ $filter == 'course' ? 'selected' : '' }}>By Course</option>
                    <option value="country" {{ $filter == 'country' ? 'selected' : '' }}>By Country</option>
                </select>
            </div>
            <div class="col-md-4">
                <select name="course_id" class="form-control" onchange="this.form.submit()">
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="country" class="form-control" onchange="this.form.submit()">
                    <option value="">Select Country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->country }}" {{ $country == $country->country ? 'selected' : '' }}>{{ $country->country }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>Country</th>
                <th>Course</th>
                <th>Total Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaderboard as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $entry->name }}</td>
                    <td>{{ $entry->country }}</td>
                    <td>{{ $entry->course_name }}</td>
                    <td>{{ $entry->total_score }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
