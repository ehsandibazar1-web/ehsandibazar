@extends('panel-old.layout.master')
@section('admin-css')
    <link href="{{ url('admin_theme/css/select2.css') }}" rel="stylesheet"/>
    <style>

        .unavailables {
            background: red !important;
            color: white !important;
        }

    </style>
@endsection


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <br><br>

                <header class="panel-heading">
                    @if(isset($findIdProducts) )
                        @lang('cms.edit-request-product')
                    @else
                        @lang('cms.request-new-product')
                    @endif

                    <a type="button" class="btn btn-xs btn-info pull-left top-left" data-toggle="modal"
                       href="{{route('panel.request.product')}}">@lang('cms.back')
                    </a>
                    <br>
                    <br>
                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')

                <div class="panel-body">
                    <div class=" form">
                        <ul class="nav nav-tabs">
                            <li id="main-home" class="active">
                                <a data-toggle="tab" class="main-home" href="#main">
                                    @lang('cms.request-product')
                                </a>
                            </li>

                            <li id="gallery-step">
                                <a data-toggle="tab" class="gallery-tabs" href="#gallery">
                                    @lang('cms.pic-gallery')
                                </a>
                            </li>

                            <li id="video-step">
                                <a data-toggle="tab" class="video-tabs" href="#video">
                                    @lang('cms.video')
                                </a>
                            </li>

                            <li id="catalog-step">
                                <a data-toggle="tab" class="catalog-tabs" href="#catalog">
                                    @lang('cms.catalog')
                                </a>
                            </li>
                        </ul>
                        @if(isset($findIdProducts) )
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.request.product.update' , ['id' => $findIdProducts->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.request.product.store')}}">

                                        @endif
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="tab-content">

                                            <div id="main" class="tab-pane fade in active">
                                                @include('panel-old.request-product.partials.descriptions')
                                            </div>

                                            <div id="gallery" class="tab-pane fade">
                                                @include('panel-old.request-product.partials.gallery')
                                            </div>

                                            <div id="video" class="tab-pane fade">
                                                @include('panel-old.request-product.partials.video')
                                            </div>

                                            <div id="catalog" class="tab-pane fade">
                                                @include('panel-old.request-product.partials.catalog')
                                            </div>
                                        </div>

                                    </form>

                    </div>

                </div>

            </section>
        </div>
    </div>

    {{-- alert modal --}}
    @include('panel-old.request-product.partials.alert-question-product')
    @include('panel-old.request-product.partials.alert-description-product')
    @include('panel-old.request-product.partials.alert-details-product');

    <div class="pulse" id="howToRequestProduct">
        <span> آموزش درخواست محصول </span>
    </div>


@endsection

@section('admin-js')

    <script src="{{ url('admin_theme/js/select2.js') }}"></script>

    {{-- select 2 --}}
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });
    </script>

    {{-- next step and previous step --}}
    <script>
        /* next to details */
        $('#next').click(function () {
            $('#main-home').removeClass('active');
            $('#gallery-step').addClass('active');
            $('#main').removeClass('in active');
            $('#gallery').addClass('in active');
        });


        /* next to video */
        $('#nextToVideo').click(function () {
            $('#gallery-step').removeClass('active');
            $('#video-step').addClass('active');
            $('#gallery').removeClass('in active');
            $('#video').addClass('in active');
        });

        /* next to catalog */
        $('#nextToCatalog').click(function () {
            $('#video-step').removeClass('active');
            $('#catalog-step').addClass('active');
            $('#video').removeClass('in active');
            $('#catalog').addClass('in active');
        });

        /* prev to pic */
        $('#prevToGallery').click(function () {
            $('#video-step').removeClass('active');
            $('#gallery-step').addClass('active');
            $('#video').removeClass('in active');
            $('#gallery').addClass('in active');
        });

        /* prev to video */
        $('#previousToVideo').click(function () {
            $('#video-step').addClass('active');
            $('#catalog-step').removeClass('active');
            $('#catalog').removeClass('in active');
            $('#video').addClass('in active');
        });

        /* prev to desc */
        $('#prevToDescription').click(function () {
            $('#main-home').addClass('active');
            $('#gallery-step').removeClass('active');
            $('#main').addClass('in active');
            $('#gallery').removeClass('in active');
        });


    </script>

    {{-- button add or delete image and add image input when edit product --}}
    <script>
        $(document).ready(function () {
            var plusImage = $('#plusImage');
            var minImage = $('#minImage');
            var result = $('.resultGalleryImage');
            var count = 5;

            /* add image */
            plusImage.click(function (e) {
                count++;
                e.preventDefault();
                result.append("<div id='gallery-item' data-att='" + count + "' class=\"form-group \">\n" +
                    "                    <label for=\"images\" class=\"control-label col-lg-2\">تصویر\n" +
                    "                    </label>\n" +
                    "                    <div class=\"col-md-6\">\n" +
                    "                        <div class=\"input-group\">\n" +
                    "                            <span class=\"input-group-btn\">\n" +
                    "                                                    <a data-input=\"thumbnail" + count + "\" data-preview=\"holder" + count + "\"\n" +
                    "                                                       class=\"lfm btn btn-primary\">\n" +
                    "                                                      <i class=\"fa fa-picture-o\"></i> انتخاب کنید\n" +
                    "                                                    </a>\n" +
                    "                                                  </span>\n" +
                    "                            <input id=\"thumbnail" + count + "\" class=\"form-control\" type=\"text\"\n" +
                    "                                   name=\"filepath[]\">\n" +
                    "                        </div>\n" +
                    "                    </div>\n" +
                    "<div class=\"col-md-3\">" +
                    "<span id=\"minImage\" data-attrss=\"" + count + "\" class=\"btn btn-xs btn-danger\">-</span>" +
                    "</div>" +
                    "                    <br>\n" +
                    "<div class=\"col-md-11 text-center\">" +
                    "                    <img  id=\"holder" + count + "\" style=\"margin-top:15px;max-height:100px;\">\n" +
                    "</div>" +
                    "                </div>");


                $('.lfm').filemanager('image', {prefix: route_prefix});

                /* remove each input splity */
                $('span').click(function () {
                    var id = $(this).attr('data-attrss');
                    var myDiv = $('div[data-att="' + id + '"]').remove();
                });

            });

            /* delete image */
            minImage.click(function (e) {
                e.preventDefault();
                $('#gallery-item:last-child').remove();
            });

            /* when edit product mode */


                @if(isset($findIdProducts) && !empty($findIdProducts) && !empty($images) && count($images) > 0)
            var count = 5;
            @foreach($images as $key => $itemImages)
                @if($itemImages != null && $key > 0 )
                count++;
            result.append("<div id='gallery' data-att= " + count + "  class=\"form-group \">\n" +
                "                    <label for=\"images\" class=\"control-label col-lg-2\">تصویر\n" +
                "                    </label>\n" +
                "                    <div class=\"col-md-6\">\n" +
                "                        <div class=\"input-group\">\n" +
                "                            <span class=\"input-group-btn\">\n" +
                "                                                    <a data-input=\"thumbnail" + count + "\" data-preview=\"holder" + count + "\"\n" +
                "                                                       class=\"lfm btn btn-primary\">\n" +
                "                                                      <i class=\"fa fa-picture-o\"></i> انتخاب کنید\n" +
                "                                                    </a>\n" +
                "                                                  </span>\n" +
                "                            <input value=\" {{$itemImages}} \" id=\"thumbnail" + count + "\" class=\"form-control\" type=\"text\"\n" +
                "                                   name=\"filepath[]\">\n" +
                "                        </div>\n" +
                "                    </div>\n" +
                "<div class=\"col-md-3\">" +
                "<span  data-attrss=\"" + count + "\" class=\"btn btn-xs btn-danger minImageEx\">-</span>" +
                "</div>" +
                "                    <br>\n" +
                "<div class=\"col-md-11 text-center\">" +
                "                    <img src=\" {{$itemImages}} \" id=\"holder" + count + "\" style=\"margin-top:15px;max-height:100px;\">\n" +
                "</div>" +
                "                </div>");
            $('.lfm').filemanager('image', {prefix: route_prefix});

            /* remove each input splity */
            $('span').click(function () {
                var id = $(this).attr('data-attrss');
                var myDiv = $('div[data-att="' + id + '"]').remove();
            });

            @endif
            @endforeach
            @endif

        });
    </script>

    {{-- button add or delete video and add video input when edit product --}}
    <script>
        $(document).ready(function () {
            var plusVideo = $('#plusVideo');
            var minVideo = $('#minVideo');
            var result = $('.resultGalleryVideo');
            var count = 5;

            /* add video */
            plusVideo.click(function (e) {
                count++;
                e.preventDefault();
                result.append("<div id='video' data-attr-videos=" + count + " class=\"form-group \">\n" +
                    "                    <label for=\"video\" class=\"control-label col-lg-2\">ویدیو\n" +
                    "                    </label>\n" +
                    "                    <div class=\"col-md-6\">\n" +
                    "                        <div class=\"input-group\">\n" +
                    "                            <span class=\"input-group-btn\">\n" +
                    "                                                    <a data-input=\"videonail" + count + "\" data-preview=\"hold" + count + "\"\n" +
                    "                                                       class=\"lfm1 btn btn-primary\">\n" +
                    "                                                      <i class=\"fa fa-picture-o\"></i> انتخاب کنید\n" +
                    "                                                    </a>\n" +
                    "                                                  </span>\n" +
                    "                            <input id=\"videonail" + count + "\" class=\"form-control\" type=\"text\"\n" +
                    "                                   name=\"video[]\">\n" +
                    "                        </div>\n" +
                    "                    </div>\n" +
                    "<div class=\"col-md-3\">" +
                    "<span  data-attr-video=\"" + count + "\" class=\"btn btn-xs btn-danger minImageEx\">-</span>" +
                    "</div>" +
                    "                    <br>\n" +
                    "                </div>");
                $('.lfm1').filemanager('file', {prefix: route_prefix});

                /* remove each input splity */
                $('span').click(function () {
                    var id = $(this).attr('data-attr-video');
                    var myDiv = $('div[data-attr-videos="' + id + '"]').remove();
                });

            });

            /* delete video */
            minVideo.click(function (e) {
                e.preventDefault();
                $('#video:last-child').remove();
            });

            /* when edit product mode */

                @if(isset($findIdProducts) && !empty($findIdProducts) && !empty($videos) && count($videos) > 0 )
            var count = 5;
            @foreach($videos as $key => $itemVideo)
                @if($itemVideo != null && $key > 0 )
                count++;
            result.append("<div id='video'  data-attr-videos=" + count + "  class=\"form-group \">\n" +
                "                    <label for=\"video\" class=\"control-label col-lg-2\">ویدیو\n" +
                "                    </label>\n" +
                "                    <div class=\"col-md-6\">\n" +
                "                        <div class=\"input-group\">\n" +
                "                            <span class=\"input-group-btn\">\n" +
                "                                                    <a data-input=\"videonail" + count + "\" data-preview=\"hold" + count + "\"\n" +
                "                                                       class=\"lfm1 btn btn-primary\">\n" +
                "                                                      <i class=\"fa fa-picture-o\"></i> انتخاب کنید\n" +
                "                                                    </a>\n" +
                "                                                  </span>\n" +
                "                            <input value=\" {{$itemVideo}} \" id=\"videonail" + count + "\" class=\"form-control\" type=\"text\"\n" +
                "                                   name=\"video[]\">\n" +
                "                        </div>\n" +
                "                    </div>\n" +
                "<div class=\"col-md-3\">" +
                "<span  data-attr-video=\"" + count + "\" class=\"btn btn-xs btn-danger minImageEx\">-</span>" +
                "</div>" +
                "<br><br><br>" +
                "<div class=\"row\">" +
                "<div class=\"col-md-12 text-center\">" +

                "<video width=\"200\" height=\"150\" controls>" +
                "<source src=\"{{$itemVideo }}\">" +
                "</video>" +

                "</div>" +
                "</div>" +
                "                    <br>\n" +
                "                </div>");
            $('.lfm1').filemanager('file', {prefix: route_prefix});

            /* remove each input splity */
            $('span').click(function () {
                var id = $(this).attr('data-attr-video');
                var myDiv = $('div[data-attr-videos="' + id + '"]').remove();
            });

            @endif
            @endforeach
            @endif

        });


    </script>

    {{-- delete input in first input of gallery tab --}}
    <script>
        $('#trashImages').click(function () {
            $('.input-first-gallery').val("");
            $('.div-first-gallery').remove();
        });
    </script>

    {{-- delete input in first input of video tab --}}
    <script>
        $('#trashVideo').click(function () {
            $('.input-first-video').val("");
            $('.div-first-video').remove();
        });
    </script>

    {{-- delete input in first input of catalog tab --}}
    <script>
        $('#trashCatalog').click(function () {
            $('.input-first-catalog').val("");
            $('.div-first-catalog').remove();
        });
    </script>

    {{-- check select option request --}}
    <script>
        $('#product_id').change(function () {
            var selecteds = $("#product_id option:selected").val();

            if (selecteds >= 0) {
                $('.desc-product').css('display', 'block');
            }

            if (selecteds == 0) {
                $('#details-request').css('display', 'block');
                $('#result-found-product').html(" ");
            } else {
                $('#details-request').css('display', 'none');
            }

            if (selecteds == "") {
                $('.desc-product').css('display', 'none');
                alert("<?= \Illuminate\Support\Facades\Lang::get('cms.alert-select-option-request') ?>");
                $('#result-found-product').html(" ");
            }

            /* when choosed request product show product links and image */
            if (selecteds > 0) {

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "post",
                    url: "{{route('panel.request.product.ajaxRequestProduct')}}",
                    data: {
                        product_id: selecteds,
                        _token: CSRF_TOKEN
                    },

                    success: function (data) {
                       // console.log(data);
                        if (data instanceof Object) {
                            $('#result-found-product').html(data.html);
                        } else {
                            alert("لطفا چند لحظه دیگر وارد شوید.");
                        }
                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });

            }


        });

        var selecteds = $("#product_id option:selected").val();

        if (selecteds >= 0) {
            $('.desc-product').css('display', 'block');
        }

        if (selecteds == 0) {
            $('#details-request').css('display', 'block');
            $('#result-found-product').html(" ");
        } else {
            $('#details-request').css('display', 'none');
        }

        if (selecteds == "") {
            $('.desc-product').css('display', 'none');
            $('#result-found-product').html(" ");
        }

        /* when choosed request product show product links and image */
        if (selecteds > 0) {

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: "post",
                url: "{{route('panel.request.product.ajaxRequestProduct')}}",
                data: {
                    product_id: selecteds,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                   // console.log(data);
                    if (data instanceof Object) {
                        $('#result-found-product').html(data.html);
                    } else {
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                },
                error: function (error) {
                    //alert(error);
                    alert("لطفا چند لحظه دیگر وارد شوید.");
                }
            });

        }

    </script>

    {{-- click example1 --}}
    <script>
        $('.example11').click(function (e) {
            e.preventDefault();
            $('#example1').toggle();
            $('#example2').css('display', 'none');
            $('#example3').css('display', 'none');
        });

        $('.example22').click(function (e) {
            e.preventDefault();
            $('#example1').css('display', 'none');
            $('#example2').toggle();
            $('#example3').css('display', 'none');
        });

        $('.example33').click(function (e) {
            e.preventDefault();
            $('#example1').css('display', 'none');
            $('#example2').css('display', 'none');
            $('#example3').toggle();
        });
    </script>

@endsection

