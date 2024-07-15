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
                        Teachers <button type="button" class="btn btn-primary pull-right" id="addTeacher"><i class="fa fa-plus"></i> Add New Teacher</a>
                    </h2>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table-bordered table table-striped">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Verified</th>
                                    <!-- <th>Courses</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teacherusers as $teacheruser)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$teacheruser->name}}</td>
                                    <td>{{$teacheruser->email}}</td>
                                    <td>{{$teacheruser->phone}}</td>
                                    <td>{{$teacheruser->status}}</td>
                                    <td>{{$teacheruser->email_verified_at}}</td>
                                    <td>
                                    <div class="dropdown text-center">
                                        <a href="#" class="d-block link-body-emphasis text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="fa fa-ellipsis-v"></span>
                                        </a>
                                        <ul class="dropdown-menu text-small">
                                            <li><a class="dropdown-item" href="#"><i class="fa fa-eye"></i> View</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fa fa-pencil"></i> Edit</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fa fa-trash"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<!-- Add New Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 text-bold text-primary" id="exampleModalLabel">Add New Teacher</h1>
        <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.teachers.register') }}" method="post">
      @csrf
      <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Fullname</label>
                        <input type="text" class="form-control" name="name" required placeholder="Enter Teacher Fullname">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Email Address</label>
                        <input type="email" class="form-control" name="email" required placeholder="Enter Teacher Email Address">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Phone No.</label>
                        <input type="phone" class="form-control" name="phone" required placeholder="Enter Teacher Phone No.">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" required placeholder="Enter Teacher Date of Birth">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Address</label>
                        <input type="text" class="form-control" name="address" required placeholder="Enter Teacher Address">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">State</label>
                        <input type="text" class="form-control" name="state" required placeholder="Enter Teacher State">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Country</label>
                        <input type="text" class="form-control" name="country" required placeholder="Enter Teacher Country">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Password</label>
                        <input type="password" class="form-control" name="password" required placeholder="Enter Teacher Password">
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-check"></i> Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Add New Teacher -->


@endsection