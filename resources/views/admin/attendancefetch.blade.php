@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
@vite(['resources/css/assessment-take/index.css'])
<div class="container-fluid">
    <div class="row">
        @include('partials.admin_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <h4 style="text-align: center">Attendance List For Lesson 3 </h4>
            <div class="attendace_area">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">S/N</th>
                            <th scope="col">Name</th>
                            <th scope="col">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Mark</td>
                            <td>Otto</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Kelvin</td>
                            <td>Otto</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <section class="action_buttons_certificate">
                <div>
                    <button class="btn btn-primary md" id="certificate_download">Download</button>
                </div>
            </section>
        </main>
    </div>
