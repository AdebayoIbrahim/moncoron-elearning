@extends('layouts.app')
@section('content')
@include('partials.header')
@vite(['resources/css/assessment-take/index.css','resources/js/assessment-take/index.js'])


<link href="//cdn.muicss.com/mui-0.10.3/css/mui.min.css" rel="stylesheet" type="text/css" />
<script src="//cdn.muicss.com/mui-0.10.3/js/mui.min.js"></script>
{{-- confettti-scripts --}}
<script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
<div class="container">
    <section class="hero_certificate_header">
        <div class="text-certificate-area">
            <h3 style="text-align: center">Congratulations, Adam! 🎉</h3>
            <div aria-label="warm-text" style="font-size: 1.3rem;text-align:center">You’ve successfully completed the <span style="font-weight: bold;font-style:italic">Java For Beginner</span> course, and your hard work has paid off!</div>
        </div>
        <div style="font-size:1.12rem;text-align: center;" aria-label="congratsText">
            "We’re proud of your dedication! This certificate reflects the knowledge and skills you’ve earned. Keep striving for more, and let this be a reminder of your achievement. Download your certificate below!"
        </div>
    </section>
    {{-- certification-parent-container --}}
    <section class="certificate_view">
        <div class="actual_certificate_container" aria-disabled="true" contenteditable="false">
            {{-- text-area --}}
            <h1 style="text-align: center;color: rgba(0, 0, 0, 1);">COURSE CERTIFICATE</h1>
            <h1 style="color: rgba(140, 140, 140, 1);
            text-align: center">CERTIFICATE OF COMPLETION</h1>
            <div style="padding-top: 3rem;text-align:center">
                <h3 style="text-align: center; color: rgba(0, 0, 0, 1);font-size:1.8rem">AWARDED TO</h3>
                <h2 style="font-size: 3rem;color: rgba(84, 65, 195, 1);padding-top: 2rem;font-weight: bold">Fatima Ojo</h2>
            </div>
        </div>
    </section>
    <section class="action_buttons_certificate">
        <div>
            <button class="mui-btn mui-btn--raised mui-btn--primary">Download</button>
        </div>
    </section>

</div>





@endsection
{{--
Congratulations, {{ $user->name }}! 🎉
You’ve successfully completed the {{ $course->title }} course, and your hard work has paid off!

We are so proud of your dedication, and this certificate is a testament to the knowledge and skills you’ve gained. Keep reaching for new heights, and continue your learning journey with confidence!

Your journey doesn’t end here — it’s just the beginning. Take pride in your achievement and feel free to download your certificate below as a reminder of this milestone. --}}
