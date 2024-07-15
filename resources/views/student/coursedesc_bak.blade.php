@extends('layouts.app')
@section('content')
@include('partials.header')
<div class="container-fluid">
    <div class="row">
        @include('partials.student_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <div class="card">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ session('error') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif                
                <h2 class="card-header text-bold">
                    {{$course->name}}
                </h2>
                <div class="card-body">
                    {{$course->description}}
                    <hr />
                    <h2 class="text-bold">Lessons</h2>
                    <div class="accordion" id="accordionExample">
                @if($lessons->isEmpty())
                    <h2 class="text-danger text-bold">No lessons are available for this course yet!!!</h2>
                @else
                    @foreach($lessons as $lesson)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                            <button class="accordion-button text-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$lesson->id}}" aria-expanded="false" aria-controls="collapse{{$lesson->id}}">
                                Lesson {{ $loop->index + 1 }}
                            </button>
                            </h2>
                            <div id="collapse{{$lesson->id}}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <strong>{{$lesson->name}}</strong>
                                    <br />
                                    {{$lesson->description}}
                                    @if($lesson->video != '')
                                        <div class="video">
                                            <video controls="" width="440" height="250">
                                                <source src="{{ asset('storage/' . $lesson->video) }}" type="video/mp4">
                                            </video>
                                        </div>
                                    @else                                        
                                    @endif
                                    @if($lesson->video != '')
                                        <div class="audio">
                                            <audio controls="">
                                                <source src="{{ asset('storage/' . $lesson->audio) }}" type="audio/mp3">
                                            </audio>
                                        </div>
                                    @else                                        
                                    @endif
                                </div>
                            </div>
                        </div> 
                    @endforeach                              
                @endif
                    </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@endsection