@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <br><br>
                <div class="col-md-12">
                    <div class="alert alert-info border-right-info">
                        @lang('cms.alert-category-product')
                    </div>
                </div>
                <header class="panel-heading">
                    @if(isset($findCategoryProductId) )
                        @lang('cms.header-category-product-edit')
                    @else
                        @lang('cms.header-category-product-create')
                    @endif

                    <a type="button" class="btn btn-xs btn-info pull-left top-left" data-toggle="modal"
                       href="{{route('panel.categoryProduct.index')}}">@lang('cms.back')
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
                                    @lang('cms.category')
                                </a>
                            </li>
                            <li id="gallery-step">
                                <a data-toggle="tab" class="gallery-tabs" href="#gallery">
                                    @lang('cms.pic-gallery')
                                </a>
                            </li>
                        </ul>

                        @if(isset($findCategoryProductId) )
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.categoryProduct.update' , ['id' => $findCategoryProductId->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.categoryProduct.store')}}">

                                        @endif
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="tab-content">
                                            <div id="main" class="tab-pane fade in active">
                                                @include('panel-old.category-products.partials.main')
                                            </div>

                                            <div id="gallery" class="tab-pane fade">
                                                @include('panel-old.category-products.partials.gallery')
                                            </div>
                                        </div>

                                    </form>

                    </div>

                </div>

            </section>
        </div>
    </div>

@endsection

@section('admin-js')
    {{-- button --}}
    <script>
        /* next to gallery */
        $('#next').click(function () {
            $('#main').removeClass('in active');
            $('#gallery').addClass('in active');
            $('#main-home').removeClass('active');
            $('#gallery-step').addClass('active');
        });

        /* previous to main */
        $('#previous').click(function () {
            $('#gallery').removeClass('in active');
            $('#main').addClass('in active');
            $('#main-home').addClass('active');
            $('#gallery-step').removeClass('active');
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



                @if(isset($findCategoryProductId) && !empty($findCategoryProductId) && count($findCategoryProductId->image) > 0)
            var count = 5;
            @foreach($findCategoryProductId->image as $key => $itemImages)
                @if($itemImages->url != null && $key > 0 )
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
                "                            <input value=\" {{$itemImages->url}} \" id=\"thumbnail" + count + "\" class=\"form-control\" type=\"text\"\n" +
                "                                   name=\"filepath[]\">\n" +
                "                        </div>\n" +
                "                    </div>\n" +
                "<div class=\"col-md-3\">" +
                "<span  data-attrss=\"" + count + "\" class=\"btn btn-xs btn-danger minImageEx\">-</span>" +
                "</div>" +
                "                    <br>\n" +
                "<div class=\"col-md-11 text-center\">" +
                "                    <img src=\" {{$itemImages->url}} \" id=\"holder" + count + "\" style=\"margin-top:15px;max-height:100px;\">\n" +
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

    {{-- delete input in first input of gallery tab --}}
    <script>
        $('#trashImages').click(function () {
            $('.input-first-gallery').val("");
            $('.div-first-gallery').remove();
        });
    </script>
@endsection

