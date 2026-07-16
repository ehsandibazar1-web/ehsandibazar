@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf_viewer.min.css"
          integrity="sha512-srhhMuiYWWC5y1i9GDsrZwGM/+rZn0fsyBW/jYzbmSiwGs8I2iAX9ivxctNznU+WndPgbqtbYECLD8KYgEB3fg=="
          crossorigin="anonymous"/>
    <style>
        #the-canvas {
            border: 1px solid black;
            direction: ltr;
            max-width: inherit;
        }

        input[type=number] {
            height: 30px;
        }

        input[type=number]:hover::-webkit-inner-spin-button {
            width: 14px;
            height: 30px;
        }

        body {
            background-color: #f2f2f2;
        }

        .AudioPlayerLayout {
            background-color: #fff;
            border: 3px solid blue;
            width: 50%;
            margin: 50px 25%;
            display: flex;
            flex-direction: column;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 1px 1px 7px #ccc;
        }

        .AudioPlayer audio {
            width: 100%;
        }

        .AudioList ul {
            text-align: right;
            list-style: none;
            display: block;
            padding: 0px 10px;
            direction: rtl;
        }

        .AudioList ul li {
            background: #f2f2f2a6;
            border-radius: 10px;
            padding: 10px;
            margin: 10px 0px;
            cursor: pointer;
        }

        span.ListTime {
            float: left;
            width: 52px;
            text-align: left;
            transition-duration: 0.5s;
            padding: 0 0 0 10px;
        }

        a.ListItemDl {
            float: left;
            color: cadetblue;
        }

        a.ListItemDl :hover {
            cursor: pointer;
            color: #000;
            transition-duration: 0.5s;
        }

        .AudioPlayerBoard p {
            font-size: 20px;
            font-weight: 900;
            text-align: center;
            margin: 10px;
            color: red;
        }

        .arrow {
            text-align: center;
            color: #999;
            font-size: 22px;
            line-height: 30px;
            margin-bottom: 10px;
        }

        .arrow i {
            background: #f7f7f77d;
            padding: 5px 10px;
            border-radius: 10px;
        }

        .arrow i:hover {
            background: #ccc;
            padding: 5px 10px;
            transition-duration: 0.5s;
            color: #fff;
            cursor: pointer;
        }

        .AudioList ul :hover {
            background: #dbf9ee;
        }

        span.ListTime i {
            font-size: 11px;
            color: #7d7d7d;
            padding-right: 3px;
        }

        .bg-voice {
            background-color: #dbf9ee !important;
        }


    </style>
    @include('users.layouts.partials.styles')
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());


        // var PlayListTemp = "";
        // var PlayerEpisodeTemp = "0";

        //Time per Secend
        // var PlaylistTest = [
        //     {
        //         Url:
        //             "http://dl.nex1music.ir/1397/10/03/Mohsen%20Ebrahimzadeh%20-%20Gole%20Poone%20[128].mp3?time=1546196521&filename=/1397/10/03/Mohsen%20Ebrahimzadeh%20-%20Gole%20Poone%20[128].mp3",
        //         Title: "محسن ابراهیم زاده گل پونه",
        //         Time: "1800",
        //         Episode: "1",
        //         Download: "true"
        //     },
        //     {
        //         Url:
        //             "http://dl.nex1music.ir/1397/10/01/Hojat%20Ashrafzadeh%20-%20Asheghe%20Toam%20[128].mp3?time=1546198607&filename=/1397/10/01/Hojat%20Ashrafzadeh%20-%20Asheghe%20Toam%20[128].mp3",
        //         Title: "حجت اشرف زاده عاشق توام",
        //         Time: "4214",
        //         Episode: "2",
        //         Download: "false"
        //     },
        //     {
        //         Url:
        //             "http://dl.nex1music.ir/1397/10/01/Reza%20Shiri%20-%20Age%20Bazam%20Biay%20(Ft%20Saeed%20Sam)%20[128].mp3?time=1546198598&filename=/1397/10/01/Reza%20Shiri%20-%20Age%20Bazam%20Biay%20(Ft%20Saeed%20Sam)%20[128].mp3",
        //         Title: "رضا شیری اگه بازم بیای",
        //         Time: "7130",
        //         Episode: "3",
        //         Download: "true"
        //     }
        // ];
        // PlayListTemp = PlaylistTest;

        $(document).ready(function () {
            ChekArrow();
            AudioPlayerList(1);

            $(".AudioList ul li").click(function () {
                PlaySound($(this).attr("id"));
            });

            $(".arrow .fa-long-arrow-left").click(function () {
                PlaySound(PlayerEpisodeTemp - 1);
            });

            $(".arrow .fa-long-arrow-right").click(function () {
                PlaySound(PlayerEpisodeTemp * 1 + 1);
            });
        });

        // function log(x) {
        //     console.log("--> " + x);
        // }
        //
        // function AudioPlayerList(x) {
        //     var ListCash = "";
        //     if (x == 1) {
        //         PlayListTemp = PlaylistTest;
        //     }
        //
        //     if (PlayListTemp.length == 0) {
        //         $(".arrow .fa-long-arrow-left").hide();
        //         $(".arrow .fa-long-arrow-right").hide();
        //     }
        //
        //     for (var z = 0; z < PlayListTemp.length; z++) {
        //         ListCash +=
        //             "<li id='" +
        //             z +
        //             "'><span class='ListTitle'>" +
        //             PlaylistTest[z].Episode +
        //             " " +
        //             PlaylistTest[z].Title +
        //             "</span><span class='ListTime'>" +
        //             Math.floor(PlaylistTest[z].Time / 60) +
        //             "<i class='fa fa-clock-o'></i>" +
        //             "</span>" +
        //             DlCheck(z) +
        //             "<i class='fa fa-download'></i></a></li>";
        //     }
        //     $(".AudioList ul").html(ListCash);
        //     ChekArrow();
        // }
        //
        // function DlCheck(x) {
        //     var r = "";
        //     if (PlayListTemp[x].Download == "true") {
        //         r = "<a href='" + PlayListTemp[x].Url + "' class='ListItemDl'>";
        //     } else {
        //         r = "<a href='#' style='display: none!important;' class='ListItemDl'>";
        //     }
        //
        //     return r;
        //     r = "";
        // }
        //
        // function PlaySound(x) {
        //     PlayerEpisodeTemp = x;
        //     ChekArrow();
        //     $(".AudioPlayerBoard p").html(
        //         $("#" + x)
        //             .find(".ListTitle")
        //             .html()
        //     );
        //
        //     $(".AudioPlayer").slideUp();
        //     $(".AudioPlayer").html();
        //     $(".AudioPlayer ").html(
        //         "<audio id='AudioDiv' controls controlsList='nodownload'><source src='" +
        //         PlayListTemp[x].Url +
        //         "'type='audio/mpeg'>Your browser does not support the audio element.</audio>"
        //     );
        //     $(".AudioPlayer").slideDown();
        // }
        //
        // function ChekArrow() {
        //     var Len = PlayListTemp.length;
        //     if (PlayerEpisodeTemp >= Len - 1) {
        //         $(".arrow .fa-long-arrow-right").hide();
        //         $(".arrow .fa-long-arrow-left").show();
        //     } else if (PlayerEpisodeTemp == "0") {
        //         $(".arrow .fa-long-arrow-left").hide();
        //         $(".arrow .fa-long-arrow-right").show();
        //     } else {
        //         $(".arrow .fa-long-arrow-left").show();
        //         $(".arrow .fa-long-arrow-right").show();
        //     }
        // }

    </script>
@endsection
@section('content')
    <main class="profile-user-page default">
        <div class="container wrapper default">
            <div class="row">
                <div class="profile-page col-xl-9 col-lg-8 col-md-12 order-2">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-12">
                                <h1 class="title-tab-content text-danger"> لطفا تا بارگزاری فایل ها شکیبا باشید</h1>
                            </div>
                            <div class="content-section default">
                                @if(isset($product->type) && $product->type == \App\Utility\ProductType::PDF)
                                    @if(isset($product->catalog[1]) && !empty($product->catalog[1]))
                                        <div class="col-12">
                                            <div>
                                                <button id="prev" class="btn btn-secondary">قبلی</button>
                                                <button id="next" class="btn btn-secondary">بعدی</button>
                                                <button id="enlarge" class="btn btn-secondary">+</button>
                                                <button id="letting" class="btn btn-secondary">-</button>
                                                <input type="number" id="desiredPage"
                                                       style="border: 1px solid;width: 10%;text-align: center"
                                                       onchange="renderPage($(this).val())">

                                                &nbsp; &nbsp;
                                                <span>صفحه : <span id="page_num"></span> / <span
                                                            id="page_count"></span></span>
                                            </div>

                                            <canvas id="the-canvas"></canvas>
                                            <img src="{{ asset('site_theme/loading.gif') }}" alt="loading"
                                                 id="loading-image">

                                        </div>
                                    @endif
                                @elseif(isset($product->type) && $product->type == \App\Utility\ProductType::VIDEO)
                                    <div class="row">
                                        <div class="col-12">
                                            @if(isset($product->video) && !empty($product->video))
                                                @foreach($product->video as $key => $video)
                                                    @if($key > 0)
                                                        <span>
                                                            <h5>  عنوان {{ $key }} : {{ $video->title }}</h5>
                                                            @if(isset($product->catalog[$key]) && !empty($product->catalog[$key]))
                                                                <a href="{{ $product->catalog[$key]->url }}"
                                                                   target="_blank">دانلود پی دی اف راهنما</a>
                                                            @endif
                                                        </span>
                                                        <div>
                                                            {!! $video->url !!}
                                                        </div>
                                                    @endif
                                                @endforeach
                                                {{--                                                @foreach($product->video as $key => $video)--}}
                                                {{--                                                    @if($key > 0)--}}
                                                {{--                                                        <a href="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('UserDownloadFile', now()->addDays(1), ['id' => $product->id,'side' => $key ,'user' => Auth::user()->id]) }}"--}}
                                                {{--                                                           target="_blank">--}}
                                                {{--                                                            دانلود پارت {{ $key }}--}}
                                                {{--                                                        </a>--}}
                                                {{--                                                    @endif--}}
                                                {{--                                                @endforeach--}}
                                            @endif
                                        </div>
                                    </div>
                                @elseif(isset($product->type) && $product->type == \App\Utility\ProductType::VOICE)
                                    @if(isset($product->video[1]) && !empty($product->video[1]))
                                        <div class="AudioPlayerLayout">
                                            {{--                                            <div class="AudioPlayerBoard">--}}
                                            {{--                                                <p>{{ $product->title }}</p>--}}
                                            {{--                                                <div class="arrow">--}}
                                            {{--                                                    <i class="fa fa-arrow-left"></i>--}}
                                            {{--                                                    <i class="fa fa-arrow-right"></i>--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                            {{--                                            <div class="AudioPlayer">--}}
                                            {{--                                                <audio id='AudioDiv' controls controlsList='nodownload'>--}}
                                            {{--                                                    <source src='' type='audio/mpeg'>--}}
                                            {{--                                                    Your browser does not support the audio element.--}}
                                            {{--                                                </audio>--}}

                                            {{--                                            </div>--}}
                                            {{--                                            <div class="AudioList">--}}
                                            {{--                                                <ul>--}}
                                            {{--                                                </ul>--}}
                                            {{--                                            </div>--}}
                                            @foreach($product->video as $key => $voice)
                                                @if($key != 0)
                                                    {!! $voice->url !!}
                                                @endif
                                            @endforeach
                                            @endif
                                            @else
                                                <div class="uk-alert uk-alert-warning">متاسفانه کتاب در دسترس نمیباشد!
                                                </div>
                                            @endif
                                        </div>
                            </div>
                        </div>
                    </div>
                    @include('users.layouts.partials.aside-menu')
                </div>
            </div>
    </main>
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
    @elseif(isset($product->type) && $product->type == \App\Utility\ProductType::VOICE)
        <script>
            document.addEventListener('contextmenu', event => event.preventDefault());


            {{--var PlayListTemp = "";--}}
            {{--var PlayerEpisodeTemp = "0";--}}

            {{--//Time per Secend--}}
            {{--var PlaylistTest = [--}}
            {{--        @foreach($product->video as $key => $voice)--}}
            {{--        @if($key != 0)--}}
            {{--    {--}}
            {{--        Url: "{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('UserDownloadFile', now()->addDays(1), ['id' => $product->id,'side' => $key ,'user' => Auth::user()->id]) }}",--}}
            {{--        Title: "{{$key}}) {{ $product->title }} - پارت {{ $key }}",--}}
            {{--        Time: "",--}}
            {{--        Episode: "",--}}
            {{--        Download: "false"--}}
            {{--    },--}}
            {{--    @endif--}}
            {{--    @endforeach--}}
            {{--];--}}
            {{--PlayListTemp = PlaylistTest;--}}

            {{--$(document).ready(function () {--}}
            {{--    ChekArrow();--}}
            {{--    AudioPlayerList(1);--}}

            {{--    $(".AudioList ul li").click(function () {--}}
            {{--        PlaySound($(this).attr("id"));--}}
            {{--    });--}}

            {{--    $(".arrow .fa-long-arrow-left").click(function () {--}}
            {{--        PlaySound(PlayerEpisodeTemp - 1);--}}
            {{--    });--}}

            {{--    $(".arrow .fa-long-arrow-right").click(function () {--}}
            {{--        PlaySound(PlayerEpisodeTemp * 1 + 1);--}}
            {{--    });--}}
            {{--});--}}

            {{--function log(x) {--}}
            {{--    console.log("--> " + x);--}}
            {{--}--}}

            {{--function AudioPlayerList(x) {--}}
            {{--    var ListCash = "";--}}
            {{--    if (x == 1) {--}}
            {{--        PlayListTemp = PlaylistTest;--}}
            {{--    }--}}

            {{--    if (PlayListTemp.length == 0) {--}}
            {{--        $(".arrow .fa-long-arrow-left").hide();--}}
            {{--        $(".arrow .fa-long-arrow-right").hide();--}}
            {{--    }--}}

            {{--    for (var z = 0; z < PlayListTemp.length; z++) {--}}
            {{--        ListCash +=--}}
            {{--            "<li id='" +--}}
            {{--            z +--}}
            {{--            "'><span class='ListTitle'>" +--}}
            {{--            PlaylistTest[z].Episode +--}}
            {{--            " " +--}}
            {{--            PlaylistTest[z].Title +--}}
            {{--            "</span><span class='ListTime'></span>" +--}}
            {{--            DlCheck(z) +--}}
            {{--            "<i class='fa fa-download'></i></a></li>";--}}
            {{--    }--}}
            {{--    $(".AudioList ul").html(ListCash);--}}
            {{--    ChekArrow();--}}
            {{--}--}}

            {{--function DlCheck(x) {--}}
            {{--    var r = "";--}}
            {{--    if (PlayListTemp[x].Download == "true") {--}}
            {{--        r = "<a href='" + PlayListTemp[x].Url + "' class='ListItemDl'>";--}}
            {{--    } else {--}}
            {{--        r = "<a href='#' style='display: none!important;' class='ListItemDl'>";--}}
            {{--    }--}}

            {{--    return r;--}}
            {{--    r = "";--}}
            {{--}--}}

            {{--function PlaySound(x) {--}}
            {{--    $('html, body').animate({--}}
            {{--        scrollTop: $(".AudioPlayerLayout").offset().top--}}
            {{--    }, 1000);--}}

            {{--    $(".bg-voice").removeClass("bg-voice");--}}

            {{--    PlayerEpisodeTemp = x;--}}
            {{--    ChekArrow();--}}
            {{--    $(".AudioPlayerBoard p").html(--}}
            {{--        $("#" + x)--}}
            {{--            .find(".ListTitle")--}}
            {{--            .html()--}}
            {{--    );--}}

            {{--    $(".AudioPlayer").slideUp();--}}
            {{--    $(".AudioPlayer").html();--}}
            {{--    $(".AudioPlayer ").html(--}}
            {{--        "<audio id='AudioDiv' controls controlsList='nodownload'><source src='" +--}}
            {{--        PlayListTemp[x].Url +--}}
            {{--        "'type='audio/mpeg'>Your browser does not support the audio element.</audio>"--}}
            {{--    );--}}
            {{--    $(".AudioPlayer").slideDown();--}}
            {{--    $('#AudioDiv').get(0).play();--}}
            {{--    $("#" + x).addClass("bg-voice");--}}
            {{--}--}}

            {{--function ChekArrow() {--}}
            {{--    var Len = PlayListTemp.length;--}}
            {{--    if (PlayerEpisodeTemp >= Len - 1) {--}}
            {{--        $(".arrow .fa-long-arrow-right").hide();--}}
            {{--        $(".arrow .fa-long-arrow-left").show();--}}
            {{--    } else if (PlayerEpisodeTemp == "0") {--}}
            {{--        $(".arrow .fa-long-arrow-left").hide();--}}
            {{--        $(".arrow .fa-long-arrow-right").show();--}}
            {{--    } else {--}}
            {{--        $(".arrow .fa-long-arrow-left").show();--}}
            {{--        $(".arrow .fa-long-arrow-right").show();--}}
            {{--    }--}}
            {{--}--}}

        </script>
    @endif
@endsection
