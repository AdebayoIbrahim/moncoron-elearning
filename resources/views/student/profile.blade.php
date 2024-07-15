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
                <div class="card-body p-3">
                    <h2 class="p-3 text-bold text-primary">
                        My Profile <button class="btn btn-primary pull-right" id="updateRecord"><i class="fa fa-pencil"></i> Edit</button>
                    </h2>
                    <div class="row p-3">
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label class="form-label text-bold">Fullname:</label> <span>{{$user->name}}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label class="form-label text-bold">Email Address:</label> <span>{{$user->email}}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label class="form-label text-bold">Phone No.:</label> <span>{{$user->phone}}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label class="form-label text-bold">Date of Birth:</label> <span>{{$user->dob}}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label class="form-label text-bold">Address:</label> <span>{{$user->address}}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label class="form-label text-bold">State:</label> <span>{{$user->state}}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label class="form-label text-bold">Country:</label> <span>{{$user->country}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
<!-- Create Post Modal -->
<div class="modal fade" id="UpdateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 text-bold text-primary" id="exampleModalLabel">Update Profile</h1>
        <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/profile-update" method="post">
      @csrf
      <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Fullname</label>
                        <input class="form-control" name="fullname" value="{{$user->name}}" required placeholder="Enter your Fullname">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Email Address</label>
                        <input class="form-control" name="email" value="{{$user->email}}" required placeholder="Enter your Email Address">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Phone No.</label>
                        <input class="form-control" name="phone" value="{{$user->phone}}" required placeholder="Enter your Phone No.">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Date of Birth</label>
                        <input class="form-control" name="dob" value="{{$user->dob}}" required placeholder="Enter your Date of Birth">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Address</label>
                        <input class="form-control" name="address" value="{{$user->address}}" required placeholder="Enter your Address">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">State</label>
                        <input class="form-control" name="state" value="{{$user->state}}" required placeholder="Enter your State">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="label text-bold">Country</label>
                        <input class="form-control" name="country" value="{{$user->country}}" required placeholder="Enter your Country">
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-check"></i> Update</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- End Modal Create Post -->



@endsection