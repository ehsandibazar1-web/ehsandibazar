@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')

    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.category.create')
                                <a href="{{ route('panel.profile.address')  }}"
                                   class="btn btn-outline-default btn-border-radius"> لیست آدرس ها </a>
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
                                @can('panel.profile.index')
                                    <li>
                                        <a href="{{ route('panel.profile.index')  }}" style="font-size: 10px">
                                            پروفایل کاربری
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    @include('generals.allErrors')
                    @if(isset($findAddress) && !empty($findAddress))
                        <form action="{{ route('panel.profile.addressUpdate' , ['id' => $findAddress->id])  }}"
                              method="post">
                            {{ method_field('PATCH')  }}
                            @else
                                <form action="{{ route('panel.profile.addressStore')  }}" method="post">
                                    @endif

                                    @csrf

                                    {{-- name --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="name">نام گیرنده
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="name" type="text" id="name" class="form-control"
                                                           placeholder="نام گیرنده را وارد نمایید"
                                                           value="{{ isset($findAddress) ? $findAddress->name : old('name') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- mobile --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="mobile">موبایل
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="mobile" type="number" id="mobile" class="form-control"
                                                           placeholder="موبایل گیرنده را وارد نمایید"
                                                           value="{{ isset($findAddress) ? $findAddress->mobile : old('mobile') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- tell --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="tell">تلفن ثابت

                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="tell" type="number" id="tell" class="form-control"
                                                           placeholder="تلفن ثابت گیرنده را وارد نمایید"
                                                           value="{{ isset($findAddress) ? $findAddress->tell : old('tell') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- province  --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="province_id">استان
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select name="province_id" id="province_id">
                                                        <option value="">-- استان خود را وارد نمایید --</option>
                                                        @if(isset($province_id) && !empty($province_id))
                                                            @foreach($province_id as $itemProvince)
                                                                <option
                                                                    value="{{ $itemProvince->id }}">{{ $itemProvince->name  }}</option>
                                                            @endforeach
                                                        @endif

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- city  --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="city_id">شهر
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <div class="select-wrapper ajaxCity_select">
                                                        <select name="city_id" id="city_id">
                                                            <option value="">-- شهر خود را وارد نمایید --</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- full address --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="fullAddress">آدرس کامل
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <textarea name="fullAddress" id="fullAddress" cols="30"
                                                              rows="10">{{isset($findAddress) ? $findAddress->fullAddress : old('fullAddress')}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- postal code--}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="postal_code">کد پستی

                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="postal_code" type="text" id="postal_code"
                                                           class="form-control"
                                                           placeholder="کد پستی گیرنده را وارد نمایید"
                                                           value="{{ isset($findAddress) ? $findAddress->postal_code : old('postal_code') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(isset($findAddress) && !empty($findAddress))
                                        <button type="submit" class="btn btn-xs btn-warning pull-left">ویرایش آدرس
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-xs btn-success pull-left ">ایجاد آدرس
                                        </button>
                                    @endif

                                </form>

                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->

@endsection

@section('admin-js')
    <script>

        $('#province_id').change(function (e) {
            e.preventDefault();
            var province_id = $(this).val();

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "{{route('panel.profile.ajaxGetCity')}}",
                data: {
                    provinceID: province_id,
                    _token: CSRF_TOKEN
                },

                success: function (data) {

                    $('.ajaxCity_select').html(data.html);

                    if (data.status == 100) {
                        Swal.fire({
                            title: "خطا!",
                            text: data.message,
                            icon: "error",
                            button: "تایید",
                        });
                    }

                },

                error: function (data) {
                    toast("لطفا چند لحظه دیگر مجددا امتحان فرمایید");
                },

            });

        });

    </script>
@endsection
