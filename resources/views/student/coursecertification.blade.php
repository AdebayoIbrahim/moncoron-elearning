@extends('layouts.app')
@section('content')
@include('partials.header')
@vite(['resources/css/assessment-take/index.css','resources/js/create-assessment/certify.js'])
{{-- confettti-scripts --}}
<script src="https://cdn.jsdelivr.net/npm/@tsparticles/confetti@3.0.3/tsparticles.confetti.bundle.min.js"></script>
<div class="container">
    <section class="hero_certificate_header">
        <div class="text-certificate-area">
            <h3 style="text-align: center">Congratulations, Adam! 🎉</h3>
            <div aria-label="warm-text" style="font-size: 1.3rem;text-align:center">You’ve successfully completed the <span style="font-weight: bold;font-style:italic">Java For Beginner</span> course, and your hard work has paid off!</div>
        </div>
        <div style="font-size:1.12rem;text-align: center;" aria-label="congratsText">
            We’re proud of your dedication! This certificate reflects the knowledge and skills you’ve earned. Keep striving for more, and let this be a reminder of your achievement. Download your certificate below!
        </div>
    </section>
    {{-- certification-parent-container --}}

    <section class="certificate_view">
        <div class="actual_certificate_container" aria-disabled="true" contenteditable="false">
            {{-- text-area --}}
            <h1 style="text-align: center;color: rgba(0, 0, 0, 1);">COURSE CERTIFICATE</h1>
            <h1 style="color: rgba(140, 140, 140, 1);
                text-align: center">CERTIFICATE OF COMPLETION</h1>
            <div style="padding-top: 1.5rem;text-align:center">
                <h3 style="text-align: center; color: rgba(0, 0, 0, 1);font-size:1.6rem">AWARDED TO</h3>
                <h2 style="font-size: 3rem;color: rgba(84, 65, 195, 1);padding-top: 0.6rem;font-weight: bold">{{certificate_name ?? 'Null'}}</h2>
            </div>
            <div class="absolute_top_image">
                <img src="{{asset ('images/Moncoron_cer.png')}}" alt="alternate_img_" srcset="">
            </div>
            {{-- for-success-pointer --}}
            <div style="padding-top: 1.4rem">
                <h3 style="text-align: center; color: rgba(0, 0, 0, 1);font-size:1.6rem">For successful completion of the course</h3>
            </div>
            {{-- for-coursename --}}
            <div style="padding-top: 1.4rem">
                <h3 style="text-align: center; color: rgba(0, 0, 0, 1);font-size:3.0rem">{{coursename ?? "Null"}}</h3>
            </div>
            {{-- signature --}}
            <div class="signature_signature" style="text-align: right">
                <img src={{asset ('images/Signature.png')}} alt="image_sign" style="width:150px;height:60px;" />
                <div style="width: 100%;text-align: right;display:flex;justify-content:flex-end">
                    <div style="background: black;width:209px;height:1px;transform:translateX(25px)"></div>
                </div>
                {{-- founder --}}
                <div style="text-align: right; color: rgba(0, 0, 0, 1);font-size:1.5rem;padding-top: 4px;">Founder</div>
                {{-- {{founders_name}} --}}
                <div style="text-align: right; color: rgba(0, 0, 0, 1);font-size:1.5rem;font-weight:600">Ayub Sherif</div>
            </div>
            {{--date---}}
            <div class="d-flex justify-content-start" style="padding-left: 1.4rem;font-weight:400;font-size:18px;flex-direction:column">
                {{-- dat-of-access --}}
                <div>{{certificate_date ?? 'Null'}}</div>
                <div><span style="font-weight: 600">To Verify</span>: {{certificate_ref ?? 'Null'}}</div>
            </div>
        </div>
    </section>

    <section class="action_buttons_certificate">
        <div>
            <button class="btn btn-primary md" id="certificate_download">Download</button>
        </div>
    </section>

</div>
@endsection
