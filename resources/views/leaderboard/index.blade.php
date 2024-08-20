@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Leaderboard</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('leaderboard.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="filter">Filter</label>
                <select name="filter" id="filter" class="form-control">
                    <option value="overall" {{ $filter == 'overall' ? 'selected' : '' }}>Overall</option>
                    <option value="course" {{ $filter == 'course' ? 'selected' : '' }}>By Course</option>
                    <option value="country" {{ $filter == 'country' ? 'selected' : '' }}>By Country</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="course_id">Course</label>
                <select name="course_id" id="course_id" class="form-control" {{ $filter != 'course' ? 'disabled' : '' }}>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="country">Country</label>
                <select name="country" id="country" class="form-control" {{ $filter != 'country' ? 'disabled' : '' }}>
                    <option value="">Select Country</option>
                    @foreach($countries as $countryOption)
                        <option value="{{ $countryOption->country }}" {{ $country == $countryOption->country ? 'selected' : '' }}>
                            {{ $countryOption->country }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
    </form>

    <!-- Leaderboard Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Total Score</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($leaderboard as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->country }}</td>
                        <td>{{ $user->total_score }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('scripts')
<script>
    document.getElementById('filter').addEventListener('change', function() {
        const filter = this.value;
        document.getElementById('course_id').disabled = (filter !== 'course');
        document.getElementById('country').disabled = (filter !== 'country');
    });
</script>
@endsection

@endsection
