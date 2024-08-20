@extends('layouts.app')

@section('content')
@include('partials.admin_header')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <!-- Lesson Content Section -->
            <div class="card">
                <div class="card-header">Lesson Content</div>
                <div class="card-body">
                    @include('partials.lesson', ['lesson' => $lesson, 'userCourseLesson' => $userCourseLesson])
                </div>
            </div>

            <!-- Chat Section -->
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
                                    @elseif ($message->file_type === 'audio')
                                        <audio src="{{ asset('storage/' . $message->file_path) }}" controls></audio>
                                    @else
                                        <a href="{{ asset('storage/' . $message->file_path) }}" download>Download File</a>
                                    @endif
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <!-- Message Form for Text and File Attachments -->
                    <form id="message-form" action="{{ route('chat.send', ['course_id' => $course_id, 'lesson_id' => $lesson_id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="input-group">
        <input type="text" name="message" class="form-control" placeholder="Type a message">
        <input type="file" name="file" class="form-control-file">
    </div>

    <!-- Recording Section -->
    <div class="mt-2">
        <button type="button" id="record-btn" class="btn btn-secondary">Rec</button>
        <button type="button" id="stop-btn" class="btn btn-danger" style="display: none;">Stop</button>
        <audio id="audio-preview" controls style="display: none;"></audio>
        <input type="hidden" id="audio-data" name="audio_data" />
    </div>

    <button type="submit" class="btn btn-primary mt-2">Send</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recordBtn = document.getElementById('record-btn');
    const stopBtn = document.getElementById('stop-btn');
    const audioPreview = document.getElementById('audio-preview');
    const audioDataInput = document.getElementById('audio-data');

    let mediaRecorder;
    let audioChunks = [];

    recordBtn.addEventListener('click', async function() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert("Your browser does not support audio recording.");
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);

            mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    audioChunks.push(event.data);
                }
            };

            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                const reader = new FileReader();
                reader.readAsDataURL(audioBlob); // Convert Blob to Base64
                reader.onloadend = () => {
                    audioDataInput.value = reader.result;  // Send Base64 encoded audio to server
                };

                const audioUrl = URL.createObjectURL(audioBlob);
                audioPreview.src = audioUrl;
                audioPreview.style.display = 'block';
            };

            mediaRecorder.start();
            recordBtn.style.display = 'none';
            stopBtn.style.display = 'inline-block';
        } catch (error) {
            console.error("Error accessing microphone", error);
            alert("Failed to access your microphone.");
        }
    });

    stopBtn.addEventListener('click', function() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
        }

        stopBtn.style.display = 'none';
        recordBtn.style.display = 'inline-block';
    });
});
</script>

