@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')

    <!-- Horizontal Layout -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-xs-0">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.product.index')
                                <a href="{{ route('panel.product.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> لیست محصولات </a>
                            @endcan
                        </div>
                    </h2>

                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="#" onClick="return false;" class="dropdown-toggle" data-toggle="dropdown"
                               role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="{{ route('panel.dashboard.index')  }}">داشبورد</a>
                                </li>
                                @can('panel.product.index')
                                    <li>
                                        <a href="{{ route('panel.product.index')  }}" style="font-size: 10px">لیست
                                            محصولات </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation">
                            <a href="#base" data-toggle="tab" class="active show"> اطلاعات پایه </a>
                        </li>
                        <li role="presentation">
                            <a href="#attribute" data-toggle="tab"> خصوصیات </a>
                        </li>
                        <li role="presentation">
                            <a href="#variations" data-toggle="tab"> موجودی و قیمت </a>
                        </li>
                        <li role="presentation">
                            <a href="#gallery" data-toggle="tab"> گالری تصاویر </a>
                        </li>
                        <li role="presentation">
                            <a href="#seo" data-toggle="tab"> سئو </a>
                        </li>
                        <li role="presentation">
                            <a href="#files" data-toggle="tab"> فایل </a>
                        </li>
                        <li role="presentation">
                            <a href="#pdf" data-toggle="tab"> پی دی اف </a>
                        </li>
                    </ul>
                    @if(isset($findIdProducts))
                        <form class="form-horizontal" method="post"
                              action="{{ route('panel.product.update',$findIdProducts->id)  }}">
                            {{ method_field("PATCH") }}
                            @else
                                <form class="form-horizontal" method="post"
                                      action="{{ route('panel.product.store')  }}">
                                    @endif

                                    @csrf

                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade in active show" id="base">
                                            @include('panel.product.partials.basic')
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="attribute">
                                            {{-- attribute --}}
                                            <div class="row clearfix mt-5">
                                                <div class="col-lg-12 col-md-12 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div id="resultAjaxAllAttributeCategory">
                                                            <p class="alert alert-info">
                                                                برای درج ویژگی محصول ابتدا دسته بندی دارای خصوصیت را انتخاب نمایید
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="variations">
                                            @include('panel.product.partials.variations')
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="gallery">
                                            @include('panel.product.partials.gallery')
                                        </div>
                                        @include('panel.layout.inputs.seo')

                                        <div role="tabpanel" class="tab-pane fade" id="files">
                                            @include('panel.product.partials.files')
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="pdf">
                                            @include('panel.product.partials.pdf')
                                        </div>
                                    </div>

                                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Horizontal Layout -->

@stop
@section('admin-js')
    @include('panel.layout.ckjs')

    {{-- change category and send by ajax and comming attribute data --}}
    <script>

        var categoryId = $('.categorySelect');

        /* when  old */
        var valueCategory_id = categoryId.val();
        var Product_id = <?= isset($findIdProducts) ? $findIdProducts->id : 0 ?>;
        // alert(valueCategory_id);
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        if (valueCategory_id != "") {
            $.ajax({
                type: "post",
                url: "{{route('panel.product.ajax_attributes')}}",
                data: {
                    id: valueCategory_id,
                    Product_id: Product_id,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                    //console.log(data);
                    // alert(data);
                    if (data instanceof Object) {
                        // console.log(data);
                        $("#resultAjaxAllAttributeCategory").html(data.html);
                    } else {
                        alert(data);
                    }

                },
                error: function (error) {
                    // alert(error);
                    alert("لطفا چند لحظه دیگر وارد شوید.");

                }
            });
        }
        /* when change */
        categoryId.change(function () {
            var valueCategory_id = categoryId.val();
            var Product_id = <?= isset($findIdProducts) ? $findIdProducts->id : 0 ?>;
            // alert(valueCategory_id);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: "post",
                url: "{{route('panel.product.ajax_attributes')}}",
                data: {
                    id: valueCategory_id,
                    Product_id: Product_id,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                    //console.log(data);
                    // alert(data);
                    if (data instanceof Object) {
                        // console.log(data);
                        $("#resultAjaxAllAttributeCategory").html(data.html);
                    } else {
                        alert(data);
                    }

                },
                error: function (error) {
                    // alert(error);
                    alert("لطفا چند لحظه دیگر وارد شوید.");

                }
            });

        });

    </script>

    {{-- change category when edit --}}
    <script>
        @if(isset($findIdProducts))
        var categoryId = $('.categorySelect');
        var valueCategory_id = categoryId.val();
        var Product_id = <?= $findIdProducts->id ?>


        // alert(valueCategory_id);

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "post",
            url: "{{route('panel.product.ajax_attributes')}}",
            data: {
                id: valueCategory_id,
                Product_id: Product_id
                , _token: CSRF_TOKEN
            },

            success: function (data) {
                //console.log(data);

                if (data instanceof Object) {
                    //console.log(data);
                    $("#resultAjaxAllAttributeCategory").html(data.html);
                } else {
                    alert(data);
                }

            },
            error: function (error) {
                alert(error);
                //alert("لطفا چند لحظه دیگر وارد شوید.");

            }
        });

        @endif


    </script>

    {{-- change type of product hidden or show expire date --}}
    <script>

        var typeProducts = $('.typeProducts');

        /* when default */
        var selecteds = $(".typeProducts option:selected").val();
        if (selecteds == 1) {
            $('.display-none').css('display', 'block');
        } else {
            $('.display-none').css('display', 'none');
        }

        /* when change */
        typeProducts.change(function () {

            var selecteds = $(".typeProducts option:selected").val();

            if (selecteds == 1) {
                $('.display-none').css('display', 'block');
            } else {
                $('.display-none').css('display', 'none');
            }

            $('.expire_date_value').val("");
            /* $('.display-none').toggle();*/
        });

        @if(isset($findIdProducts) && !empty($findIdProducts->expire_date))
        $('.display-none').css('display', 'block');
        @endif


    </script>

    {{-- get button add or delete attribute and when edit product mode initial input dynamic --}}
    <script>

        $(document).ajaxStop(function (event, request, settings) {

            var add;
            var shAdd;
            var active = $('#description').attr('class');
            if (active == "active") {
                /* add attribute input */
                $('.exAttributes').click(function (e) {
                    e.preventDefault();
                    var thiss = $(this);
                    add = thiss.attr('data-attrs-filit');
                    shAdd = "shahriar" + add;
                    $("#" + add).append(" <div  id='" + shAdd + "' class=\"form-group{{ $errors->has('attributes') ? ' has-error' : '' }}\">\n" +
                        "                                            <label  for=\"details\"\n" +
                        "                                                   class=\"col-md-2 control-label\" style=\" color :#8aa2ce\">خصوصیات بیشتر</label>\n" +
                        "                                            <div  class=\"col-md-10\">\n" +
                        "                                                <input id=\"details\" type=\"text\" class=\"form-control\" name=\"attributes[" + add + "][]\"\n" +
                        "                                                       value=\"\" required autofocus>\n" +
                        "\n" +
                        "                                                @if ($errors->has('attributes'))\n" +
                        "                                                    <span class=\"help-block\">\n" +
                        "                                        <strong>{{ $errors->first('attributes') }}</strong>\n" +
                        "                                                    </span>\n" +
                        "                                                @endif\n" +
                        "                                            </div>\n" +
                        "\n" +
                        "\n" +
                        "                                        </div>");
                });

                /* delete attribute input */
                $('.delAttributes').click(function (e) {
                    e.preventDefault();
                    var thiss = $(this);
                    var del = thiss.attr('data-attrs-filit');
                    del = "shahriar" + del;
                    $('div#' + del + ':last').remove();

                });
            }

            //alert(active);
            if (active == "active") {
                /* when edit product mode */
                @if(isset($findIdProducts))

                <?php $arrays = collect($findIdProducts->attributevalues)->groupBy('attribute_id') ?>

                        @foreach($arrays as $key => $item)
                        @if($item->count() > 1)
                        @foreach($item as $keys => $values)
                        @if($keys != 0)
                if (active == "active") {
                    var id = <?= $values->attribute_id ?>;

                    var shAdd = "shahriar" + id;

                    $("#" + id).append(" <div id='" + shAdd + "' class=\"form-group{{ $errors->has('attributes') ? ' has-error' : '' }}\">\n" +
                        "                                            <label  for=\"details\"\n" +
                        "                                                   class=\"col-lg-2 control-label \" style=\" color :#8aa2ce\">خصوصیات بیشتر</label>\n" +
                        "                                            <div  class=\"col-lg-10\">\n" +
                        "                                                <input id=\"details\" type=\"text\" class=\"form-control\" name=\"attributes[" + id + "][]\"\n" +
                        "                                                       value=\"{{ isset($values)  ? $values->value  : old('attributes') }}\"  autofocus>\n" +
                        "\n" +
                        "                                                @if ($errors->has('attributes'))\n" +
                        "                                                    <span class=\"help-block\">\n" +
                        "                                        <strong>{{ $errors->first('attributes') }}</strong>\n" +
                        "                                                    </span>\n" +
                        "                                                @endif\n" +
                        "                                            </div>\n" +
                        "\n" +
                        "\n" +
                        "                                        </div>");
                }
                @endif
                @endforeach
                @endif
                @endforeach

                @endif
            }

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
                result.append("<div id='gallery' data-att='" + count + "' class=\"form-group \">\n" +
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


                $('.lfm').filemanager('image', "", "{{env('APP_URL')}}");


                /* remove each input splity */
                $('span').click(function () {
                    var id = $(this).attr('data-attrss');
                    var myDiv = $('div[data-att="' + id + '"]').remove();
                });

            });

            /* delete image */
            minImage.click(function (e) {
                e.preventDefault();
                $('#gallery:last-child').remove();
            });

            /* when edit product mode */

            @if(isset($findIdProducts) && !empty($findIdProducts) && count($findIdProducts->image) > 2)
            var count = 5;
            @foreach($findIdProducts->image as $key => $itemImages)
                    @if($itemImages->url != null && $key > 1 )
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
            $('.lfm').filemanager('image', "", "{{env('APP_URL')}}");


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
                    "                    <label for=\"video\" class=\"control-label col-lg-2\">ویدیو | ویس\n" +
                    "                    </label>\n" +
                    "                    <div class='row'>" +
                    "                       <div class=\"col-md-6\">\n" +
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
                    "                    </div>" +
                    "                     <div class='col-md-6'><div class='input-group'><input  class='form-control' type='text'name='video_title[]' placeholder='تیتر/عنوان ویدیو ...'> </div></div>" +
                    "               </div>\n" +
                    "<div class=\"col-md-3\">" +
                    "<span  data-attr-video=\"" + count + "\" class=\"btn btn-xs btn-danger minImageEx\">-</span>" +
                    "</div>" +
                    "                    <br>\n" +
                    "                </div>");
                $('.lfm1').filemanager('file', "", "{{env('APP_URL')}}");


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

            @if(isset($findIdProducts) && !empty($findIdProducts) && count($findIdProducts->video) > 0)
            var count = 5;
            @foreach($findIdProducts->video as $key => $itemVideo)
                    @if($itemVideo->url != null && $key > 0 )
                count++;
            result.append("<div id='video'  data-attr-videos=" + count + "  class=\"form-group \">\n" +
                "                    <label for=\"video\" class=\"control-label col-lg-2\">ویدیو\n" +
                "                    </label>\n" +
                "                    <div class='row'>" +
            "                           <div class=\"col-md-6\">\n" +
                "                        <div class=\"input-group\">\n" +
                "                            <span class=\"input-group-btn\">\n" +
                "                                                    <a data-input=\"videonail" + count + "\" data-preview=\"hold" + count + "\"\n" +
                "                                                       class=\"lfm1 btn btn-primary\">\n" +
                "                                                      <i class=\"fa fa-picture-o\"></i> انتخاب کنید\n" +
                "                                                    </a>\n" +
                "                                                  </span>\n" +
                "                            <input value=\" {{$itemVideo->url}} \" id=\"videonail" + count + "\" class=\"form-control\" type=\"text\"\n" +
                "                                   name=\"video[]\">\n" +
                "                        </div>\n" +
                "                    </div>" +"" +
                "<div class='col-md-6'><div class='input-group'><input  value='{{$itemVideo->title}}' class='form-control' type='text' name='video_title[]' placeholder='تیتر/عنوان ویدیو ...'> </div>"+
        "</div>"+
                "                           </div>\n" +
                "<div class=\"col-md-3\">" +
                "<span  data-attr-video=\"" + count + "\" class=\"btn btn-xs btn-danger minImageEx\">-</span>" +
                "</div>" +
                "<br><br><br>" +
                "<div class=\"row\">" +
                "<div class=\"col-md-12 text-center\">" +

                "<video width=\"200\" height=\"150\" controls>" +
                "<source src=\"{{$itemVideo->url }}\">" +
                "</video>" +

                "</div>" +
                "</div>" +
                "                    <br>\n" +
                "                </div>");
            $('.lfm1').filemanager('file', "", "{{env('APP_URL')}}");


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

    {{-- button add or delete catalog and add catalog input when edit product --}}
    <script>
        $(document).ready(function () {
            var plusCatalog = $('#plusCatalog');
            var minCatalog = $('#minCatalog');
            var result = $('.resultGalleryCatalog');
            var count = 5;

            /* add catalog */
            plusCatalog.click(function (e) {
                count++;
                e.preventDefault();
                result.append("<div id='catalog' data-attr-catalog=" + count + " class=\"form-group \">\n" +
                    "                    <label for=\"video\" class=\"control-label col-lg-2\">پی دی اف\n" +
                    "                    </label>\n" +
                    "                    <div class=\"col-md-6\">\n" +
                    "                        <div class=\"input-group\">\n" +
                    "                            <span class=\"input-group-btn\">\n" +
                    "                                                    <a data-input=\"catalognail" + count + "\" data-preview=\"hold" + count + "\"\n" +
                    "                                                       class=\"lfm1 btn btn-primary\">\n" +
                    "                                                      <i class=\"fa fa-picture-o\"></i> انتخاب کنید\n" +
                    "                                                    </a>\n" +
                    "                                                  </span>\n" +
                    "                            <input id=\"catalognail" + count + "\" class=\"form-control\" type=\"text\"\n" +
                    "                                   name=\"catalog[]\">\n" +
                    "                        </div>\n" +
                    "                    </div>\n" +
                    "<div class=\"col-md-3\">" +
                    "<span  data-attr-catalog=\"" + count + "\" class=\"btn btn-xs btn-danger minImageEx\">-</span>" +
                    "</div>" +
                    "                    <br>\n" +
                    "                </div>");
                $('.lfm1').filemanager('file', "", "{{env('APP_URL')}}");


                /* remove each input splity */
                $('span').click(function () {
                    var id = $(this).attr('data-attr-catalog');
                    var myDiv = $('div[data-attr-catalog="' + id + '"]').remove();
                });

            });

            /* delete catalog */
            minCatalog.click(function (e) {
                e.preventDefault();
                $('#catalog:last-child').remove();
            });

            /* when edit product mode */

            @if(isset($findIdProducts) && !empty($findIdProducts) && count($findIdProducts->catalog) > 0)
            var count = 5;
            @foreach($findIdProducts->catalog as $key => $itemCatalog)
                    @if($itemCatalog->url != null && $key > 0 )
                count++;
            result.append("<div id='catalog'  data-attr-catalog=" + count + "  class=\"form-group \">\n" +
                "                    <label for=\"catalog\" class=\"control-label col-lg-2\">پی دی اف\n" +
                "                    </label>\n" +
                "                    <div class=\"col-md-6\">\n" +
                "                        <div class=\"input-group\">\n" +
                "                            <span class=\"input-group-btn\">\n" +
                "                                                    <a data-input=\"catalognail" + count + "\" data-preview=\"hold" + count + "\"\n" +
                "                                                       class=\"lfm1 btn btn-primary\">\n" +
                "                                                      <i class=\"fa fa-picture-o\"></i> انتخاب کنید\n" +
                "                                                    </a>\n" +
                "                                                  </span>\n" +
                "                            <input value=\" {{$itemCatalog->url}} \" id=\"catalognail" + count + "\" class=\"form-control\" type=\"text\"\n" +
                "                                   name=\"catalog[]\">\n" +
                "                        </div>\n" +
                "                    </div>\n" +
                "<div class=\"col-md-3\">" +
                "<span  data-attr-catalog=\"" + count + "\" class=\"btn btn-xs btn-danger minImageEx\">-</span>" +
                "</div>" +
                "<br><br><br>" +
                "                    <br>\n" +
                "                </div>");
            $('.lfm1').filemanager('file', "", "{{env('APP_URL')}}");


            /* remove each input splity */
            $('span').click(function () {
                var id = $(this).attr('data-attr-catalog');
                var myDiv = $('div[data-attr-catalog="' + id + '"]').remove();
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

    {{-- add or remove details spect for attribute type value --}}
    <script>
        $(document).ready(function () {
            var plusDivs = $('#addAttributeTypeValue');
            var minDiv = $('#removeAttributeTypeValue');
            var results = $('#add-attribute-typeValue');
            var count = 105;

            plusDivs.click(function (e) {
                e.preventDefault();

                results.append(" <div id='divAttributeTypeValue' data-attr-attributeTypeValue='" + count + "' class=\"col-md-12 margin-top-attribute\">\n" +
                    "\n" +
                    "        <div class=\"col-md-2 div-line-height\">\n" +
                    "            <label for=\"attribute_type_id\"> مقدار خود را انتخاب کنید :</label>\n" +
                    "        </div>\n" +
                    "        <div class=\"col-md-2\">\n" +
                    "            <select data-id='" + count + "' name= 'attribute_type_value_id[]' \n" +
                    "                    class=\"form-control select-option  js-example-basic-multiple attribute_type_value_id" + count + " \"\n" +
                    "                   >\n" +
                    "                <option value=\"\">-- انتخاب کنید --</option>\n" +
                    "                @if(isset($allAttributeTypeValue) && $allAttributeTypeValue->count() > 0)\n" +
                    "                    @foreach($allAttributeTypeValue as $itemAttributeTypeValue)\n" +
                    "                        <option value=\"{{$itemAttributeTypeValue->id}}\">{{$itemAttributeTypeValue->value}}</option>\n" +
                    "                    @endforeach\n" +
                    "                @endif\n" +
                    "            </select>\n" +
                    "<img src='{{url('admin_theme/img/fill.png')}}' style='margin-bottom: 5px;margin-top: 5px' alt='' width='16px'>" +
                    "        </div>\n" +
                    "<div class=\"col-md-6\">" +
                    "<div id= 'resultAjaxAllAttributeTypeValue" + count + "' ></div>" +
                    "</div>" +
                    "        <div class=\"col-md-2 pull-left margin-top-1\">\n" +
                    "            <span  data-attr-attributeTypeValue='" + count + "' class=\"btn btn-xs btn-danger ss\" id=\"removeAttributeType\">-</span>\n" +
                    "        </div>\n" +
                    "        <br>\n" +
                    "        <br>\n" +
                    "\n" +
                    "        <div class=\"row\">\n" +
                    "            <div class=\"col-md-12\">\n" +
                    "\n" +
                    "                <div id='resultAjaxAllAttributeTypeValue" + count + "'></div>\n" +
                    "\n" +
                    "            </div>\n" +
                    "        </div>\n" +
                    "\n" +
                    "    </div>");

                $('.js-example-basic-multiple').select2();


                /* remove each input splity */
                $('span.ss').click(function () {
                    var id = $(this).attr('data-attr-attributeTypeValue');
                    var myDiv = $('div[data-attr-attributeTypeValue="' + id + '"]').remove();
                });


                $('select').change(function () {
                    var position = $(this).attr('data-id');

                    var selecteds = $(".attribute_type_value_id" + position + " option:selected").val();
                    //alert(selecteds);
                    if (selecteds != "") {
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            type: "post",
                            url: "{{route('panel.product.ajax_attributes_type_value')}}",
                            data: {
                                attributeTypeValue: selecteds,
                                position: position,
                                _token: CSRF_TOKEN
                            },

                            success: function (data) {

                                //console.log(data);
                                // console.log(data);
                                $("#resultAjaxAllAttributeTypeValue" + position).html(data.html);
                                CKEDITOR.replace('desc' + position, {
                                    height: 100,
                                    filebrowserImageBrowseUrl: route_prefix + '?type=Images',
                                    filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
                                    filebrowserBrowseUrl: route_prefix + '?type=Files',
                                    filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}'
                                });
                            },
                            error: function (error) {
                                // alert(error);
                                alert("لطفا چند لحظه دیگر وارد شوید.");
                            }
                        });

                    } else {
                        alert('لطفا مقدار خود را انتخاب کنید.');
                    }
                });


                count++;

            });

            /*minDiv.click(function (e) {
                e.preventDefault();
                $('#divAttributeTypeValue:last-child').remove();
            });*/
        });
    </script>

    {{-- variation change select option (detail) --}}
    <script>
        $(document).ready(function () {
            $('.attribute_type_value_id').change(function () {
                var selecteds = $(".attribute_type_value_id option:selected").val();
                var thiss = $(this).attr('data-id');

                if (selecteds != "") {

                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: "post",
                        url: "{{route('panel.product.ajax_attributes_type_value')}}",
                        data: {
                            attributeTypeValue: selecteds,
                            position: thiss,
                            _token: CSRF_TOKEN
                        },

                        success: function (data) {

                            // console.log("#resultAjaxAllAttributeTypeValue"+thiss);
                            // console.log(data);
                            $("#resultAjaxAllAttributeTypeValue" + thiss).html(data.html);
                            CKEDITOR.replace('desc0', {
                                height: 100,
                                filebrowserImageBrowseUrl: route_prefix + '?type=Images',
                                filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
                                filebrowserBrowseUrl: route_prefix + '?type=Files',
                                filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}'
                            });
                        },
                        error: function (error) {
                            //alert(error);
                            alert("لطفا چند لحظه دیگر وارد شوید.");
                        }
                    });

                } else {
                    alert('لطفا مقدار خود را انتخاب کنید.');
                }
            });
        });
    </script>

    {{-- when edit mode for variation (details) --}}
    <script>

        @if(isset($findIdProducts))

        var selectedss = $(".attribute_type_value_id option:selected").val();
        var edit = "edit";
        var product_id = <?= isset($findIdProducts) ? $findIdProducts->id : 0 ?>;

        if (selectedss != "") {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "{{route('panel.product.ajax_attributes_type_value')}}",

                data: {
                    attributeTypeValue: selectedss,
                    mode: edit,
                    product_id: product_id,
                    position: 0,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                    $("#resultAjaxAllAttributeTypeValue" + 0).html(data.html);
                    CKEDITOR.replace('descss', {
                        height: 100,
                        filebrowserImageBrowseUrl: route_prefix + '?type=Images',
                        filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
                        filebrowserBrowseUrl: route_prefix + '?type=Files',
                        filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}'
                    });
                },
                error: function (error) {
                    alert(error);
                    //alert("لطفا چند لحظه دیگر وارد شوید.");
                }
            });
        } else {
            // alert('لطفا مقدار خود را انتخاب کنید.');
        }

        @if(count($findIdProducts->variations) > 1)
        <?php $i = 1 ?>
        @foreach($findIdProducts->variations as $key => $itemVariation)


        @if($key > 0)
        var results = $('#add-attribute-typeValue');
        results.append("<br> <br> @if(isset($allAttributeTypeValue))\n" +
            "    @if(!empty($allAttributeTypeValue))\n" +
            "<div class='asd' data-attr-attributeTypeValues='<?=$i?>' >" +
            "<div class=\"col-md-2 div-line-height\">" +
            "<label for=\"attribute_type_id\"> مقدار خود را انتخاب کنید :</label>" +
            "</div>" +
            "<div  class=\"col-md-2\">" +
            "<select  data-id=\"<?=$i?>\" name=\"attribute_type_value_id[]\" class=\"form-control select-option attribute_type_value_id js-example-basic-multiple <?=$i?> \">" +
            "<option value=''>-- انتخاب کنید --</option>" +
                @if(isset($allAttributeTypeValue) && $allAttributeTypeValue->count() > 0)
                        @foreach($allAttributeTypeValue as $itemAttributeTypeValue)
                    "<option  {{ isset($findIdProducts) && $itemVariation->attribute_type_value_id ==  $itemAttributeTypeValue->id ? "selected" : null }} value=\"{{$itemAttributeTypeValue->id}}\">{{$itemAttributeTypeValue->value}}</option>" +
                @endforeach
                        @endif
                    "</select>" +
            "<img src=\"{{url('admin_theme/img/fill.png')}}\" style='margin-bottom: 5px;margin-top: 5px' alt='' width=\"16px\">" +
            "</div>" +
            "<div class='col-md-6'>" +
            "    <div id='div<?=$i?>' class=\"col-md-4\">\n" +
            "            <select name=\"attribute_type_id_related[]\"\n" +
            "                    class=\"form-control select-option attribute_type_value_related_id js-example-basic-multiple \">\n" +
            "                <option value=\"\">-- انتخاب کنید --</option>\n" +
            "                @foreach($allAttributeTypeValue as $itemAttributeTypeValue)\n" +
                @if($itemAttributeTypeValue->attribute_type_id > 1)
                    "                    <option {{ isset($findIdProducts) && isset($itemVariation->relatedVariations[0]) && $itemVariation->relatedVariations[0]->attribute_type_value_id == $itemAttributeTypeValue->id ? 'selected' : null  }} value=\"{{$itemAttributeTypeValue->id}}\">{{$itemAttributeTypeValue->value}}</option>\n" +
                @endif
                    "                @endforeach\n" +
            "            </select>\n" +
            "    </div>\n" +
            "    @endif\n" +
            "    @if(!empty($allAttributeTypeValue))\n" +
            "        <div class=\"col-md-4 afterAjax<?=$i?> \">\n" +
            "            @else\n" +
            "                <div class=\"col-md-12\">\n" +
            "                    @endif\n" +
            "                    <span class=\"\">\n" +
            "        <input id='prices<?=$i?>'  value=\"{{isset($findIdProducts) ? $itemVariation->price : null}}\" class=\"form-control input-height  \" min=\"0\" placeholder=\"قیمت\" type=\"number\" name=\"prices[]\">\n" +
            "        <img src=\"{{url('admin_theme/img/fill.png')}}\" style='margin-bottom: 5px;margin-top: 5px' alt=\"\" width=\"16px\">\n" +
            "        </span>\n" +
            "                </div>\n" +
            "\n" +
            "\n" +
            "                @if(!empty($allAttributeTypeValue))\n" +
            "                    <div class=\"col-md-4 afterAjax<?=$i?> \">\n" +
            "                        @else\n" +
            "                            <div class=\"col-md-12\">\n" +
            "                                @endif\n" +
            "                                <span class=\"\">\n" +
            "        <input id='countes<?=$i?>' value=\"{{isset($findIdProducts) ? $itemVariation->count : null}}\" class=\"form-control input-height <?=$i?> \" min=\"0\" placeholder=\"موجودی\" type=\"number\" name=\"countes[]\">\n" +
            "        <img src=\"{{url('admin_theme/img/fill.png')}}\" style='margin-bottom: 5px;margin-top: 5px' width=\"16px\" alt=\"\">\n" +
            "        </span>\n" +
            "                            </div>\n" +
            "</div>" +

            "        <div class=\"col-md-2 pull-left margin-top-1\">\n" +
            "            <span  data-attr-attributeTypeValue='<?=$i?>' class=\"btn btn-xs btn-danger sh\" id=\"removeAttributeTypeValues\">-</span>\n" +
            "        </div>\n" +
            "<br>" +
            "<br>" +
            "                            <div style='width: 784px;margin-top : 40px;' class=\"col-md-12 text-center col-md-push-2\">\n" +
            "        <textarea id='desc<?=$i?>' placeholder=\"توضیحات اضافی\" style=\"width: 755px;margin-right: -25px\" class=\"form-control <?=$i?> t\"\n" +
            "                  name=\"desc[]\"  cols=\"30\" rows=\"10\">{{ isset($findIdProducts) ? $itemVariation->description : "" }}</textarea>\n" +
            "                            </div>\n" +
            "</div>" +
            "    @endif ");
        // var valSelected =  $(this).val();

        CKEDITOR.replace('desc<?= $i?>', {
            height: 100,
            filebrowserImageBrowseUrl: route_prefix + '?type=Images',
            filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
            filebrowserBrowseUrl: route_prefix + '?type=Files',
            filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}'
        });

        @endif



        /*var position = $('.attribute_type_value_id').attr('data-id');*/
        /* when append check input */
        var position = <?=$i?>;

        //alert(position);

        if (position > 1) {
            var selectedValue = $(".<?=$i?> option:selected").val();

            if (selectedValue != undefined) {

                $.ajax({
                    type: "post",
                    url: "{{route('panel.product.ajax_attributes_variations')}}",
                    data: {
                        variations: selectedValue,
                        _token: CSRF_TOKEN
                    },
                    success: function (data) {
                        /* color */
                        if (data == 1) {
                            if (position > 1) {
                                // alert('#' + position + " select");
                                //$('#' + position + " select").val("");
                                $('#<?=$i?>' + " select");
                                $('#div<?=$i?>').css('display', 'block');
                                $('.afterAjax<?=$i?>').removeClass('col-md-6');
                                $('.afterAjax<?=$i?>').addClass('col-md-4');
                                //  $('#' + position).css('display', 'none');
                            }
                            /* size */
                        } else if (data == 2) {
                            if (position > 1) {
                                $('#<?=$i?>' + " select").val("");
                                $('#div<?=$i?>').css('display', 'none');
                                $('.afterAjax<?=$i?>').removeClass('col-md-4');
                                $('.afterAjax<?=$i?>').addClass('col-md-6');
                            }
                        }
                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });
            }
        }

        <?php $i++ ?>
        @endforeach
        @endif
        //$('.asd').load();
        @endif

        /* remove each input splity */
        $('span.sh').click(function () {
            var id = $(this).attr('data-attr-attributeTypeValue');
            var myDiv = $('div[data-attr-attributeTypeValues="' + id + '"]').remove();
        });

        /* change select option variation append */
        $('select').change(function () {
            var valSelected = $(this).val();
            //alert(valSelected);
            var position = $(this).attr('data-id');
            //alert(position);
            if (position > 0) {
                $.ajax({
                    type: "post",
                    url: "{{route('panel.product.ajax_attributes_variations')}}",
                    data: {
                        variations: valSelected,
                        _token: CSRF_TOKEN
                    },

                    success: function (data) {
                        if (data == 1) {
                            if (position > 0) {
                                $('#' + position + " select").val("");
                                $('#' + position).css('display', 'block');
                                $('#prices' + position).val("");
                                $('#countes' + position).val("");
                                $('#desc' + position).val("");
                                var idck = 'desc' + position;
                                CKEDITOR.instances[idck].updateElement();
                                CKEDITOR.instances[idck].setData('');
                                $('.afterAjax' + position).removeClass('col-md-6');
                                $('.afterAjax' + position).addClass('col-md-4');

                                //  $('#' + position).css('display', 'none');
                            }
                        } else if (data == 2) {
                            if (position > 0) {
                                $('#' + position + " select").val("");
                                $('#' + position).css('display', 'none');
                                $('#prices' + position).val("");
                                $('#countes' + position).val("");
                                $('#desc' + position).val("");
                                var idck = 'desc' + position;
                                CKEDITOR.instances[idck].updateElement();
                                CKEDITOR.instances[idck].setData('');
                                $('.afterAjax' + position).removeClass('col-md-4');
                                $('.afterAjax' + position).addClass('col-md-6');

                            }
                        }
                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });
            }
        });

    </script>

    {{-- when click on trash icon delete first input on details --}}
    <script>
        $('#removeAttributeTypeValue').click(function () {
            $('#firstInput').remove();
        });
    </script>


    {{-- trigger --}}
    <script>
        window.onscroll = function () {
            scrollFunction()
        };
        var addAttribute = $('#addAttributeTypeValue');

        function scrollFunction() {
            if (document.documentElement.scrollTop < 450) {
                addAttribute.removeClass('addTrigger');
                addAttribute.addClass('removeTrigger');
            } else {
                addAttribute.removeClass('removeTrigger');
                addAttribute.addClass('addTrigger');
            }
        }

    </script>
@endsection
