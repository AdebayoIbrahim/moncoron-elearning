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
                <h2 class="card-header">
                    Courses
                </h2>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Available Courses</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">My Courses</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                        <div class="row my-bold-row">
                            @foreach($mycourses as $mycourse)
                                <div class="col-sm-4 col-lg-3">
                                    <div class="card mb-4 text-dark bg-light">
                                        <div class="card-body text-center">
                                            <div class="fw-semibold my-bold-num mb-4">
                                                @if($mycourse->image != '')
                                                    <img src="{{ asset('storage/' . $mycourse->image) }}" class="rounded-circle" width="133" height="133"/>
                                                @else
                                                    <img src="{{ asset('images/group-305.svg') }}" class="rounded-circle"/>
                                                @endif
                                            </div>
                                            <div class="fw-semibold my-bold-num-text mb-2">{{$mycourse->name}}</div>
                                            <a href="/courses/view/{{$mycourse->id}}" class="btn btn-primary" id="">View</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                            <div class="row my-bold-row">
                            @foreach($courses as $course)
                                <div class="col-sm-4 col-lg-3">
                                    <div class="card mb-4 text-dark bg-light">
                                        <div class="card-body text-center">
                                            <div class="fw-semibold my-bold-num mb-4">                                                
                                                @if($course->image != '')
                                                    <img src="{{ asset('storage/' . $course->image) }}" class="rounded-circle" width="133" height="133"/>
                                                @else
                                                    <img src="{{ asset('images/group-305.svg') }}" class="rounded-circle"/>
                                                @endif
                                            </div>
                                            <div class="fw-semibold my-bold-num-text mb-2">{{$course->name}}</div>
                                            <small class="text-success mb-4" style="display: block;">
                                            @if($course->price != 0)    
                                            <span class="badge text-bg-primary">Premium</span>
                                            @else
                                            <span class="badge text-bg-primary">Free</span>
                                            @endif
                                            </small>
                                            @if($course->is_locked != 0)
                                            <button type="button" disabled="" class="btn btn-secondary">Enroll</button>
                                            @else
                                            <button type="button" class="btn btn-primary enrollCourse" id="{{$course->id}}">Enroll</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Course Enrollment -->
<div class="modal fade" id="EnrollModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 text-bold text-primary" id="exampleModalLabel">New Course Enrollment</h1>
        <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form  role="form" id="paymentForm" method="post">
      @csrf
      <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-4">
                        <h4 class="label text-bold">Are you sure you want to enroll for this course?</h4>
                        <p><b>Course Title:</b> <span id="course-title"></span></p>
                        <p><b>Course Description:</b> <span id="course-description"></span></p>
                        <p><b>Price:</b> $<span id="course-price"></span></p>
                    </div>
                </div>
            </div>
            <input type="text" id="course_id" class="hidden" name="course_id">
            <input type="email" id="email" class="hidden" name="email" value="{{ auth()->user()->email }}">
            <input type="text" id="amount" class="hidden" name="amount" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i> No</button>
        <button type="button" class="btn btn-primary" onclick="payWithPaystack()"><i class="fa fa-check"></i> Yes</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Course Enrollment -->
@endsection