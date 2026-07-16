@extends('panel-old.layout.master')


@section('admin-css')
    <link href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{url('admin_theme/css/selected.css')}}"/>
    <link href="{{ url('admin_theme/css/select2.css') }}" rel="stylesheet"/>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.header-discount-create')
                    <a type="button" class="btn btn-xs btn-info pull-left top-left" data-toggle="modal"
                       href="{{ route('panel.discount.index') }}">برگشت
                    </a>
                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                </header>


                <div class="panel-body">
                    <div class="form">
                        @if(isset($discount) && $discount->count() > 0 )
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  action="{{route('panel.discount.update',$discount->id)}}"
                                  enctype="multipart/form-data">
                                <input type="hidden" name="_method" value="PATCH">
                                <input type="hidden" name="type_old" value="{{ $discount->type }}">
                                @if(isset($discount) && $discount->count() > 0 )
                                    <input type="hidden" name="id"
                                           value="{{isset($discount) && $discount->count() > 0 ? $discount->id : null }}"
                                           class="id">
                                @endif
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          action="{{route('panel.discount.store')}}" enctype="multipart/form-data">
                                        @endif
                                        @csrf

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.title')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="cname" name="title" minlength="2"
                                                       type="text"
                                                       value="{{isset($discount) && $discount->count() > 0 ? $discount->title : null }}"
                                                       required/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">فروشنده</label>
                                            <div class="col-lg-10">
                                                @if(isset($users) && count($users) > 0)
                                                <select name="user_id" id="" class="form-control select-option requester">
                                                    <option value="">-- درخواست دهنده --</option>
                                                    @foreach($users as $user)
                                                        <option value="{{$user->id}}" {{ isset($discount) && $discount->user_id == $user->id ? "selected" : null  }}> {{ $user->name }} {{ $user->family }} - {{ $user->email }} </option>
                                                    @endforeach
                                                </select>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="cname"
                                                   class="control-label col-lg-2">@lang('cms.discount-desc-note')</label>
                                            <div class="col-lg-10">
                                                <textarea class="form-control"
                                                          name="description">{{isset($discount) && $discount->count() > 0 ? $discount->description : null }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="cname"
                                                   class="control-label col-lg-2">@lang('cms.based-on')</label>
                                            <div class="col-lg-10">
                                                <select name="baseon" class="form-control select-option">
                                                    <option value="">@lang('cms.choose')</option>
                                                    @foreach(\App\Utility\DiscountType::baseOnEach() as $key=>$value)
                                                        <option
                                                            value="{{ $key }}" {{isset($discount) && $discount->count() > 0 && $discount->baseon == $key  ? "selected"  : null }} >{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.value')</label>
                                            <div class="col-lg-10">
                                                <input name="cent" class="form-control expire_date_value" type="number"
                                                       value="{{isset($discount) && $discount->count() > 0 ? $discount->cent : null }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="cname"
                                                   class="control-label col-lg-2">@lang('cms.typeDiscount')</label>
                                            <div class="col-lg-10">
                                                <select name="discount_type" class="form-control distype select-option">
                                                    <option value="-1">@lang('cms.choose')</option>
                                                    @foreach(\App\Utility\DiscountType::DiscountTypeEach() as $key=>$value)
                                                        <option
                                                            value="{{ $key }}" {{isset($discount) && $discount->count() > 0 && $discount->type  == $key  ? "selected"  : null }} >{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div id="typediscount">
                                        </div>
                                        <br>


                                        <div id="result">
                                        </div>
                                        <br>


                                        <div class="form-group userCountAjax" id="count_user">
                                            <label for="cname"
                                                   class="control-label col-lg-2">@lang('cms.discount-count-user')</label>
                                            <div class="col-lg-10">
                                                <input class="form-control"  name="count_user" type="number"
                                                       value="{{isset($discount) && $discount->count() > 0 ? $discount->count_user : null }}"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="cname"
                                                   class="control-label col-lg-2">@lang('cms.status')</label>
                                            <div class="col-lg-10">
                                                <select class=" form-control select-option" id="lang" name="status">
                                                    <option value="">@lang('cms.choose-status')</option>
                                                    @foreach(App\Utility\Status::Status() as $key=> $value)
                                                        <option
                                                            value="{{ $key }}" {{isset($discount) && $discount->count() > 0 && $discount->status  == $key  ? "selected"  : null }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>


                                        @if(isset($discount) && !empty($discount))
                                            <div class="form-group">
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-warning pull-left" type="submit"
                                                           value="@lang('cms.edit')">
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-success pull-left" type="submit"
                                                           value="@lang('cms.save')">
                                                </div>
                                            </div>
                                        @endif
                                    </form>
                    </div>

                </div>

            </section>
        </div>


    </div>
@endsection
@section('admin-js')
    <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js" type="text/javascript"></script>
    <script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"
            type="text/javascript"></script>
    <script src="{{url('admin_theme/js/select2.js')}}"></script>

    {{--Start Of calender --}}


    <script>
        $(document).ready(function () {
            //hide form group amazimg | Limited to products
            $('.amazing').hide();
            $(".datepicker").persianDatepicker({
                format: 'YYYY/MM/DD H:m:s',
                // initialValue: false,
                timePicker: {
                    enabled: true,
                    meridiem: {
                        enabled: true
                    }
                }
            });
        });
    </script>
    {{-- End Of calender --}}

    {{-- Start Of multi selected --}}
    <script>
        $(document).ajaxStop(function () {
            $('.js-example-basic-multiple').select2();
        });

        $(".type").change(function () {
            var selecteds = $(".type option:selected").val();
            var requester =$('.requester').val();
            if (!requester)
            {
                alert('لطفا درخواست دهنده را انتخاب نمایید');
            }
        });

    </script>
    {{-- End Of multi selected --}}

    @if(isset($discount) && $discount->count() > 0)
        {{-- Edit script --}}
        <script type="text/javascript">
            $(document).ready(function () {

                var discountType = $('.distype').val();
                var sellerId = $('.requester').val();

                var id = $('.id').val();
                $('.morph').remove();

                if (discountType >= 0) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: "post",
                        url: "{{ route('get.All.Type.Discount') }}",
                        data: {
                            type: discountType,
                            id: id,
                            sellerId : sellerId ,
                            _token: CSRF_TOKEN
                        },
                        success: function (data) {
                            console.log(data);
                            if (data instanceof Object) {
                                $("#typediscount").html(data.html);
                                $(".datepicker").persianDatepicker({
                                    format: 'YYYY/MM/DD H:m:s',
                                    // initialValue: false,
                                    timePicker: {
                                        enabled: true,
                                        meridiem: {
                                            enabled: true
                                        }
                                    }
                                });

                            } else {
                                alert(data.html);
                            }

                        },
                        error: function (error) {
                            alert("لطفا چند لحظه دیگر وارد شوید.");
                        }
                    });
                }
            });

            $('.distype').change(function () {
                var discountType = $(this).val();
                $('.morph').remove();
                if (discountType =={{ \App\Utility\DiscountType::COUNTBUY }}) {
                    $("#count_user").css('display','none');
                }else{
                    $("#count_user").css('display','block');
                }

                if (discountType >= 0) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: "post",
                        url: "{{ route('get.All.Type.Discount') }}",
                        data: {
                            type: discountType,
                            _token: CSRF_TOKEN
                        },

                        success: function (data) {
                            console.log(data);
                            if (data instanceof Object) {
                                $("#typediscount").html(data.html);
                                $(".datepicker").persianDatepicker({
                                    format: 'YYYY/MM/DD H:m:s',
                                    // initialValue: false,
                                    timePicker: {
                                        enabled: true,
                                        meridiem: {
                                            enabled: true
                                        }
                                    }
                                });

                            } else {
                                alert(data.html);
                            }

                        },
                        error: function (error) {
                            // alert(error);
                            alert("لطفا چند لحظه دیگر وارد شوید.");

                        }
                    });
                }
            });
            var i = 0;
            $(document).ajaxStop(function (event, request, settings) {
                /***Load Value in Select Option***/
                if (i == 0) {
                    var type = $('.type').val();
                    var sellerId = $('.requester').val();

                    if (!sellerId)
                    {
                        alert('لطفا درخواست دهنده را انتخاب نمایید');
                    }

                    var category =<?= \App\Utility\DiscountType::category ?>;
                    var brand =<?= \App\Utility\DiscountType::brand ?>;
                    var product =<?= \App\Utility\DiscountType::product ?>;
                    var user =<?= \App\Utility\DiscountType::user ?>;
                    var role =<?= \App\Utility\DiscountType::role ?>;

                    if (type != "") {
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            type: "post",
                            url: "{{ route('get.All.TypeOn.Discount') }}",
                            data: {
                                type: type,
                                sellerId: sellerId,
                                @if(isset($discount) && $discount->count() > 0) id:{{ $discount->id  }}, @endif
                                _token: CSRF_TOKEN
                            },

                            success: function (data) {
                                if (data instanceof Object) {
                                    $("#result").html(data.html);
                                } else {
                                    alert(data);
                                }

                            },
                            error: function (error) {
                                alert("لطفا چند لحظه دیگر وارد شوید.");

                            }
                        });
                    }
                    /**Load Value in Select Option**/

                    $('.type').change(function () {
                        var type = $(this).val();
                        var sellerId = $('.requester').val();
                        if (!sellerId)
                        {
                            alert('لطفا درخواست دهنده را انتخاب نمایید');
                        }

                        var category =<?= \App\Utility\DiscountType::category ?>;
                        var brand =<?= \App\Utility\DiscountType::brand ?>;
                        var product =<?= \App\Utility\DiscountType::product ?>;
                        var user =<?= \App\Utility\DiscountType::user ?>;
                        var role =<?= \App\Utility\DiscountType::role ?>;

                        if (type != "") {
                            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                            $.ajax({
                                type: "post",
                                url: "{{ route('get.All.TypeOn.Discount') }}",
                                data: {
                                    type: type,
                                    sellerId : sellerId ,
                                    @if(isset($discount) && $discount->count() > 0) id:{{ $discount->id  }}, @endif
                                    _token: CSRF_TOKEN
                                },

                                success: function (data) {
                                    if (data instanceof Object) {
                                        $("#result").html(data.html);
                                    } else {
                                        alert(data);
                                    }

                                },
                                error: function (error) {
                                    alert("لطفا چند لحظه دیگر وارد شوید.");

                                }
                            });
                        }


                    });
                    i = 1;
                }

                $('.type').change(function () {
                    var type = $(this).val();
                    var sellerId = $('.requester').val();
                    if (!sellerId)
                    {
                        alert('لطفا درخواست دهنده را انتخاب نمایید');
                    }
                    var category =<?= \App\Utility\DiscountType::category ?>;
                    var brand =<?= \App\Utility\DiscountType::brand ?>;
                    var product =<?= \App\Utility\DiscountType::product ?>;
                    var user =<?= \App\Utility\DiscountType::user ?>;
                    var role =<?= \App\Utility\DiscountType::role ?>;

                    if (type != "") {
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            type: "post",
                            url: "{{ route('get.All.TypeOn.Discount') }}",
                            data: {
                                type: type,
                                sellerId : sellerId ,
                                _token: CSRF_TOKEN
                            },

                            success: function (data) {
                                if (data instanceof Object) {
                                    $("#result").html(data.html);
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


                });
            });
        </script>
        {{-- Edit script --}}
    @else
        {{-- Create script --}}
        <script type="text/javascript">
            $('.distype').change(function () {

                var discountType = $(this).val();
                if(discountType >= 0){
                $('.morph').remove();
                if (discountType =={{ \App\Utility\DiscountType::COUNTBUY }}) {
                    $("#count_user").css('display','none');
                }else{
                    $("#count_user").css('display','block');
                }
                if (discountType != "") {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: "post",
                        url: "{{ route('get.All.Type.Discount') }}",

                        data: {
                            type: discountType,
                            _token: CSRF_TOKEN,
                        },


                        success: function (data) {
                            console.log(data);
                            if (data instanceof Object) {
                                $("#typediscount").html(data.html);
                                $(".datepicker").persianDatepicker({
                                    format: 'YYYY/MM/DD H:m:s',
                                    // initialValue: false,
                                    timePicker: {
                                        enabled: true,
                                        meridiem: {
                                            enabled: true
                                        }
                                    }
                                });

                            } else {
                                alert(data.html);
                            }

                        },
                        error: function (error) {
                            // alert(error);
                            alert("لطفا چند لحظه دیگر وارد شوید.");

                        }
                    });
                }
                }
            });

            $(document).ajaxStop(function (event, request, settings) {
                $('.type').change(function () {
                    var type = $(this).val();
                    var sellerId = $('.requester').val();
                    if (!sellerId)
                    {
                        alert('لطفا درخواست دهنده را انتخاب نمایید');
                    }
                    var category =<?= \App\Utility\DiscountType::category ?>;
                    var brand =<?= \App\Utility\DiscountType::brand ?>;
                    var product =<?= \App\Utility\DiscountType::product ?>;
                    var user =<?= \App\Utility\DiscountType::user ?>;
                    var role =<?= \App\Utility\DiscountType::role ?>;

                    if (type != "") {
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            type: "post",
                            url: "{{ route('get.All.TypeOn.Discount') }}",
                            data: {
                                sellerId: sellerId,
                                type: type,
                                _token: CSRF_TOKEN
                            },

                            success: function (data) {
                                if (data instanceof Object) {
                                    $("#result").html(data.html);
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
                });

            });
        </script>
        {{-- Create script --}}
    @endif

    {{--  when change requester update type --}}
    <script>
        $('.requester').change(function () {
            var distypes =  $('.distype').val('-1');
            $('.alltype').remove();
            $('.morph').remove();
        });
    </script>


    {{-- when change discount type and discount type = 6 hidden count user --}}
    <script>
        @if(isset($discount) && $discount->count() > 0)
                var discountType = $('.distype').val();
                if(discountType == 6){
                    $('.userCountAjax').css('display','none');
                }else{
                    $('.userCountAjax').css('display','block');
                }
            @else
        @endif
    </script>
    <script>
        function selectAll()
        {
            $('.p-all option').prop('selected', true);
        }
    </script>
@endsection
