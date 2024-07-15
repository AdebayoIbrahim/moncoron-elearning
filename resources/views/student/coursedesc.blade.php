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
                    {{$course->name}} <a class="btn btn-primary pull-right" href="/courses"><i class="fa fa-arrow-left"></i> Go Back</a>
                </h2>
                <div class="card-body">
                    {{$course->description}}
                    <hr />
                    <h2 class="text-bold">Lessons</h2>
                    <div class="accordion" id="accordionExample">
                @if($lessons->isEmpty())
                    <h2 class="text-danger text-bold">No lessons are available for this course yet!!!</h2>
                @else
                @php
                    // Variable to keep track of whether all previous lessons are completed
                    $allPreviousCompleted = true;
                @endphp

                    @foreach($course->lessons as $lesson)
                        @php
                            $completed = $lesson->users->isNotEmpty() ? (bool) $lesson->users->first()->pivot->completed : false;
                        @endphp

                        <!-- Debugging: Display the value of $completed -->
                        <!-- @dump($completed) -->

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button text-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$lesson->id}}" aria-expanded="false" aria-controls="collapse{{$lesson->id}}" id="button{{$lesson->id}}" @if(!$loop->first && !$allPreviousCompleted) disabled @endif>
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
                                            <video controls="" width="440" height="250" id="video{{$lesson->id}}">
                                                <source src="{{ asset('storage/' . $lesson->video) }}" type="video/mp4">
                                            </video>
                                        </div>
                                    @endif
                                    @if($lesson->audio != '')
                                        <div class="audio">
                                            <audio controls="" id="audio{{$lesson->id}}">
                                                <source src="{{ asset('storage/' . $lesson->audio) }}" type="audio/mp3">
                                            </audio>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @php
                            // Update the $allPreviousCompleted variable
                            if (!$completed) {
                                $allPreviousCompleted = false;
                            }
                        @endphp
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