@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
@vite(['resources/css/app.css','resources/js/courseview/leaderboard.js'])
<div class="container-fluid">
    <div class="row">
        @include('partials.admin_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4">
            <div class="container_leaderboard">
                <div class="d-flex gap-3 align-items-center">
                    <img src={{asset('images/Moncoronlogo.png')}} alt="sender_avatar" width="40" height="40">
                    <h4 class="mt-1">Leader Board</h4>
                </div>
                <div class="leaderboard_switcher">
                    <div class="d-flex" style="gap: 3rem;">
                        <div class="switcher_toggle current">Local</div>
                        <div class="switcher_toggle">Global</div>
                    </div>
                </div>

                {{-- loader-animation --}}
                <div class="loader_spinner">
                    <div class="spinner-border text-secondary spinner_size" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div id="empty_text">
                        <h5>No Scoreboard Data Yet!</h5>
                    </div>
                </div>
                {{-- leadboad-autofill-from-API --}}
                <div class="leaderboard_section_lists" id="scoreboards">

                </div>
            </div>
        </main>
    </div>
</div>

@endsection
