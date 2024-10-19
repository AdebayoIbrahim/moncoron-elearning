@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
@vite(['resources/css/Main/main.css','resources/css/Main/main.js'])

<div class="container-fluid">
    <div class="row">
        {{-- loader-animation --}}
        {{-- <div class="loader_spinner_lecturer" style="text-align: center">
                <div class="spinner-border text-secondary spinner_size" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div id="loader_lecturer" class="pt-4">
                    <h5>Fetching Details...</h5>
                </div>
            </div> --}}
        <section class="lecturer_view_container">
            <section class="lecturer_bio">
                <img src={{asset ('/images/Qari.jpeg')}} alt="dahee_image" style="width: 200px;height: 200px; border-radius: 50%;object-fit:cover;">
                <div id="name_lecturer">
                    <h4>Abdrahman Aloayuwiyy</h4>
                    <p style="max-width: 100ch">Shaykh Saud ibn Ibrahim ibn Muhammad ash-Shuraim was on October the 15th, 1966 in Riyadh, Saudi Arabia. He is one of the prayer leaders and Friday preachers at the Masjid al-Haram in Makkah. A Quranic reciter, he also holds a PhD degree in Sharia (Islamic studies) at the Umm al-Qura University in Makkah.
                        Shaykh Shuraim has been leading the Taraweeh prayers during Ramadan in Makkah since 1992.
                    </p>
                    <button class="btn btn-primary md">
                        <i class="fas fa-play"></i>
                        Play Radio
                    </button>
                </div>
            </section>
            {{-- div-content-area --}}

            <section class="upload_contents">
                {{-- <div><input type="text" name="lexture_search" id="search_lecture" placeholder="Search...."></div> --}}
                <div class="lecture_switcher">
                    <div class="d-flex" style="gap: 3rem;">
                        <div class="switcher_toggle activePane">Audio</div>
                        <div class="switcher_toggle">Video</div>
                    </div>
                </div>
            </section>

            {{-- is-uploaded-medias --}}
            <div class="media_targets">
                <div class="uploaded_media is_video">
                    @for($i = 0; $i < 6; $i++) <div class="media_video_conainer">
                        <div>
                            <video src={{asset ('/images/movie.mp4')}} controls crossorigin playsinline></video>
                        </div>
                        {{-- video-title-andlength --}}
                        <div class="video_footer">
                            <h6 class="video-name">
                                Fiqh-Sunah
                            </h6>
                            {{-- length-video --}}
                            <h6 id="video_length">

                            </h6>
                        </div>
                </div>
                @endfor
            </div>
    </div>
    </section>

</div>
</div>
{{-- absolute-audio-player --}}
<div class="absolute_player_audio">
    <div class="close_audio"><i class="fa fa-times" aria-hidden="true" style="color: #d6f2ff;font-size:1.5rem"></i></div>
    <audio src={{asset ('images/horse.mp3')}} crossorigin playsinline controls>
    </audio>
</div>
@endsection
