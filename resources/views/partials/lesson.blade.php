<div class="lesson-content">
    <h2>{{ $lesson->name }}</h2>
    <p>{{ $lesson->description }}</p>
    @if($lesson->video)
        <div class="video">
            <video controls width="440" height="250">
                <source src="{{ asset('storage/' . $lesson->video) }}" type="video/mp4">
            </video>
        </div>
    @endif
    @if($lesson->audio)
        <div class="audio">
            <audio controls>
                <source src="{{ asset('storage/' . $lesson->audio) }}" type="audio/mp3">
            </audio>
        </div>
    @endif

    @if($userCourseLesson)
        <h3>Your Notes</h3>
        <p>{{ $userCourseLesson->notes }}</p>
        @if($userCourseLesson->file_path)
            <h3>Your Uploaded File</h3>
            <a href="{{ asset('storage/' . $userCourseLesson->file_path) }}" download>Download File</a>
        @endif
        @if($userCourseLesson->audio_path)
            <h3>Your Recorded Audio</h3>
            <audio controls>
                <source src="{{ asset('storage/' . $userCourseLesson->audio_path) }}" type="audio/webm">
            </audio>
        @endif
        @if($userCourseLesson->video_path)
            <h3>Your Recorded Video</h3>
            <video width="320" height="240" controls>
                <source src="{{ asset('storage/' . $userCourseLesson->video_path) }}" type="video/webm">
            </video>
        @endif
    @endif
</div>
