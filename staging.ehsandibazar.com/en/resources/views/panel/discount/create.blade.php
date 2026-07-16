@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.discount.index')
                                <a href="{{ route('panel.discount.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> فهرست </a>
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
                                @can('panel.discount.index')
                                    <li>
                                        <a href="{{ route('panel.discount.index')  }}" style="font-size: 10px">فهرست</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                    <br>


                    @if(isset($discount) && $discount->count() > 0 )
                        <form class="form-horizontal" method="post"
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
                                <form class="form-horizontal" method="post"
                                      action="{{route('panel.discount.store')}}" enctype="multipart/form-data">
                                    @endif
                                    @csrf


                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">@lang('cms.title')
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input class="form-control" id="cname" name="title" minlength="2"
                                                           type="text"
                                                           value="{{isset($discount) && $discount->count() > 0 ? $discount->title : null }}"
                                                           required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(isset($users) && count($users) > 0)
                                        {{-- users --}}
                                        <div class="row clearfix">
                                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                <label for="status">فروشنده
                                                    <span class="redAlert">*</span>
                                                </label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                <div class="form-group">
                                                    <div class="form-group default-select select2Style">
                                                        <select class="form-group select-option select2 requester"
                                                                name="user_id">
                                                            <option value="">-- درخواست دهنده --</option>
                                                            @foreach($users as $user)
                                                                <option value="{{$user->id}}" {{ isset($discount) && $discount->user_id == $user->id ? "selected" : null  }}> {{ $user->name }} {{ $user->family }}
                                                                    - {{ $user->email }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">@lang('cms.discount-desc-note')
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                     <textarea class="form-control ckeditor"
                                                               name="description">{{isset($discount) && $discount->count() > 0 ? $discount->description : null }}</textarea>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="status">@lang('cms.based-on')
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-group default-select select2Style">
                                                    <select class="form-group select-option select2" name="baseon">
                                                        <option value="">@lang('cms.choose')</option>
                                                        @foreach(\App\Utility\DiscountType::baseOnEach() as $key=>$value)
                                                            <option
                                                                    value="{{ $key }}" {{isset($discount) && $discount->count() > 0 && $discount->baseon == $key  ? "selected"  : null }} >{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">@lang('cms.value')
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="cent" class="form-control expire_date_value"
                                                           type="number"
                                                           value="{{isset($discount) && $discount->count() > 0 ? $discount->cent : null }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="status">@lang('cms.typeDiscount')
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-group default-select select2Style">
                                                    <select class="form-group distype select-option select2"
                                                            name="discount_type">
                                                        <option value="-1">@lang('cms.choose')</option>
                                                        @foreach(\App\Utility\DiscountType::DiscountTypeEach() as $key=>$value)
                                                            <option
                                                                    value="{{ $key }}" {{isset($discount) && $discount->count() > 0 && $discount->type  == $key  ? "selected"  : null }} >{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="typediscount">
                                        </div>
                                    <div id="result">
                                        </div>

                                    <div class="row clearfix userCountAjax" id="count_user">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">@lang('cms.discount-count-user')
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input class="form-control"  name="count_user" type="number"
                                                           value="{{isset($discount) && $discount->count() > 0 ? $discount->count_user : null }}"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="status">@lang('cms.status')
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-group default-select select2Style">
                                                    <select class="form-group select-option select2" name="status">
                                                        <option value="">@lang('cms.choose-status')</option>
                                                        @foreach(App\Utility\Status::Status() as $key=> $value)
                                                            <option
                                                                    value="{{ $key }}" {{isset($discount) && $discount->count() > 0 && $discount->status  == $key  ? "selected"  : null }}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(isset($find))
                                        @can('panel.discount.update')
                                            {{-- button --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                    <button type="submit" class="btn-hover color-1 pull-left">ویرایش
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
                                    @else
                                        @can('panel.discount.store')
                                            {{-- button --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                    <button type="submit" class="btn-hover color-1 pull-left">ذخیره
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
                                    @endif
                                </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('admin-js')
    @include('panel.layout.ckjs')
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
            var requester = $('.requester').val();
            if (!requester) {
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
                            sellerId: sellerId,
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
                    $("#count_user").css('display', 'none');
                } else {
                    $("#count_user").css('display', 'flex');
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

                    if (!sellerId) {
                        alert('لطفا درخواست دهنده را انتخاب نمایید');
                    }

                    var category = <?= \App\Utility\DiscountType::category ?>;
                    var brand = <?= \App\Utility\DiscountType::brand ?>;
                    var product = <?= \App\Utility\DiscountType::product ?>;
                    var user = <?= \App\Utility\DiscountType::user ?>;
                    var role = <?= \App\Utility\DiscountType::role ?>;

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
                        if (!sellerId) {
                            alert('لطفا درخواست دهنده را انتخاب نمایید');
                        }

                        var category = <?= \App\Utility\DiscountType::category ?>;
                        var brand = <?= \App\Utility\DiscountType::brand ?>;
                        var product = <?= \App\Utility\DiscountType::product ?>;
                        var user = <?= \App\Utility\DiscountType::user ?>;
                        var role = <?= \App\Utility\DiscountType::role ?>;

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


                    });
                    i = 1;
                }

                $('.type').change(function () {
                    var type = $(this).val();
                    var sellerId = $('.requester').val();
                    if (!sellerId) {
                        alert('لطفا درخواست دهنده را انتخاب نمایید');
                    }
                    var category = <?= \App\Utility\DiscountType::category ?>;
                    var brand = <?= \App\Utility\DiscountType::brand ?>;
                    var product = <?= \App\Utility\DiscountType::product ?>;
                    var user = <?= \App\Utility\DiscountType::user ?>;
                    var role = <?= \App\Utility\DiscountType::role ?>;

                    if (type != "") {
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            type: "post",
                            url: "{{ route('get.All.TypeOn.Discount') }}",
                            data: {
                                type: type,
                                sellerId: sellerId,
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
                if (discountType >= 0) {
                    $('.morph').remove();
                    if (discountType =={{ \App\Utility\DiscountType::COUNTBUY }}) {
                        $("#count_user").css('display', 'none');
                    } else {
                        $("#count_user").css('display', 'flex');
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
                    if (!sellerId) {
                        alert('لطفا درخواست دهنده را انتخاب نمایید');
                    }
                    var category = <?= \App\Utility\DiscountType::category ?>;
                    var brand = <?= \App\Utility\DiscountType::brand ?>;
                    var product = <?= \App\Utility\DiscountType::product ?>;
                    var user = <?= \App\Utility\DiscountType::user ?>;
                    var role = <?= \App\Utility\DiscountType::role ?>;

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
            var distypes = $('.distype').val('-1');
            $('.alltype').remove();
            $('.morph').remove();
        });
    </script>


    {{-- when change discount type and discount type = 6 hidden count user --}}
    <script>
        @if(isset($discount) && $discount->count() > 0)
        var discountType = $('.distype').val();
        if (discountType == 6) {
            $('.userCountAjax').css('display', 'none');
        } else {
            $('.userCountAjax').css('display', 'flex');
        }
        @else
        @endif
    </script>
    <script>
        function selectAll() {
            $('.p-all option').prop('selected', true);
        }
    </script>
@endsection
