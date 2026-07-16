@extends('panel-old.layout.master')


@section('admin-css')
    <link rel="stylesheet" href="{{url('admin_theme/css/calender/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{url('admin_theme/css/selected.css')}}"/>
    <link href="{{ url('admin_theme/css/select2.css') }}" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <div class="col-lg-12">

            <section class="panel">
                <header class="panel-heading">

                    @if(isset($findIdProducts) && isset($userVariationId))
                        <div class="col-md-12">

                        </div>
                        @lang('cms.edit-request-product') :
                        <span class="btn btn-xs btn-darkgreen margin-top-1">@lang('cms.category') : {{$findIdProducts->categories[0]->title}}</span>
                        <span class="btn btn-xs btn-wood margin-top-1">@lang('cms.brand-2') : {{$findIdProducts->brand->title}}</span>
                        <span class="btn btn-xs btn-primary margin-top-1 product-link">
                            @lang('cms.product') : ‌{{ $findIdProducts->title}}
                        </span>
                        {{-- todo store name   --}}
                        <span class="btn btn-xs btn-indianred margin-top-1">@lang('cms.customer') :
                            <?php $user =  \App\Utility\getUser::getUser($userVariationId); ?>
                            {{  isset($user) && !empty($user) ? $user->name : null  }}
                        </span>
                        @else
                        @lang('cms.header-create-new-product')
                    @endif


                    <a type="button" class="btn btn-xs btn-info pull-left top-left" data-toggle="modal"
                       href="{{route('panel.customer',['id'=>$findProduct->id])}}">@lang('cms.back')
                    </a>
                    <br>
                    <br>
                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')

                <div class="panel-body">
                    <div class="form">

                        <ul class="nav nav-tabs">
                            <li id="details-step" class="active"><a data-toggle="tab" class="details-tabs"
                                                     href="#detail">@lang('cms.details')</a>
                            </li>
                        </ul>
                        @if(isset($findIdProducts) )
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.customer.update' , ['id' => $findIdProducts->id ])}}">
                                {{method_field("PATCH")}}

                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm"
                                          method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.customer.store')}}">
                                        @endif
                                        @csrf
                                        <div class="tab-content">

                                            <div id="detail" class="tab-pane fade in active">
                                                @include('panel-old.customer.partials.details')
                                            </div>

                                        </div>
                                        <input type="hidden" name="product_id" value="{{$findProduct->id}}">
                                    </form>

                    </div>

                </div>

            </section>
        </div>
    </div>




@endsection



@section('admin-js')
    <script src="{{url('admin_theme/js/calender/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{url('admin_theme/js/calender/bootstrap-datepicker.fa.min.js')}}"></script>
    <script src="{{ url('admin_theme/js/select2.js') }}"></script>
    <script src="{{ url('admin_theme/js/selected.js')}}"></script>


    {{-- select 2 --}}
    <script>
        $(document).ajaxComplete(function () {
            $('.js-example-basic-multiple').select2();
        });
        $('.js-example-basic-multiple').select2();
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
                                CKEDITOR.replace('desc' + position);
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
                            CKEDITOR.replace('desc0');
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
        var user_id = <?= isset($userVariationId) ? $userVariationId : "null" ?>

        if (selectedss != "") {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "{{route('panel.customer.ajax_attributes_type_value')}}",

                data: {
                    attributeTypeValue: selectedss,
                    mode: edit,
                    product_id: product_id,
                    position: 0,
                    user_id : user_id ,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                    $("#resultAjaxAllAttributeTypeValue" + 0).html(data.html);
                    CKEDITOR.replace('descss');
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
            "                  name=\"desc[]\"  cols=\"30\" rows=\"10\">{!! isset($findIdProducts) ? $itemVariation->description : "" !!}</textarea>\n" +
            "                            </div>\n" +
            "</div>" +
            "    @endif ");
        // var valSelected =  $(this).val();

        CKEDITOR.replace('desc<?= $i?>');

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




