@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')

<div class="container">
    <h1>User Management</h1>
    <table class="table">
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
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->status ? 'Active' : 'Suspended' }}</td>
                    <td>
                        @if ($user->status)
                            <button class="btn btn-warning" onclick="confirmSuspend({{ $user->id }})">Suspend</button>
                        @else
                            <button class="btn btn-success" onclick="confirmUnsuspend({{ $user->id }})">Unsuspend</button>
                        @endif
                        <button class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Suspend Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1" role="dialog" aria-labelledby="suspendModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suspendModalLabel">Confirm Suspend</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to suspend this user?
            </div>
            <div class="modal-footer">
                <form id="suspendForm" method="POST">
                    @csrf
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Suspend</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Unsuspend Modal -->
<div class="modal fade" id="unsuspendModal" tabindex="-1" role="dialog" aria-labelledby="unsuspendModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unsuspendModalLabel">Confirm Unsuspend</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to unsuspend this user?
            </div>
            <div class="modal-footer">
                <form id="unsuspendForm" method="POST">
                    @csrf
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Unsuspend</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function confirmSuspend(userId) {
        $('#suspendForm').attr('action', '/admin/users/suspend/' + userId);
        $('#suspendModal').modal('show');
    }

    function confirmUnsuspend(userId) {
        $('#unsuspendForm').attr('action', '/admin/users/unsuspend/' + userId);
        $('#unsuspendModal').modal('show');
    }

    function confirmDelete(userId) {
        $('#deleteForm').attr('action', '/admin/users/delete/' + userId);
        $('#deleteModal').modal('show');
    }
</script>
@endsection
