@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
    <style>

        :root {
            --fontPrimary: "El Messiri", sans-serif;
            --fontSecondary: "STIX Two Text", serif;
            --cTPrimary: #f7f8fc;
            --cTPrimary-dark: #e1e5f4;
            --cTSecondary: rgba(98, 74, 255, 1);
            --cPrimary: #161616;
            --cPrimary-light: #151515;
            --cSecondary: linear-gradient(
                    90deg,
                    rgba(98, 74, 255, 1) 0%,
                    rgba(193, 46, 251, 1) 100%
            );
            --shadow: rgb(38, 57, 77) 0px 20px 30px -13px,
            inset 10px 10px 30px -15px rgba(0, 0, 0, 0.13);
            --shadowP: rgb(38, 57, 77) 0px 20px 30px -13px;
            --shadowS: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px,
            rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
            --fontBig: 1.6rem;
            --fontMedium: 1.3rem;
            --fontSmall: 1rem;
            --fontMini: 0.7rem;
            --paddingMini: 5px;
            --paddingSmall: 10px;
            --paddingMedium: 20px;
            --paddingBig: 30px;
        }

        .player-box {
            position: relative;
            background: url("https://images.pexels.com/photos/1509534/pexels-photo-1509534.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260") no-repeat center center;
            background-size: cover;
            text-transform: capitalize;
            overflow-x: hidden;
            display: grid;
            align-items: center;
            justify-content: center;
            min-height: 600px;
            max-height: 700px;
            color: var(--cPrimary);
            scroll-behavior: smooth;
        }

        /* public styles */
        .sbtn {
            position: relative;
            background-color: var(--cTPrimary);
            width: 64px;
            height: 64px;
            padding: var(--paddingMini);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-around;
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
            overflow: hidden;
        }

        .sbtn::before {
            content: "";
            background-image: var(--cSecondary);
            border-radius: 20%;
            display: block;
            height: 100%;
            width: 100%;
            position: absolute;
            left: 0;
            top: 0;
            transform: translate(-100%, 0) rotate(10deg);
            transform-origin: top left;
            transition: 0.2s transform ease-out;
            will-change: transform;
            z-index: -1;
        }

        .sbtn:hover::before {
            transform: translate(0, 0);
        }

        .sbtn:hover {
            border: 3px solid transparent;
            transform: scale(1.1);
            will-change: transform;
        }

        .sbtn:hover svg {
            fill: var(--cTPrimary);
        }

        .text-gradient {
            background-color: rgb(193, 46, 251);
            background-image: var(--cSecondary);
            background-size: 100%;
            background-repeat: repeat;
            /* chrome - IE */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            /* firefox */
            -moz-background-clip: text;
            -moz-text-fill-color: transparent;
        }

        .opacity50 {
            opacity: 0.5;
        }

        .nodisplay {
            display: none;
        }

        /* private styles */
        .glass-container {
            width: 500px;
            height: 800px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50px;
            border: 1px solid var(--cTPrimary);
            box-shadow: var(--shadow);
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
        }

        .MP3container {
            position: relative;
            width: 90%;
            height: 95%;
            border-radius: 50px;
            background-color: var(--cTPrimary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-direction: column;
            padding: var(--paddingSmall);
            overflow: hidden;
        }

        .MP3container .header-wrapper {
            width: 100%;
            height: 10%;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .MP3container .header-wrapper .sbtn {
            transform: scale(0.8);
        }

        .MP3container .songState {
            padding: var(--paddingMedium);
            background-color: var(--cPrimary);
            color: var(--cTPrimary);
            border-radius: 20px;
            width: 33.3333%;
            height: 70%;
            text-align: center;
            display: grid;
            align-content: center;
            font-size: var(--fontMini);
            box-shadow: var(--shadowS);
        }

        .MP3container .img-container {
            position: relative;
            border-radius: 50%;
            width: 230px;
            height: 230px;
            background-color: var(--cTPrimary-dark);
            margin: 0px 0;
            box-shadow: var(--shadow);
            transition: all 0.3s ease-in-out;
        }

        .MP3container .img-container img {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            border-radius: 50%;
            transition: all 0.3s ease-in-out;
        }

        .MP3container .song-info {
            width: 75%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            margin: 50px 0 0 0;
        }

        .MP3container .song-info .title {
            width: 100%;
            font-weight: bold;
            font-size: var(--fontMedium);
            padding-bottom: var(--paddingSmall);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .MP3container .song-info .author {
            width: 90%;
            font-weight: 300;
            opacity: 0.5;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .MP3container .range-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-around;
            width: 100%;
            margin: 50px 0;
            min-height: 50px;
        }

        .MP3container .range-wrapper input {
            width: 70%;
            height: 75px;
            -webkit-appearance: none; /* Override default CSS styles */
            appearance: none;
            background: var(--cPrimary); /* Grey background */
            outline: none; /* Remove outline */
            -webkit-transition: 0.2s; /* 0.2 seconds transition on hover */
            transition: all 0.2s;
            border-radius: 50px;
            cursor: pointer; /* Cursor on hover */
        }

        .MP3container .range-wrapper input::-webkit-slider-thumb {
            -webkit-appearance: none; /* Override default look */
            appearance: none;
            width: 0px; /* Set a specific slider handle width */
            height: 0px; /* Slider handle height */
            background: var(--cTSecondary); /* Green background */
            cursor: pointer; /* Cursor on hover */
        }

        .MP3container .range-wrapper input::-moz-range-thumb {
            width: 3px; /* Set a specific slider handle width */
            height: 10px; /* Slider handle height */
            background: var(--cTSecondary); /* Green background */
            cursor: pointer; /* Cursor on hover */
        }

        .MP3container .range-wrapper #waveform {
            width: 70%;
            cursor: pointer;
        }

        .MP3container .range-wrapper span {
            font-weight: bold;
        }

        .MP3container .controller {
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 80%;
            margin-bottom: 20px;
        }

        .MP3container .controller .sbtn {
            border-radius: 50%;
            transform: scale(1);
        }

        .MP3container .controller .mbtn {
            border-radius: 50%;
            background-image: var(--cSecondary);
            transform: scale(1.2);
        }

        .MP3container .controller .sbtn:nth-child(2):before {
            transform: scale(0);
            display: none;
        }

        .MP3container .controller .sbtn:nth-child(2):hover {
            transform: scale(1.3);
            border: none;
        }

        .MP3container .controller .mbtn svg {
            fill: var(--cTPrimary);
            transition: transform 0.3s ease-in-out;
        }

        .controller .mbtn #pauseSVG {
            display: none;
        }

        .MP3container .footer-wrapper {
            background-color: var(--cPrimary);
            width: 90%;
            height: 10%;
            border-radius: 25px;
            margin: 5px 0;
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            color: var(--cTPrimary);
        }

        .MP3container .footer-wrapper div {
            cursor: pointer;
        }

        .MP3container .footer-wrapper div svg {
            fill: var(--cTPrimary);
        }

        .MP3container .footer-wrapper div #heart-full {
            display: none;
        }

        .MP3container .footer-wrapper div {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            padding: var(--paddingSmall);
            transition: transform 0.3s ease-in-out, background-image 0.3s ease-in-out;
        }

        .MP3container .footer-wrapper div:hover {
            transform: scale(1.1);
            background-image: var(--cSecondary);
            animation: scaleUP 0.3s ease-in-out alternate;
        }

        .MP3container .side-menu {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translate(0, -50%);
            width: 0%;
            height: 73%;
            background-color: var(--cPrimary);
            z-index: 5;
            border-radius: 0 30px 30px 0;
            transition: width 0.3s ease-in-out;
            box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px;
        }

        .MP3container .side-menu-opener {
            font-size: var(--fontMini);
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 50%;
            right: -75px;
            transform: translate(0, -50%) rotate(90deg);
            width: 110px;
            height: 40px;
            background-color: var(--cPrimary);
            color: var(--cTPrimary);
            border-radius: 30px 30px 0 0;
            cursor: pointer;
        }

        .MP3container .right-side-menu {
            position: absolute;
            top: 50%;
            right: 0;
            transform: translate(0, -50%);
            background-image: var(--cSecondary);
            width: 0%;
            height: 70%;
            border-radius: 30px 0 0 30px;
            transition: width 0.3s ease-in-out;
            z-index: 5;
            box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px;
        }

        .MP3container .right-side-opener {
            font-size: var(--fontMini);
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 50%;
            left: -75px;
            transform: translate(0, -50%) rotate(90deg) scale(-1);
            width: 110px;
            height: 40px;
            background-image: var(--cSecondary);
            color: var(--cTPrimary);
            border-radius: 30px 30px 0 0;
            cursor: pointer;
        }

        .infinity-loop {
            animation: loop 2s infinite ease-in-out;
            color: var(--colorSecondary);
        }

        @keyframes scaleUP {
            from {
                transform: scale(0.8);
            }
            to {
                transform: scale(1.1);
            }
        }

        @keyframes animateHeart {
            0% {
                transform: rotate(0deg);
            }
            10% {
                transform: rotate(35deg);
            }
            100% {
                transform: rotate(0deg);
            }
        }

        @keyframes loop {
            from {
                transform: rotate(0);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @media screen and (max-width: 525px) {
            .glass-container {
                width: 100vw;
                height: 100vh;
                border-radius: 0;
            }

            .MP3container {
                position: relative;
                width: 100%;
                height: 100%;
                border-radius: 0;
                padding: var(--paddingSmall);
                overflow: hidden;
            }

            .controller {
                justify-content: space-between;
            }
        }

        @media screen and (max-width: 425px) {
            .glass-container {
                width: 100%;
                height: 100%;
            }
            .MP3container {
                position: relative;
                width: 100%;
                height: 100%;
                border-radius: 0;
                padding: var(--paddingSmall);
                overflow: hidden;
            }
        }

    </style>
@endsection
@section('content')
    <section class="page-section account-page">
        <div class="uk-container uk-containcer-center uk-margin-large-top uk-margin-large-bottom">
            <div class="uk-grid" uk-grid>
                @include('site.users.partials.menu')
                <div class="uk-width-3-4@m">
                    <div class="account-orders">
                        <div class="uk-alert uk-alert-danger">
                            لطفا تا بارگزاری فایل ها شکیبا باشید
                        </div>
                        <div class="row">
                            @if(isset($voice) && !empty($voice))
                                <div class="col-md-12 col-12 mt-3">
                                    @php
                                        $key = array_search($voice->id,$voice->videoable->video->pluck('id')->toArray());
                                    @endphp
                                    <div class="player-box">
                                        <div class="glass-container">
                                            <div class="nodisplay">
                                                <div class="imgs">
                                                    <img class="img"
                                                         src="https://images.pexels.com/photos/5412/water-blue-ocean.jpg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                                                         alt="">
                                                    <img class="img"
                                                         src="https://images.pexels.com/photos/175773/pexels-photo-175773.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                                                         alt="">
                                                    <img class="img"
                                                         src="https://images.pexels.com/photos/1546249/pexels-photo-1546249.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                                                         alt="">
                                                    <img class="img"
                                                         src="https://images.pexels.com/photos/1591252/pexels-photo-1591252.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                                                         alt="">
                                                    <img class="img"
                                                         src="https://images.pexels.com/photos/287229/pexels-photo-287229.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
                                                         alt="">
                                                    <img class="img"
                                                         src="https://images.pexels.com/photos/53135/hydrangea-blossom-bloom-flower-53135.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                                                         alt="">
                                                    <img class="img"
                                                         src="https://images.pexels.com/photos/73873/star-clusters-rosette-nebula-star-galaxies-73873.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                                                         alt="">
                                                    <img class="img"
                                                         src="https://images.pexels.com/photos/1819650/pexels-photo-1819650.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                                                         alt="">
                                                    <img class="img"
                                                         src="https://images.pexels.com/photos/3029545/pexels-photo-3029545.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                                                         alt="">
                                                    <img class="img"
                                                         src="https://images.pexels.com/photos/114979/pexels-photo-114979.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                                                         alt="">
                                                </div>
                                            </div>


                                            <div class="MP3container">
                                                <div id="lyrics-menu" class="side-menu">
                                                    <div id="lyrics-opener" class="side-menu-opener"
                                                         title="Song's lyrics.">
                                                        lyrics
                                                    </div>
                                                </div>
                                                <div id="songs-menu" class="right-side-menu">
                                                    <div id="songs-opener" class="right-side-opener"
                                                         title="Songs List.">
                                                        songs
                                                    </div>
                                                </div>
                                                <div class="header-wrapper">
                                                    <div class="sbtn">
                                                        <svg width="20px" height="20px"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 486.975 486.975">
                                                            <path d="M473.475,230.025h-427.4l116-116c5.3-5.3,5.3-13.8,0-19.1c-5.3-5.3-13.8-5.3-19.1,0l-139,139c-5.3,5.3-5.3,13.8,0,19.1
		l139,139c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1l-116-116h427.5c7.5,0,13.5-6,13.5-13.5
		S480.975,230.025,473.475,230.025z"/>
                                                        </svg>
                                                    </div>
                                                    <div id="songState" class="songState">
                                                        kaci.ir
                                                    </div>
                                                    <div class="sbtn">
                                                        <svg width="20px" height="20px"
                                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 150">
                                                            <path id="XMLID_241_"
                                                                  d="M15,30h120c8.284,0,15-6.716,15-15s-6.716-15-15-15H15C6.716,0,0,6.716,0,15S6.716,30,15,30z"/>
                                                            <path id="XMLID_242_"
                                                                  d="M135,60H15C6.716,60,0,66.716,0,75s6.716,15,15,15h120c8.284,0,15-6.716,15-15S143.284,60,135,60z"/>
                                                            <path id="XMLID_243_"
                                                                  d="M135,120H15c-8.284,0-15,6.716-15,15s6.716,15,15,15h120c8.284,0,15-6.716,15-15S143.284,120,135,120z"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="img-container">
                                                    @if(isset($voice->videoable->image[0]) && !empty($voice->videoable->image[0]))
                                                        <img id="thumbnail" src="{{ $voice->videoable->image[0]->url }}"
                                                             alt="">
                                                    @endif
                                                </div>
                                                <div class="song-info">
{{--                                                    <div id="songTitle" class="title">Loading Song...</div>--}}
                                                    <div id="songAuthor"
                                                         title="check your connection if it's took so lonk."
                                                         class="author">
                                                        {{ $voice->videoable->title }} - پارت {{ $key }}
                                                    </div>
                                                </div>
                                                <div class="range-wrapper">
{{--                                                    <span id="songCurrentTime" class="text-gradient">00:00</span>--}}
                                                    <div id="waveform"></div>
{{--                                                    <span id="songDuration" class="opacity50">00:00</span>--}}
                                                </div>
                                                <div class="controller">
                                                    <audio controls controlsList="nodownload" style="width: 80%">
                                                        {{--                                            <source src="{{ $voice->url }}" type="audio/ogg">--}}
                                                        <source src="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('UserDownloadFile', now()->addDays(1), ['id' => $voice->videoable->id,'side' => $key ,'user' => Auth::user()->id]) }}">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                </div>
                                                <div class="footer-wrapper">
                                                    <div id="loop" title="No Loop!" class="">
                                                        <svg id="loopSVG" width="20px" height="20px" viewBox="0 0 8 8"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <defs>
                                                                <linearGradient id="grad" x1="0%" y1="0%" x2="100%"
                                                                                y2="0%">
                                                                    <stop offset="0%"
                                                                          style="stop-color:rgba(98,74,255,1);stop-opacity:1"/>
                                                                    <stop offset="100%"
                                                                          style="stop-color:rgba(193,46,251,1);stop-opacity:1"/>
                                                                </linearGradient>
                                                            </defs>
                                                            <path
                                                                    d="M6 0v1h-5c-.55 0-1 .45-1 1v1h1v-1h5v1l2-1.5-2-1.5zm-4 4l-2 1.5 2 1.5v-1h5c.55 0 1-.45 1-1v-1h-1v1h-5v-1z"/>
                                                        </svg>
                                                    </div>
                                                    <div id="heart" class="">
                                                        <svg id="heart-empty" width="24px" height="24px"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 217.408 217.408">
                                                            <path d="M194.078,22.682c-10.747-8.193-22.606-12.348-35.248-12.348c-15.951,0-33.181,6.808-50.126,19.754
                   C91.759,17.142,74.529,10.334,58.578,10.334c-12.642,0-24.501,4.155-35.248,12.348C7.606,34.671-0.24,49.8,0.006,67.648
                   c0.846,61.117,100.093,133.233,104.317,136.273l4.381,3.153l4.381-3.153c4.225-3.04,103.472-75.156,104.317-136.273
                   C217.648,49.8,209.802,34.671,194.078,22.682z M153.833,149.017c-18.374,18.48-36.915,33.188-45.129,39.453
                   c-8.214-6.265-26.755-20.972-45.129-39.453c-31.479-31.661-48.274-59.873-48.57-81.585c-0.178-13.013,5.521-23.749,17.421-32.822
                   c8.073-6.156,16.872-9.277,26.152-9.277c17.563,0,34.338,10.936,45.317,20.11l4.809,4.018l4.809-4.018
                   c10.979-9.174,27.754-20.11,45.317-20.11c9.28,0,18.079,3.121,26.152,9.277c11.9,9.073,17.599,19.809,17.421,32.822
                   C202.107,89.145,185.311,117.356,153.833,149.017z"/>
                                                        </svg>
                                                        <svg id="heart-full" width="24px" height="24px"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 472.7 472.7">
                                                            <defs>
                                                                <linearGradient id="grad" x1="0%" y1="0%" x2="100%"
                                                                                y2="0%">
                                                                    <stop offset="0%"
                                                                          style="stop-color:rgba(98,74,255,1);stop-opacity:1"/>
                                                                    <stop offset="100%"
                                                                          style="stop-color:rgba(193,46,251,1);stop-opacity:1"/>
                                                                </linearGradient>
                                                            </defs>
                                                            <path fill="url(#grad)" id="XMLID_810_" d="M433.5,67c-25.3-25.3-59-39.3-94.8-39.3s-69.6,14-94.9,39.4l-7.3,7.3l-7.5-7.5
		c-25.4-25.4-59.1-39.4-95-39.4c-35.8,0-69.4,13.9-94.7,39.3C13.9,92.2,0,125.9,0,161.7s14,69.5,39.4,94.8l182.7,182.7
		c3.8,3.8,9,6,14.5,6c5.4,0,10.6-2.2,14.5-6l182.2-182.4c25.4-25.4,39.3-59.1,39.4-94.9S458.8,92.4,433.5,67z M132.5,117.2
		c-23.9,0-43.4,19.5-43.4,43.4c0,11-8.9,19.9-19.9,19.9s-19.9-8.9-19.9-19.9c0-45.8,37.3-83.1,83.1-83.1c11,0,19.9,8.9,19.9,19.9
		C152.4,108.4,143.5,117.2,132.5,117.2z"/>
                                                        </svg>
                                                    </div>
                                                    <div id="shuffle" title="Not shuffled" class="">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <rect width="24" height="24" transform="rotate(180 12 12)"
                                                                  opacity="0"/>
                                                            <path
                                                                    d="M18 9.31a1 1 0 0 0 1 1 1 1 0 0 0 1-1V5a1 1 0 0 0-1-1h-4.3a1 1 0 0 0-1 1 1 1 0 0 0 1 1h1.89L12 10.59 6.16 4.76a1 1 0 0 0-1.41 1.41L10.58 12l-6.29 6.29a1 1 0 0 0 0 1.42 1 1 0 0 0 1.42 0L18 7.42z"/>
                                                            <path
                                                                    d="M19 13.68a1 1 0 0 0-1 1v1.91l-2.78-2.79a1 1 0 0 0-1.42 1.42L16.57 18h-1.88a1 1 0 0 0 0 2H19a1 1 0 0 0 1-1.11v-4.21a1 1 0 0 0-1-1z"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="uk-alert uk-alert-warning">متاسفانه کتاب در دسترس نمیباشد!
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('site-js')
    @if(isset($product->type) && $product->type == \App\Utility\ProductType::PDF)
        @if(isset($product->catalog[1]) && !empty($product->catalog[1]))
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf.min.js"
                    integrity="sha512-g4FwCPWM/fZB1Eie86ZwKjOP+yBIxSBM/b2gQAiSVqCgkyvZ0XxYPDEcN2qqaKKEvK6a05+IPL1raO96RrhYDQ=="
                    crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf.worker.entry.min.js"
                    integrity="sha512-NJEHr6hlBM4MkVxJu+7FBk+pn7r+KD8rh+50DPglV/8T8I9ETqHJH0bO7NRPHaPszzYTxBWQztDfL6iJV6CQTw=="
                    crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf.worker.min.js"
                    integrity="sha512-QVzIOQH0mGpLAOwHfVSOGsVjh4UGon7+hQwoqIUHbTMvcyS76Ee3AUDep58mU2TvdkPgzZ4aQqxbZ0v2wsyvpA=="
                    crossorigin="anonymous"></script>
            <script>
                $('#loading-image').show();
                // Loaded via <script> tag, create shortcut to access PDF.js exports.
                var pdfjsLib = window['pdfjs-dist/build/pdf'];

                // The workerSrc property shall be specified.
                pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';

                var pdfDoc = null,
                    pageNum = 1,
                    pageRendering = false,
                    pageNumPending = null,
                    scale = 2.5,
                    canvas = document.getElementById('the-canvas'),
                    ctx = canvas.getContext('2d');

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: "post",
                    url: "{{ route('site.product.load') }}",
                    data: {
                        id: "{{ $product->id }}",
                        side: "2",
                        _token: CSRF_TOKEN
                    },
                    success: function (data) {
                        if (data.status == 200) {

                            var pdfData = atob(data.url);


                            /**
                             * Get page info from document, resize canvas accordingly, and render page.
                             * @param num Page number.
                             */


                            /**
                             * If another page rendering in progress, waits until the rendering is
                             * finised. Otherwise, executes rendering immediately.
                             */
                            function queueRenderPage(num) {
                                if (pageRendering) {
                                    pageNumPending = num;
                                } else {
                                    renderPage(num);
                                }
                            }

                            /**
                             * Displays previous page.
                             */
                            function onPrevPage() {
                                if (pageNum <= 1) {
                                    return;
                                }
                                pageNum--;
                                queueRenderPage(pageNum);
                            }

                            document.getElementById('prev').addEventListener('click', onPrevPage);


                            //enlarge
                            document.getElementById('enlarge').addEventListener('click', function () {
                                scale += 0.1;
                                queueRenderPage(pageNum);
                            });

                            //Zoom out
                            document.getElementById('letting').addEventListener('click', function () {
                                scale -= 0.1;
                                queueRenderPage(pageNum);
                            });


                            /**
                             * Displays next page.
                             */
                            function onNextPage() {
                                if (pageNum >= pdfDoc.numPages) {
                                    return;
                                }
                                pageNum++;
                                queueRenderPage(pageNum);
                            }

                            document.getElementById('next').addEventListener('click', onNextPage);

                            /**
                             * Asynchronously downloads PDF.
                             */
                            pdfjsLib.getDocument({data: pdfData}).promise.then(function (pdfDoc_) {
                                pdfDoc = pdfDoc_;
                                document.getElementById('page_count').textContent = pdfDoc.numPages;

                                // Initial/first page rendering
                                renderPage(pageNum);
                            });

                        }
                    },
                    complete: function () {
                        $('#loading-image').hide();
                    },
                    error: function (error) {
                        {{--swal({--}}
                        {{--    title: "@lang('cms.error')",--}}
                        {{--    text: "@lang('cms.try-again-few-moments')",--}}
                        {{--    icon: "error",--}}
                        {{--    button: "@lang('cms.accept-2')",--}}
                        {{--});--}}
                    }
                });

                function renderPage(num) {

                    num = parseInt(num);
                    pageRendering = true;
                    // Using promise to fetch the page
                    pdfDoc.getPage(num).then(function (page) {
                        var viewport = page.getViewport({scale: scale});
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        // Render PDF page into canvas context
                        var renderContext = {
                            canvasContext: ctx,
                            viewport: viewport
                        };
                        var renderTask = page.render(renderContext);

                        // Wait for rendering to finish
                        renderTask.promise.then(function () {
                            pageRendering = false;
                            if (pageNumPending !== null) {
                                // New page rendering is pending
                                renderPage(pageNumPending);
                                pageNumPending = null;
                            }
                        });
                    });

                    // Update page counters
                    document.getElementById('page_num').textContent = num;
                }
            </script>

        @endif
    @endif

@endsection
