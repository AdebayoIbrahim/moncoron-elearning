@if($studentMedia->isNotEmpty())
    @foreach($studentMedia as $media)
        <div class="media">
            <h3>{{ $media->user ? $media->user->name : 'Unknown User' }}</h3>
            @if(pathinfo($media->file_path ?? $media->audio_path ?? $media->video_path, PATHINFO_EXTENSION) == 'mp4')
                <video width="320" height="240" controls>
                    <source src="{{ asset('storage/' . ($media->file_path ?? $media->audio_path ?? $media->video_path)) }}" type="video/mp4">
                </video>
            @elseif(pathinfo($media->file_path ?? $media->audio_path ?? $media->video_path, PATHINFO_EXTENSION) == 'webm')
                <video width="320" height="240" controls>
                    <source src="{{ asset('storage/' . ($media->file_path ?? $media->audio_path ?? $media->video_path)) }}" type="video/webm">
                </video>
            @else
                <audio controls>
                    <source src="{{ asset('storage/' . ($media->file_path ?? $media->audio_path ?? $media->video_path)) }}" type="audio/mp3">
                </audio>
            @endif
        </div>
    @endforeach
@else
    <p>No student media available.</p>
@endif
