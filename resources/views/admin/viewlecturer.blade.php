@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
@vite(['resources/css/Main/main.css','resources/css/Main/main.js'])

<div class="container-fluid">
    <div class="row">
        {{-- loader-animation --}}
        <div class="loader_spinner_lecturer" style="text-align: center">
            <div class="spinner-border text-secondary spinner_size" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div id="loader_lecturer" class="pt-4">
                <h5>Fetching Details...</h5>
            </div>
        </div>


    </div>
</div>
{{-- absolute-audio-player --}}
<div class="absolute_player_audio">
    <div class="close_audio"><i class="fa fa-times" aria-hidden="true" style="color: #d6f2ff;font-size:1.5rem"></i></div>
    <audio src={{asset ('images/horse.mp3')}} crossorigin playsinline controls>
    </audio>
</div>
@endsection
