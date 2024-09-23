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
                    <h2>
                        Courses <button type="button" class="btn btn-primary pull-right" id="addCourse"><i class="fa fa-plus"></i> Add New Course</a>
                    </h2>
                </div>
                <div class="card-body">
                    <table class="table-bordered table table-striped">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Capacity</th>
                                <th>Age Group</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$course->name}}</td>
                                <td>${{$course->price}}</td>
                                <td>{{$course->capacity}}</td>
                                <td>
                                    @if($course->age_group == 0)
                                    All Age Group
                                    @elseif($course->age_group == 1)
                                    Below 18
                                    @else
                                    18 Above
                                    @endif
                                </td>
                                <td>
                                    @if($course->is_locked == 0)
                                    <span class="badge text-bg-success">Unlock</span>
                                    @else
                                    <span class="badge text-bg-danger">Locked</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown text-center">
                                        <a href="#" class="d-block link-body-emphasis text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="fa fa-ellipsis-v"></span>
                                        </a>
                                        <ul class="dropdown-menu text-small">
                                            <li><a class="dropdown-item" href="/admin/courses/{{$course->id}}"><i class="fa fa-eye"></i> View</a></li>
                                            <li><a class="dropdown-item editCourse" href="#" id="{{$course->id}}"><i class="fa fa-pencil"></i> Edit</a></li>
                                            <li><a class="dropdown-item" href="/admin/courses/delete/{{$course->id}}"><i class="fa fa-trash"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Add New Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-bold text-primary" id="exampleModalLabel">Add New Course</h1>
                <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.courses.register') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Course Name</label>
                                <input type="text" class="form-control" name="name" required placeholder="Enter Course Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Course Image</label>
                                <input type="file" class="form-control" name="image" required id="file">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Slug</label>
                                <input type="text" class="form-control" name="slug" required placeholder="Enter Slug">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Price</label>
                                <input type="text" class="form-control" name="price" required placeholder="Enter Price">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Age Group</label>
                                <select class="form-control" name="age_group" required>
                                    <option value="" selected disabled>-- Select Age Group --</option>
                                    <option value="0">All Age Group</option>
                                    <option value="1">Below 18</option>
                                    <option value="2">18 Above</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Lock this course?</label>
                                <select class="form-control" name="is_locked" required>
                                    <option value="" selected disabled>-- Select Option --</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        {{-- course_type-special-ornormal --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Select Course Type</label>
                                <select class="form-control" name="course_type" required>
                                    <option value="" selected disabled>-- Select Option --</option>
                                    <option value="normal">Normal</option>
                                    <option value="special">Special</option>
                                </select>
                                <small class="text-xs">
                                    Special Courses are only visible through subscribtions
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Capacity</label>
                                <input type="number" class="form-control" name="capacity" required>
                                <small class="text-xs">
                                    Enter a number that indicates the number of students who can subscribe to this course. If unlimited, enter 0.
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Description</label>
                                <textarea type="number" class="form-control" name="description" required></textarea>
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
<!-- End Modal Add New Course -->


<!-- Edit Course Modal -->
<div class="modal fade " id="editCourseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-bold text-primary" id="exampleModalLabel">Edit Course</h1>
                <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.courses.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Course ID</label>
                                <input type="text" class="form-control" name="id" id="course_id" required placeholder="Enter Course Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Course Name</label>
                                <input type="text" class="form-control" name="name" id="name" required placeholder="Enter Course Name">
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Course Image</label>
                        <input type="file" class="form-control" name="image" required id="file">
                    </div>
                </div> -->
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Slug</label>
                                <input type="text" class="form-control" name="slug" id="slug" required placeholder="Enter Slug">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Price</label>
                                <input type="text" class="form-control" name="price" id="price" required placeholder="Enter Price">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Age Group</label>
                                <select class="form-control" name="age_group" required>
                                    <option id="age_group" value="" selected>-- Select Age Group --</option>
                                    <option value="0">All Age Group</option>
                                    <option value="1">Below 18</option>
                                    <option value="2">18 Above</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Lock this course?</label>
                                <select class="form-control" name="is_locked" required>
                                    <option id="is_locked" value="" selected>-- Select Option --</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Capacity</label>
                                <input type="number" class="form-control" name="capacity" id="capacity" required>
                                <small class="text-xs">
                                    Enter a number that indicates the number of students who can subscribe to this course. If unlimited, enter 0.
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="label text-bold">Description</label>
                                <textarea type="number" class="form-control" name="description" id="description" required></textarea>
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
<!-- End Modal Edit Course -->
@endsection
