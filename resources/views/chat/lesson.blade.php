@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Lesson Content</div>
                <div class="card-body">
                    <h2>Lesson Content for Course ID: {{ $course_id }} and Lesson ID: {{ $lesson_id }}</h2>
                    <!-- Display lesson content here -->
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">Chat</div>
                <div class="card-body">
                    <h2>Welcome to the Chat</h2>
                    <p>Current Route: {{ $routeNamePart }}</p>
                    <!-- Chat message list -->
                    <ul id="messages">
                        @foreach($messages as $message)
                            <li>
                                {{ $message->user->name }}: {{ $message->message }}
                                @if ($message->file_type)
                                    @if ($message->file_type === 'image')
                                        <img src="{{ asset('storage/' . $message->file_path) }}" alt="Image" style="max-width: 200px;">
                                    @elseif ($message->file_type === 'video')
                                        <video src="{{ asset('storage/' . $message->file_path) }}" controls style="max-width: 200px;"></video>
                                    @elseif ($message->file_type === 'audio')
                                        <audio src="{{ asset('storage/' . $message->file_path) }}" controls></audio>
                                    @else
                                        <a href="{{ asset('storage/' . $message->file_path) }}" download>Download File</a>
                                    @endif
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <!-- Message form -->
                    <form id="message-form" action="{{ route('chat.send', ['course_id' => $course_id, 'lesson_id' => $lesson_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="message" placeholder="Type a message" required>
                        <input type="file" name="file">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@7.0.3/dist/web/pusher.min.js"></script>
<script>
    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });

    const channel = pusher.subscribe('chat');
    channel.bind('MessageSent', function(data) {
        const messageElement = document.createElement('li');
        messageElement.textContent = `${data.user.name}: ${data.message.message}`;
        if (data.message.file_type) {
            if (data.message.file_type === 'image') {
                const img = document.createElement('img');
                img.src = `{{ asset('storage/') }}/${data.message.file_path}`;
                img.style.maxWidth = '200px';
                messageElement.appendChild(img);
            } else if (data.message.file_type === 'video') {
                const video = document.createElement('video');
                video.src = `{{ asset('storage/') }}/${data.message.file_path}`;
                video.controls = true;
                video.style.maxWidth = '200px';
                messageElement.appendChild(video);
            } else if (data.message.file_type === 'audio') {
                const audio = document.createElement('audio');
                audio.src = `{{ asset('storage/') }}/${data.message.file_path}`;
                audio.controls = true;
                messageElement.appendChild(audio);
            } else {
                const link = document.createElement('a');
                link.href = `{{ asset('storage/') }}/${data.message.file_path}`;
                link.download = 'Download File';
                link.textContent = 'Download File';
                messageElement.appendChild(link);
            }
        }
        document.getElementById('messages').appendChild(messageElement);
    });

    document.getElementById('message-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        await axios.post(`{{ route('chat.send', ['course_id' => $course_id, 'lesson_id' => $lesson_id]) }}`, formData);
        e.target.reset();
    });
</script>
@endsection
