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

    @php
    $totalquest = count($Questions['questions'])
    @endphp

    <div class="cbt-area">
        <div class="action_cbt_buttons">
            {{-- submit-btn- --}}
            <button type="button" class="btn btn-danger btn-md" id="submit_cbt" style="width: 150px;">Submit</button>
            {{-- assessment-timer --}}
            {{$Questions['general_time_limit']}}
        </div>
        @foreach($Questions['questions'] as $question)
        @php
        // get-unique-label
        $uniquelabel = 'Question'. $loop->index + 1

        @endphp
        <div class="area-question-data questions-{{$loop->index + 1}}" id="question_each">
            {{-- div.count_questions --}}
            <h4 class="question_current_index" style="padding-block: 1rem" id="{{$loop->index + 1}}">Question {{$loop->index + 1}} out of {{$totalquest}}</h4>
            <div style="display: flex;gap:5px;align-items:center">
                @if(!empty($question['question_text']))
                <h5 class="mb-2">{{$loop->index + 1}}.</h5>
                <h5>{{$question['question_text']}}</h5>
                @endif
            </div>
            {{-- Todo  render-empty if no -media is present--}}
            @if(isset($question['media']))
            <div style="display: flex;gap: 5px;align-items:center;margin-left:3rem">
                <!-- Media -->
                @if(!empty($question['media']['image_path']))
                <img src="{{ asset('storage/' . $question['media']['image_path']) }}" alt="Question Image" class="pop_upload_file">
                @endif
                @if(!empty($question['media']['audio_path']))
                <audio controls style="height: 50px" src="{{ asset('storage/' . $question['media']['audio_path']) }}">
                </audio>
                @endif
                @if(!empty($question['media']['video_path']))
                <video controls class="pop_upload_file" src="{{ asset('storage/' . $question['media']['video_path']) }}">
                </video>
                @endif
            </div>
            @endif
            {{-- options-choose --}}
            <div class="options_choose" style="margin-left: 1.2rem">
                <div class="form-group mt-4 options_group">
                    @php
                    // Define alphabetical labels
                    $alphabet = ['A', 'B', 'C', 'D', 'E'];
                    @endphp
                    @foreach($question['options'] as $index => $option)
                    <div class="options_q{{$loop->index}} option_cbt">
                        <h6 class="mb-2">{{$alphabet[$index]}}.</h6>

                        <div class="options_ans{{$loop->index}} op_ans_style">
                            <input type="radio" name={{$uniquelabel}} id="{{$uniquelabel .' '.$loop->index}}" data-id={{$loop->index + 1}}>
                            <label for="{{$uniquelabel .' '.$loop->index}}">
                                @if(!empty($option['option_text']))
                                {{ $option['option_text'] }}
                                @endif
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
        <div id=" progress-tracker">
            {{-- track-progress-area --}}
            <section class="btn-actions-cbt">
                <button type="button" class="btn btn-info btn-md" id="prev_cbt">Previous</button>
                <button type="button" class="btn btn-success btn-md " id="next_cbt">Next</button>
            </section>
            <section class="highlight_questions_track">
                <div style="display: flex; flex-wrap: wrap;">
                    @for($i = 0; $i < 60; $i++) <div style="border: 1px solid #ddd; padding: 5px; width: 40px; text-align: center; margin: 2px; font-weight: 500;cursor:pointer" id="box_navigate_cbt">
                        {{$i + 1}}
                </div>
                @endfor
            </section>
        </div>
    </div>




    {{-- <div id="loadingAnimation" class="loading-frame">
    <h2>Loading Assessment..</h2>
    <div class="h6">Hang Tight! Your Test is about to begin!</div>
    <div>
        <img src=" {{asset ('images/Loader.gif') }}" alt="stream-loading" style="width: 110px; height: 110px;">
</div>
</div> --}}

<div class="modal modal-md fad" tabindex="-1" id="modal_result">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-bold text-primary" id="Editor_modal">
                    Test Result</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container_modal_body">
                    <div style="text-align: center; padding-botton: 10px" class=" d-flex justify-content-center">
                        <dotlottie-player src="https://lottie.host/69a64540-0934-4244-8840-29b3bc08d921/a95uBnXlyg.json" background="transparent" speed="1" style="width: 150px; height: 150px;" autoplay></dotlottie-player>
                    </div>
                    <h5 style="text-align: center">Congratulations! You passed the assessment with a score of <b><span style="color: blue"> 80%</span></b></h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Retake Assessment</button>
            </div>
        </div>
    </div>
</div>
@endsection
