@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Notifications</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach (Auth::user()->notifications as $notification)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $notification->data['message'] }}
                                <a href="{{ url($notification->data['url']) }}" class="btn btn-primary btn-sm">View</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
