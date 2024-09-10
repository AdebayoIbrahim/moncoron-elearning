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
                       <h3 class="text-center">{{$lesson->name}}</h3>
                       {{-- lesson-desc --}}
                       <p>{{$lesson->description}}</p>
                   </div>
                   
                   <div class="tool_add">
                    <button type="button" class="btn btn-primary btn-md">Add Assessment</button>
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
                                    <img src="{{ asset('storage/' . $lesson->image) }}" alt="Lesson Image" class="img-fluid" style="object-fit: cover;widht: clac(100%-30px);height: 100%">
                                @endif
                            </div>
                        </div>
                    </div>
                        <!-- Left side: Chat UI -->
                        <div class="col-md-5">
                            <input type="hidden" id="curruserid" value="{{ auth()->user()->id }}">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Chat</h5>
                                </div>
                                <div class="card-body" id="chat-box" style="height: 450px; overflow-y: auto; padding: 15px;width:100%">
                                    <!-- Chat messages will be displayed here -->
                                </div>
                                <div class="card-footer">
                                    <div class="input-group">
                                        <input type="text" id="chat-input" class="form-control" placeholder="Type a message..." />
                                        <button type="button" class="btn btn-primary" id="send-message">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                </div>
            
        </div>
        
    </div> 
@endsection
