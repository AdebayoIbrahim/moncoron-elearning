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
                <div class="card-body p-3">
                    <div class="row my-bold-row">
                        <div class="col-sm-4 col-lg-3">
                            <div class="card mb-4 text-white bg-success">
                                <div class="card-body d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="my-bold-num fw-semibold">
                                    {{ count($courses) }}
                                    <span class="fa fa-book" style="margin-left: 90px;"></span>
                                    </div>
                                    <div class="fw-semibold my-bold-num-text">All Courses</div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-sm-4 col-lg-3">
                            <div class="card mb-4 text-white bg-primary">
                                <div class="card-body d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="my-bold-num fw-semibold">
                                    {{ count($dawahs) }}
                                    <span class="fa fa-book" style="margin-left: 90px;"></span>
                                    </div>
                                    <div class="fw-semibold my-bold-num-text">All Dawah</div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-sm-4 col-lg-3">
                            <div class="card mb-4 text-white bg-warning">
                                <div class="card-body d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="my-bold-num fw-semibold">
                                    {{ count($studentusers) }}
                                    <span class="fa fa-users" style="margin-left: 90px;"></span>
                                    </div>
                                    <div class="fw-semibold my-bold-num-text">All Students</div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-sm-4 col-lg-3">
                            <div class="card mb-4 text-white bg-info">
                                <div class="card-body d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="my-bold-num fw-semibold">
                                    {{ count($teacherusers) }}
                                    <span class="fa fa-book" style="margin-left: 90px;"></span>
                                    </div>
                                    <div class="fw-semibold my-bold-num-text">All Teacher</div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-sm-4 col-lg-3">
                            <div class="card mb-4 text-white bg-success">
                                <div class="card-body d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="my-bold-num fw-semibold">
                                    {{ count($lecturerusers) }}
                                    <span class="fa fa-book" style="margin-left: 90px;"></span>
                                    </div>
                                    <div class="fw-semibold my-bold-num-text">All Lecturer</div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->                
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>



@endsection