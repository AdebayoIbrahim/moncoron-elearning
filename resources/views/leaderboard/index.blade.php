@extends('layouts.app')

@section('content')
@include('partials.admin_header')
<div class="container">
    <h1>Leaderboard</h1>

    <form method="GET" action="{{ route('leaderboard.index') }}" class="mb-4">
        <div class="form-row">
            <div class="col-md-4">
                <select name="filter" class="form-control">
                    <option value="overall" {{ $filter == 'overall' ? 'selected' : '' }}>Overall</option>
                    <option value="course" {{ $filter == 'course' ? 'selected' : '' }}>By Course</option>
                    <option value="country" {{ $filter == 'country' ? 'selected' : '' }}>By Country</option>
                </select>
            </div>

            <div class="col-md-4">
                <select name="course_id" class="form-control" {{ $filter != 'course' ? 'disabled' : '' }}>
                    <option value="">Select Course</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <select name="country" class="form-control" {{ $filter != 'country' ? 'disabled' : '' }}>
                    <option value="">Select Country</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->country }}" {{ $country == $country->country ? 'selected' : '' }}>{{ $country->country }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 mt-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    @if($leaderboard->isEmpty())
        <p>No records found.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Total Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaderboard as $entry)
                    <tr>
                        <td>{{ $entry->name }}</td>
                        <td>{{ $entry->country }}</td>
                        <td>{{ $entry->total_score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.querySelector('select[name="filter"]').addEventListener('change', function () {
        const courseSelect = document.querySelector('select[name="course_id"]');
        const countrySelect = document.querySelector('select[name="country"]');

        courseSelect.disabled = this.value !== 'course';
        countrySelect.disabled = this.value !== 'country';
    });
</script>
@endsection
