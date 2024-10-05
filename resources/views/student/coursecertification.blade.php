@extends('layouts.app')
@section('content')
@include('partials.header')
@vite(['resources/css/assessment-take/index.css','resources/js/assessment-take/index.js'])
{{-- confettti-scripts --}}
<script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>

<div class="container mt-3">
    <section class="hero_certificate_header">
        <div class="text-certificate-area">

        </div>
    </section>
    {{-- certification-parent-container --}}
    <section class="certificate_view">
        <div class="actual_certificate_container" aria-disabled="true">

        </div>
    </section>
    <section class="action_buttons">

    </section>

</div>





@endsection
{{--
Congratulations, {{ $user->name }}! 🎉
You’ve successfully completed the {{ $course->title }} course, and your hard work has paid off!

We are so proud of your dedication, and this certificate is a testament to the knowledge and skills you’ve gained. Keep reaching for new heights, and continue your learning journey with confidence!

Your journey doesn’t end here — it’s just the beginning. Take pride in your achievement and feel free to download your certificate below as a reminder of this milestone. --}}
