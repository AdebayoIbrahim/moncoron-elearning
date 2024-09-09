@extends('layouts.adminapp')
@section('content')
    @include('partials.admin_header')
    @csrf
    @vite(['resources/css/create-assessment/assessment.css'])
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
                                    <audio controls class="w-100 mb-3">
                                        <source src="{{ asset('storage/' . $lesson->audio) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @endif

                                @if($lesson->image)
                                    <img src="{{ asset('storage/' . $lesson->image) }}" alt="Lesson Image" class="img-fluid">
                                @endif
                            </div>
                        </div>
                    </div>
                        <!-- Left side: Chat UI -->
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h5>Chat</h5>
                            </div>
                            <div class="card-body" id="chat-box" style="height: 450px; overflow-y: scroll;">
                                <!-- Chat messages will be displayed here -->
                            </div>
                            <div class="card-footer">
                                <form id="chat-form">
                                    <div class="input-group">
                                        <input type="text" id="chat-input" class="form-control" placeholder="Type a message..." />
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            
        </div>
    </div>

    <script>
        // JS for handling chat functionality (basic example)
        document.getElementById('chat-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const chatInput = document.getElementById('chat-input');
            const chatBox = document.getElementById('chat-box');

            if (chatInput.value.trim() !== '') {
                // Append new message to chat box
                const newMessage = document.createElement('p');
                newMessage.innerText = chatInput.value;
                chatBox.appendChild(newMessage);

                // Clear chat input field
                chatInput.value = '';

                // Scroll to bottom of chat box
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    </script>
@endsection
