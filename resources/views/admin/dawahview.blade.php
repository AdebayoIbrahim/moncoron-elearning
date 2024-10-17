@extends('layouts.adminapp')
@section('content')
@include('partials.admin_header')
@vite(['resources/css/Main/main.css','resources/css/Main/main.js'])
<div class="container-fluid">
    <div class="row">
        @include('partials.admin_sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-4  dawah_overlay_container">
            {{-- banner-shoews --}}
            <div class="overlaydawah_banner">
                {{-- <img src="{{asset('images/Dawah.png')}}" alt="dawah-banner" style="width: 100%; height:100%;object-fit:cover"> --}}
                <div class="d-flex gap-3 align-items-center">
                    <img src={{asset('images/Moncoronlogo.png')}} alt="sender_avatar" width="40" height="40">
                    <h4 class="mt-1">Dawah</h4>
                </div>
                <div class=" d-flex justify-content-betwen align-items-center mt-4 pt-2">
                    <h5>Inspire Your Soul with varieties Of Islamic Lectures from top notch islamic scholars.</h5>
                </div>
                <div class="pt-2 d-flex justify-content-end">
                    <button class="btn btn-primary md">
                        <i class="fas fa-plus"></i>
                        Add a Lecturer
                    </button>
                </div>
            </div>
            {{-- lecturers-list-of-lecturers --}}
            <div class="lecturers_list_admin">
                {{-- <div class="d-flex justify-content-center align-items-center">
                    <h5>No lecturer Yet!</h5>
                </div> --}}
                <div class="d-flex justify-content-between align-items-center" style="width: 100%">
                    <h5>Lecturers</h5>
                    <button class="btn btn-primary md">
                        <i class="fa fa-upload" aria-hidden="true"></i>
                        Upload
                    </button>
                </div>
                <section class="pt-3 " style="text-align: center">
                    {{-- <div class="spinner-border text-secondary spinner_dawah" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div> --}}

                    {{-- lecturer-details --}}
                    <div class="flex_lecturer_gtoup">
                        @for($i = 0; $i < 5; $i++) <div class=" d-flex justify-content-between lecturer_overlay" id="dahee_select">

                            <div class="d-flex" style="align-items: center;gap: 1.3rem">
                                {{-- div-image --}}
                                <img src={{asset ('/images/Qari.jpeg')}} alt="dahee_image" style="width: 70px;height: 70px; border-radius: 50%;object-fit:cover;">
                                <div class="d-flex" style="flex-direction: column; gap: 0rem; align-items: center;margin-top:25px">
                                    <div style="font-weight: 500;font-size: 1rem">Mufti Menk</div>
                                    <p style="font-weight: 500;font-size: 0.9rem;color: #9d9d9d">Joined 2016</p>
                                </div>
                            </div>

                            <div style="cursor: pointer"><i class="fa-solid fa-ellipsis-vertical" style="font-size: 1.2rem"></i></div>
                    </div>
                    @endfor
            </div>
            </section>
    </div>

</div>


</div>
</main>
</div>
</div>
@endsection
