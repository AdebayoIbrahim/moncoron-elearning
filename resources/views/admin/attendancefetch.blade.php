@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
@vite(['resources/css/assessment-take/index.css'])
<div class="container-fluid">
    <div class="row">
        @include('partials.admin_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <h4 style="text-align: center">Attendance List For Lesson 3 </h4>
            @if($attendance->isEmpty())
            <h5 style="padding-block: 2rem; text-align: center;">OOPs!! No attendance Yet</h5>
            @else
            <div class="attendace_area">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">S/N</th>
                            <th scope="col">Name</th>
                            <th scope="col">Date | Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendance as $participate)

                        <tr>
                            <td>{{$loop->index + 1}}</td>
                            <td>{{$participate -> name}}</td>
                            @php
                            $formattedDate = $participate->created_at->format('d/m/Y h:i A');
                            @endphp
                            <td>{{$formattedDate}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            @endif
            @if(!$attendance->isEmpty())
            <section class="action_buttons_certificate">
                <div>
                    <button class="btn btn-primary md" id="certificate_download">Download</button>
                </div>
            </section>
            @endif
        </main>
    </div>
