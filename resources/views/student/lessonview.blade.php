@extends('layouts.app')
@section('content')
@include('partials.header')
@csrf
@vite(['resources/css/create-assessment/assessment.css','resources/js/courseview/lessonview.js','resources/sass/app.scss'])


<div class="container-fluid">
    <div class="row mt-2">
        {{-- nosidebar-view --}}
        {{-- @include('partials.student_sidebar') --}}
        <div class="lesson_view_tool">
            <div class="lesson_headr">
                <h3 class="text-center" id="file_correlation">{{$lesson->name}}</h3>
                {{-- lesson-desc --}}
                <p>{{$lesson->description}}</p>
            </div>

            <div class="tool_add">
                <button type="button" class="btn btn-primary btn-md" id="add_assessment_btn">{{$hasassessment ? 'Manage Assessment' : 'Add Assessment'}}</button>
                {{-- //TODO:check --for-use-type-for-join-toappearhere-andon-condition --}}

                <button id="" class="btn btn-primary " style="margin-left: 5px">
                    <i class="fa-solid fa-calendar-days"></i>
                    Schedule a live class
                </button>
            </div>

        </div>
        <div class="row Chat_ui">

            <!-- Right side: Lesson Media Files -->
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header d-flex justify-content-between">
                        <h5>Lesson Media</h5>
                        <button id="StartCall" type="button" class="btn btn-primary btn-md">
                            <span><img width="25" height="25" src="https://img.icons8.com/color/48/online--v1.png" alt="online--v1" /></span>
                            Go Live
                        </button>
                    </div>
                    <div class="card-body media_container">
                        <div id="video_class_layout">
                            <div id="remote-video"></div>
                            <div id="local-video"></div>
                            <div class="controls_section">
                                {{-- mic-controls --}}

                                {{-- toggle-video --}}
                                <div id="togglevideo" class="control_container_vid">
                                    <i class="fa-solid fa-video icon_styles_con"></i>
                                </div>
                                {{-- togglemicrophone --}}
                                <div id="toggleaudio" class="control_container_vid">
                                    <i class="fa-solid fa-microphone icon_styles_con"></i>
                                </div>
                                {{-- end-call --}}
                                <div class="control_container_vid" id="end_call">
                                    <i class="fa-solid fa-phone icon_styles_con canccall"></i>
                                </div>


                            </div>
                        </div>
                        <div id="media_uploaded">
                            @if($lesson->video)
                            <h4>Video</h4>
                            <video controls class="w-100 mb-3" style="height: 60vh">
                                <source src="{{ asset('storage/'. $lesson->video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            @endif

                            @if($lesson->audio)
                            <h4>Audio</h4>
                            <audio controls class="w-100 mb-3" style="height: 50px">
                                <source src="{{ asset('storage/' . $lesson->audio) }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                            @endif

                            @if($lesson->image)
                            <h4>Image</h4>
                            <img src="{{ asset('storage/' . $lesson->image) }}" alt="Lesson Image" class="img-fluid" style="object-fit: cover;width: clac(100%-30px);height: 450px; border-radius: 0.4rem">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Left side: Chat UI -->
                <input type="hidden" id="curruserid" value="{{ auth()->user()->id }}">
                <div class="card">
                    <div class="card-header">
                        <h5>Group Chat : {{$lesson->lesson_number}}</h5>
                    </div>
                    <div class="card-body" id="chat-box" style="height: 450px; overflow-y: auto;
                                overflow-x:hidden; padding: 15px;width:100%">
                        <!-- Chat messages will be displayed here -->
                    </div>
                    <div class="card-footer">
                        <div class="input-group">
                            <div class="form_datas"><input type="text" id="chat-input" class="form-control" placeholder="Type a message..." />
                                <div class="d-flex gap-0">
                                    <button class="btn btn-primay no_styling_btn">
                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-primay no_styling_btn" id="audio_btn_record">
                                        <i class="fa-solid fa-microphone"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="send-message"><i class="fa fa-paper-plane send-paper" aria-hidden="true"></i></button>
                            {{-- audio-signal-flow --}}
                            <div class="audio-signal">
                                <input type="hidden" id="audio_data" name="audio_data" />
                                <h4 id="record_text">Recording Message</h4>
                                <div class="img_record_wrapper" id="record_wave">
                                    <img src="{{asset ('images/Recording.gif') }}" alt="audio-wave" style="width: 85px; height: 85px">
                                </div>

                                {{-- last_flex_action_btn --}}
                                <div class="d-flex justify-content-between" style="width: 70%; ">

                                    <button class="btn btn-primay no_styling_btn record_btns" id="cancel_record">
                                        <i class="fas fa-times" style="color: #ffff"></i>
                                    </button>
                                    <button class="btn btn-primay no_styling_btn record_btns" id="stop_record">

                                        <i class="fas fa-stop b" style="color:red"></i>
                                    </button>
                                    <button class="btn btn-primay no_styling_btn record_btns" id="send_audio_record">
                                        <i class="fa fa-paper-plane" aria-hidden="true" style="color: #ffff !important"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<div id="loadingAnimation" class="loading-frame">
    <h2>Streaming in progress..</h2>
    <div class="h6">Hang on! Your live class is about to begin.</div>
    <div>
        <img src=" {{asset ('images/Loader.gif') }}" alt="stream-loading" style="width: 110px; height: 110px;">
    </div>
</div>
@endsection
