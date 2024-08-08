@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Lesson Content</div>
                <div class="card-body">
                    @include('partials.lesson', ['lesson' => $lesson, 'userCourseLesson' => $userCourseLesson])
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">Chat</div>
                <div class="card-body">
                    <h2>Welcome to the Classroom</h2>
                    <p>Current Route: {{ $routeNamePart }}</p>
                    <!-- Chat message list -->
                    <ul id="messages">
                        @foreach($messages as $message)
                            <li id="message-{{ $message->id }}">
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
                                @if (Auth::user()->isAdmin() || Auth::id() == $message->user_id)
                                    <div class="dropdown" style="display:inline;">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $message->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            ...
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $message->id }}">
                                            <li><a class="dropdown-item" href="#" onclick="deleteMessage({{ $message->id }})">Delete</a></li>
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <!-- Message form -->
                    <form id="message-form" action="{{ route('chat.send', ['course_id' => $course_id, 'lesson_id' => $lesson_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Type a message" required>
                            <input type="file" name="file" class="form-control-file">
                            <button type="submit" class="btn btn-primary">Send</button>
                            {{-- Commented out audio and video recording features --}}
                            {{-- <button type="button" id="toggle-audio-record" class="btn btn-secondary">Audio</button>
                            <button type="button" id="toggle-video-record" class="btn btn-secondary">Video</button> --}}
                        </div>
                        {{-- <input type="hidden" name="audio_data" id="audio_data">
                        <input type="hidden" name="video_data" id="video_data"> --}}
                    </form>
                    <div id="recordings-preview" style="margin-top: 20px;">
                        {{-- <audio id="audio-preview" controls style="display: none;"></audio>
                        <video id="video-preview" width="320" height="240" controls style="display: none;"></video> --}}
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
<script>
    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });

    const channel = pusher.subscribe('chat');
    channel.bind('MessageSent', function(data) {
        const messageElement = document.createElement('li');
        messageElement.id = 'message-' + data.message.id;
        messageElement.innerHTML = `${data.user.name}: ${data.message.message}`;
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
        if (data.user.isAdmin || data.user.id === {{ Auth::id() }}) {
            const dropdownDiv = document.createElement('div');
            dropdownDiv.className = 'dropdown';
            dropdownDiv.style.display = 'inline';

            const dropdownButton = document.createElement('button');
            dropdownButton.className = 'btn btn-secondary dropdown-toggle';
            dropdownButton.type = 'button';
            dropdownButton.id = 'dropdownMenuButton' + data.message.id;
            dropdownButton.setAttribute('data-bs-toggle', 'dropdown');
            dropdownButton.setAttribute('aria-expanded', 'false');
            dropdownButton.textContent = '...';

            const dropdownMenu = document.createElement('ul');
            dropdownMenu.className = 'dropdown-menu';
            dropdownMenu.setAttribute('aria-labelledby', 'dropdownMenuButton' + data.message.id);

            const deleteOption = document.createElement('li');
            const deleteLink = document.createElement('a');
            deleteLink.className = 'dropdown-item';
            deleteLink.href = '#';
            deleteLink.textContent = 'Delete';
            deleteLink.onclick = () => deleteMessage(data.message.id);

            deleteOption.appendChild(deleteLink);
            dropdownMenu.appendChild(deleteOption);
            dropdownDiv.appendChild(dropdownButton);
            dropdownDiv.appendChild(dropdownMenu);

            messageElement.appendChild(dropdownDiv);
        }
        document.getElementById('messages').appendChild(messageElement);
    });

    document.getElementById('message-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        await axios.post(`{{ route('chat.send', ['course_id' => $course_id, 'lesson_id' => $lesson_id]) }}`, formData);
        e.target.reset();
        document.getElementById('audio-preview').style.display = 'none';
        document.getElementById('video-preview').style.display = 'none';
    });

    async function deleteMessage(id) {
        if (confirm('Are you sure you want to delete this message?')) {
            await axios.post(`{{ route('chat.delete', ['courseId' => $course_id, 'lessonId' => $lesson_id]) }}`, {
                message_id: id,
                _token: '{{ csrf_token() }}'
            });
            document.getElementById('message-' + id).remove();
        }
    }
</script>
@endsection
