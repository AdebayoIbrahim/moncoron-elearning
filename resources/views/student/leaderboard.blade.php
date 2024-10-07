@extends('layouts.app')
@section('content')
@include('partials.header')
@vite(['resources/css/app.css','resources/js/courseview/leaderboard.js'])
<div class="container-fluid">
    <div class="row">
        @include('partials.student_sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <div class="container_leaderboard">
                <div class="leaderboard_switcher">
                    <div class="d-flex" style="gap: 3rem;">
                        <div class="switcher_toggle current">Local</div>
                        <div class="switcher_toggle">Global</div>
                    </div>
                </div>
                {{-- leadboad-autofill-from-API --}}
                <div class="leaderboard_section_lists"></div>
            </div>
        </main>


    </div>
</div>
@endsection
