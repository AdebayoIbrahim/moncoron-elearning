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
                    <h2 class="p-3">
                        Welcome back, {{$user->name}}
                    </h2>
                    <p class="p-3">Pick up from where you left by <a class="no-decor" href="{{ route('student.courses')}}"> registering a new Course or Continue with your registered Courses</a></p>
                </div>
            </div>

        </main>
    </div>
</div>




@endsection