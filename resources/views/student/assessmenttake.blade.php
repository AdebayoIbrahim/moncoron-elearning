@extends('layouts.app')
@section('content')
@include('partials.header')
@vite(['resources/css/assessment-take/index.css','resources/js/assessment-take/index.js'])

<div class="container mt-5">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div>Hold on</div>

</div>


<div id="loadingAnimation" class="loading-frame">
    <h2>Loading Assessment..</h2>
    <div class="h6">Hang Tight! Your Test is about to begin!</div>
    <div>
        <img src=" {{asset ('images/Loader.gif') }}" alt="stream-loading" style="width: 110px; height: 110px;">
    </div>
</div>
@endsection
