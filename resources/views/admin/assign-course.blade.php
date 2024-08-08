@extends('layouts.adminapp')

@section('content')
@include('partials.admin_header')
<div class="container mt-4">
    <h1>Assign Courses</h1>
    <div class="mb-3">
        <button class="btn btn-primary" onclick="location.href='{{ route('admin.dashboard') }}'">Back to Dashboard</button>
    </div>
    <form action="{{ route('admin.assign-course.post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="course_id">Select Course</label>
            <select name="course_id" id="course_id" class="form-control" required>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="user_id">Select Lecturer/Teacher</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}">{{ $lecturer->name }} (Lecturer)</option>
                @endforeach
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }} (Teacher)</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Assign Course</button>
    </form>
    
    <h2 class="mt-5">Current Assignments</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course</th>
                <th>Lecturers/Teachers</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $course)
                <tr>
                    <td>{{ $course->name }}</td>
                    <td>
                        <ul>
                            @foreach($course->users as $user)
                                <li>{{ $user->name }} ({{ ucfirst($user->pivot->role) }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        @foreach($course->users as $user)
                            <button class="btn btn-danger" onclick="confirmAction('{{ route('admin.unassign-course') }}', {{ $course->id }}, {{ $user->id }}, 'Are you sure you want to unassign this course?')">Unassign {{ $user->name }}</button>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function confirmAction(url, courseId, userId, message) {
        if (confirm(message)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            form.appendChild(token);

            const courseInput = document.createElement('input');
            courseInput.type = 'hidden';
            courseInput.name = 'course_id';
            courseInput.value = courseId;
            form.appendChild(courseInput);

            const userInput = document.createElement('input');
            userInput.type = 'hidden';
            userInput.name = 'user_id';
            userInput.value = userId;
            form.appendChild(userInput);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
