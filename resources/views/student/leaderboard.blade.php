@extends('layouts.app')
@section('content')
@include('partials.header')
@vite(['resources/css/app.css','resources/js/courseview/leaderboard.js'])
<div class="container-fluid">
    <div class="row">
        @include('partials.student_sidebar')

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
                {{-- leadboad-autofill-from-API --}}
                <div class="leaderboard_section_lists">
                    @for($i = 0; $i < 10; $i++) <div class="leaderboard_users">
                        <div style="display: flex;align-items:center;gap:3rem">
                            <div style="width: 18px;font-weight:600;">{{$i + 1}}</div>
                            <i class="fa-solid fa-user" style="font-size: 1.2rem"></i>
                            <div class="user-name_leaderboard">
                                Fatimo Ojo
                            </div>
                        </div>

                        <div class="points_user">
                            +40MCP
                        </div>
                </div>
                @endfor
            </div>
    </div>
    </main>


</div>
</div>
@endsection
