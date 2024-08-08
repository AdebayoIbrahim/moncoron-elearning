@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $lesson->title }}</h2>
    <p>{{ $lesson->description }}</p>
    
    @if($studentMedia->isNotEmpty())
        <div class="student-media">
            @foreach($studentMedia as $media)
                <div class="media-item">
                    <strong>{{ $media->user ? $media->user->name : 'Unknown User' }}</strong>
                    @if(pathinfo($media->file_path ?? $media->audio_path ?? $media->video_path, PATHINFO_EXTENSION) == 'mp4')
                        <video src="{{ Storage::url($media->video_path) }}" controls></video>
                    @elseif(pathinfo($media->file_path ?? $media->audio_path ?? $media->video_path, PATHINFO_EXTENSION) == 'webm')
                        <audio src="{{ Storage::url($media->audio_path) }}" controls></audio>
                    @else
                        <a href="{{ Storage::url($media->file_path) }}" target="_blank">View File</a>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p>No student media available.</p>
    @endif

    <!-- Form to upload student media -->
    <form action="{{ route('lessons.uploadStudentMedia', ['course_id' => $lesson->course_id, 'id' => $lesson->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Upload File</label>
            <input type="file" name="file" class="form-control">
        </div>
        <div class="form-group">
            <label for="audio_data">Record Audio</label>
            <input type="hidden" name="audio_data" id="audio_data">
            <button type="button" onclick="startRecording()">Start Recording</button>
            <button type="button" onclick="stopRecording()">Stop Recording</button>
        </div>
        <div class="form-group">
            <label for="video_data">Record Video</label>
            <input type="hidden" name="video_data" id="video_data">
            <button type="button" onclick="startVideoRecording()">Start Recording</button>
            <button type="button" onclick="stopVideoRecording()">Stop Recording</button>
        </div>
        <button type="submit" class="btn btn-primary">Upload Media</button>
    </form>

    <!-- Form to mark lesson as complete -->
    <form action="{{ route('lessons.complete', ['course_id' => $lesson->course_id, 'id' => $lesson->id]) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Complete Lesson</button>
    </form>

</div>

<script>
    // JavaScript for recording audio
    let mediaRecorder;
    let audioChunks = [];

    function startRecording() {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.start();
                mediaRecorder.ondataavailable = event => {
                    audioChunks.push(event.data);
                };
                mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        document.getElementById('audio_data').value = reader.result;
                    };
                    reader.readAsDataURL(audioBlob);
                    audioChunks = [];
                };
            });
    }

    function stopRecording() {
        mediaRecorder.stop();
    }

    // JavaScript for recording video
    let videoRecorder;
    let videoChunks = [];

    function startVideoRecording() {
        navigator.mediaDevices.getUserMedia({ video: true, audio: true })
            .then(stream => {
                videoRecorder = new MediaRecorder(stream);
                videoRecorder.start();
                videoRecorder.ondataavailable = event => {
                    videoChunks.push(event.data);
                };
                videoRecorder.onstop = () => {
                    const videoBlob = new Blob(videoChunks, { type: 'video/webm' });
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        document.getElementById('video_data').value = reader.result;
                    };
                    reader.readAsDataURL(videoBlob);
                    videoChunks = [];
                };
            });
    }

    function stopVideoRecording() {
        videoRecorder.stop();
    }
</script>
@endsection
