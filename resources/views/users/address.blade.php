@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
@endsection
@section('content')
    <main class="profile-user-page default">
        <div class="container wrapper default">
            <div class="row">
                <div class="profile-page col-xl-9 col-lg-8 col-md-12 order-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="col-12">
                                <h1 class="title-tab-content">آدرس ها</h1>
                            </div>
                            <div class="content-section default">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 class="title-tab-content">مدیریت آدرس ها</h1>
                                    </div>
                                </div>
                                @if(isset($address) && !empty($address))
                                    <div class="shopping-page">
                                        <main class="cart-page default">
                                            <div class="cart-page-content col-xl-12 col-lg-12 col-md-12 order-1">
                                                <div class="page-content default">
                                                    @foreach($address as $key => $itemAddress)
                                                        <div class="address-section">
                                                            <label class="checkout-contact" style="width: 100%">
                                                                <div class="checkout-contact-content">
                                                                    <ul class="checkout-contact-items">
                                                                        <li class="checkout-contact-item">
                                                                            گیرنده:
                                                                            <span class="full-name">{{ $itemAddress->name }}</span>
                                                                            {{--                                                            @if(\Illuminate\Support\Facades\Auth::user()->isCustomer())--}}
                                                                            {{--                                                                <a class="checkout-contact-btn-edit">اصلاح این آدرس</a>--}}
                                                                            {{--                                                            @endif--}}
                                                                        </li>
                                                                        <li class="checkout-contact-item">
                                                                            <div class="checkout-contact-item checkout-contact-item-mobile">
                                                                                شماره تماس:
                                                                                <span class="mobile-phone">{{ $itemAddress->mobile  }}</span>
                                                                            </div>
                                                                            <div class="checkout-contact-item-message">
                                                                                کد پستی:
                                                                                <span class="post-code"> {{ $itemAddress->postal_code  }}</span>
                                                                            </div>
                                                                            <br>
                                                                            استان
                                                                            <span class="state">{{ $itemAddress->province->name }}</span>
                                                                            ، ‌شهر
                                                                            <span class="city">{{ $itemAddress->city->name }}</span>
                                                                            ،
                                                                            <span class="address-part">{{ $itemAddress->fullAddress }}</span>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="checkout-contact-badge">
                                                                        <i class="now-ui-icons ui-1_check"></i>
                                                                    </div>
                                                                </div>
                                                                <a href="{{ route('users.delete.address',$itemAddress->id) }}" class="checkout-contact-location remove-address">حذف</a>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </main>
                                    </div>
                                @endif
                                <hr class="mb-5">
                                @include('generals.allErrors')
                                <form class="form-account" action="{{ route('users.panel.storeAddress') }}"
                                      method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">استان</div>
                                            <div class="form-account-row">
                                                <select name="province_id" class="input-field text-right province"
                                                        id="form-stacked-select">
                                                    <option>استان را انتخاب کنید</option>
                                                    @foreach($provinces as $province)
                                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6" id="result-ajax">
                                            <div class="form-account-title">شهر</div>
                                            <div class="form-account-row">
                                                <select name="city_id" class="input-field text-right"
                                                        id="form-stacked-select city">
                                                    <option value="">شهر را انتخاب کنید</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">آدرس تکمیلی</div>
                                            <div class="form-account-row">
                                                <input class="input-field text-right" type="text" placeholder="آدرس"
                                                       name="fullAddress" onkeypress="text(this)" onchange="text(this)"
                                                       keyup="text(this)" keydown="text(this)" value=""></div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">نام</div>
                                            <div class="form-account-row">
                                                <input class="input-field text-right" type="text" name="name" value=""
                                                       onkeypress="text(this)" onchange="text(this)" keyup="text(this)"
                                                       keydown="text(this)" placeholder="نام">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title"> موبایل</div>
                                            <div class="form-account-row">
                                                <input class="input-field text-right" type="number" name="mobile"
                                                       value="" placeholder="تلفن همراه یا موبایل"></div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">تلفن ثابت</div>
                                            <div class="form-account-row">
                                                <input class="input-field text-right" type="number" name="tell" value=""
                                                       placeholder="تلفن ثابت">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">کد پستی</div>
                                            <div class="form-account-row">
                                                <input class="input-field text-right" type="text" name="postal_code"
                                                       value="" placeholder="کد پستی منزل یا محل کار"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button class="btn btn-default btn-lg">ذخیره</button>
                                        <a href="{{ route('users.dashboard.index') }}" class="btn btn-default btn-lg">انصراف</a>
                                    </div>
                                </form>
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
    <script>
        $('.province').change(function (e) {
            var province_id = $(this).val();
            e.preventDefault();
            /* start ajax */
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: "post",
                url: "{{route('users.panel.profile.ajaxCity')}}",

                data: {isRequestIDChange: province_id, _token: CSRF_TOKEN},
                success: function (data) {
                    $('#result-ajax').html(data.html);
                },
                error: function (error) {
                    //alert(error);
                    alert("لطفا چند لحظه دیگر امتحان نمایید")
                }
            });
            /* end ajax */
        });
    </script>
@endsection
