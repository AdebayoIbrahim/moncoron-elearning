@extends('layouts.adminapp')

@section('content')
@include('partials.admin_header')
<div class="container mt-4">
    <h1>Manage Users</h1>
    <div class="mb-3">
        <button class="btn btn-primary" onclick="location.href='{{ route('admin.dashboard') }}'">Back to Dashboard</button>
    </div>
    <form action="{{ route('admin.users') }}" method="GET" class="form-inline mb-3">
        <label for="status" class="mr-2">Filter by Status:</label>
        <select name="status" id="status" class="form-control mr-2">
            <option value="">All</option>
            <option value="active"{{ request('status') == 'active' ? ' selected' : '' }}>Active</option>
            <option value="suspended"{{ request('status') == 'suspended' ? ' selected' : '' }}>Suspended</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->status ? 'Active' : 'Suspended' }}</td>
                    <td>
                        @if($user->status)
                            <button class="btn btn-warning" onclick="confirmAction('{{ route('admin.users.suspend', $user->id) }}', 'Are you sure you want to suspend this user?')">Suspend</button>
                        @else
                            <button class="btn btn-success" onclick="confirmAction('{{ route('admin.users.unsuspend', $user->id) }}', 'Are you sure you want to unsuspend this user?')">Unsuspend</button>
                        @endif
                        <button class="btn btn-danger" onclick="confirmAction('{{ route('admin.users.delete', $user->id) }}', 'Are you sure you want to delete this user?')">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function confirmAction(url, message) {
        if (confirm(message)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            form.appendChild(token);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'POST';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
