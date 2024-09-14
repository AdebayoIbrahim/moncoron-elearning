@extends('layouts.adminapp')
@section('content')
    @include('partials.admin_header')
    @csrf
    @vite(['resources/css/create-assessment/assessment.css','resources/js/courseview/lessonview.js'])
    <div class="container-fluid">
        <div class="row mt-2">
            {{-- @include('partials.admin_sidebar') --}}
         
                <div class="lesson_view_tool">
                   <div class="lesson_headr">
                       <h3 class="text-center" id = "file_correlation">{{$lesson->name}}</h3>
                       {{-- lesson-desc --}}
                       <p>{{$lesson->description}}</p>
                   </div>
                   
                   <div class="tool_add">
                    <button type="button" class="btn btn-primary btn-md" id = "add_assessment_btn">Add Assessment</button>
                   </div>
                </div>
                <div class="row Chat_ui">
                                <!-- Right side: Lesson Media Files -->
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <h5>Lesson Media</h5>
                            </div>
                            <div class="card-body media_container">
                                @if($lesson->video)
                                    <h4>Video</h4>
                                    <video controls class="w-100 mb-3">
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
                                    <img src="{{ asset('storage/' . $lesson->image) }}" alt="Lesson Image" class="img-fluid" style="object-fit: cover;widht: clac(100%-30px);height: 450px; border-radius: 0.4rem">
                                @endif
                            </div>
                        </div>
                    </div>
                        <!-- Left side: Chat UI -->
                        <div class="col-md-5">
                            <input type="hidden" id="curruserid" value="{{ auth()->user()->id }}">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Group Chat</h5>
                                </div>
                                <div class="card-body" id="chat-box" style="height: 450px; overflow-y: auto;
                                overflew-x:hidden; padding: 15px;width:100%">
                                    <!-- Chat messages will be displayed here -->
                                </div>
                                <div class="card-footer">
                                    <div class="input-group">
                                        <div class="form_datas"><input type="text" id="chat-input" class="form-control" placeholder="Type a message..." />
                                        <div class="d-flex gap-0">
                                        <button class="btn btn-primay no_styling_btn">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-primay no_styling_btn" id = "audio_btn_record">
                                            <i class="fa-solid fa-microphone"></i>
                                        </button>
                                        </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="send-message"><i class="fa fa-paper-plane send-paper" aria-hidden="true"></i></button>
                                        {{-- audio-signal-flow --}}
                                        <div class="audio-signal">
                                            <input type="hidden" id="audio_data" name="audio_data" />
                                            <h4 id = "record_text">Recording Message</h4>
                                            <div class="img_record_wrapper" id = "record_wave">
                                                <img src="{{asset ('images/Recording.gif') }}" alt="audio-wave" style="width: 85px; height: 85px">
                                            </div>

                                            {{-- last_flex_action_btn --}}
                                            <div class="d-flex justify-content-between" style="width: 70%; ">

                                                <button class="btn btn-primay no_styling_btn record_btns" id = "cancel_record">
                                                    <i class="fas fa-times"style = "color: #ffff"></i>
                                                </button>
                                                <button class="btn btn-primay no_styling_btn record_btns" id = "stop_record">

                                                    <i class="fas fa-stop b" style = "color:red"></i>
                                                </button>
                                                <button class="btn btn-primay no_styling_btn record_btns" id = "send_audio_record">
                                                    <i class="fa fa-paper-plane" aria-hidden="true" style = "color: #ffff !important"></i>
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
@endsection
