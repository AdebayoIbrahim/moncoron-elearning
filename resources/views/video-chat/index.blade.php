@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Video Chat</div>

                <div class="card-body">
                    <h2>Welcome to the Video Chat</h2>
                    <p>Current Route: {{ $routeNamePart }}</p>
                    <form id="create-room-form" action="{{ route('video-chat.call') }}" method="POST">
                        @csrf
                        <button id="callButton" type="button" class="btn btn-primary">Call User</button>
                    </form>
                    <div id="video-chat-container" style="display: none; margin-top: 20px;">
                        <video id="localVideo" autoplay muted width="320" height="240"></video>
                        <video id="remoteVideo" autoplay width="320" height="240"></video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@7.0.3/dist/web/pusher.min.js"></script>
<script src="{{ mix('js/echo.js') }}"></script>

<script>
    document.getElementById('callButton').addEventListener('click', () => {
        const userToCall = prompt('Enter the user ID to call:');
        if (userToCall) {
            callUser(userToCall);
            document.getElementById('video-chat-container').style.display = 'block';
        }
    });
</script>
@endsection
