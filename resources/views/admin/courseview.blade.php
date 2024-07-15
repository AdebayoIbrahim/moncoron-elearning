@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <div class="card">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ session('error') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card-header">
                    <h2 class="text-bold">
                    {{$course->name}} <a class="btn btn-primary pull-right" href="/admin/courses"><i class="fa fa-arrow-left"></i> Go Back</a>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">{{$course->description}}</div>
                    <hr />
                    <h2 class="text-bold">Lessons <button class="btn btn-primary" id="addLesson"><i class="fa fa-plus"></i> Add Lesson</button></h2>
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
                                    @if($lesson->audio != '')
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
        </main>
    </div>
</div>

<!-- Add New Lesson Modal -->
<div class="modal fade" id="addLessonModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 text-bold text-primary" id="exampleModalLabel">Add New Course</h1>
        <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.courses.lesson') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
            <div class="row">
                <div class="col-md-6 hidden">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Course ID</label>
                        <input type="text" class="form-control" name="course_id" required placeholder="Enter Course Name" value="{{ $course->id}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Lesson Name</label>
                        <input type="text" class="form-control" name="name" required placeholder="Enter Course Name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Lesson Text</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Lesson Video</label>
                        <input type="file" class="form-control" name="video" accept=".mp4,.avi,.mkv">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Lesson Audio</label>
                        <input type="file" class="form-control" name="audio" accept=".mp3,.wav">
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Add New Lesson -->
@endsection