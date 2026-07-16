@extends('site.layout.master')
@section('site.css')
@include('users.layouts.partials.styles')
@endsection
@section('content')
    <div class="wrapper default">
        <div class="container">
            <div class="row">
                <div class="main-content col-12 col-md-7 col-lg-5 mx-auto">
                    <div class="account-box">
                        <!--<a href="#" class="logo">-->
                        <!--    <img src="assets/img/logo.png" alt="">-->
                        <!--</a>-->
                        <div class="account-box-title text-right">ورود به {{ env('SITE_NAME_FA') }}</div>
                        <div class="account-box-content">
                            @include('generals.allErrors')
                            @include('generals.sessionMessage')
                            <form action="{{ route('login') }}" method="post" class="form-account">
                                @csrf
                                <div class="form-account-title"> شماره موبایل</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="number" name="mobile" value="{{ old('mobile') }}"
                                           placeholder="  شماره موبایل خود را وارد نمایید">
                                </div>
                                <div class="form-account-title">رمز عبور
                                    <a href="{{ route('reset.password.update.sms.view') }}"
                                       class="btn-link-border form-account-link">رمز
                                        عبور خود را فراموش
                                        کرده ام</a>
                                </div>
                                <div class="form-account-row">
                                    <label class="input-label"><i
                                                class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                    <input class="input-field" type="password" name="password"
                                           placeholder="رمز عبور خود را وارد نمایید">
                                </div>
                                <div class="form-account-row form-account-submit">
                                    <div class="parent-btn">
                                        <button class="dk-btn dk-btn-info">
                                            ورود به {{ env('SITE_NAME_FA') }}
                                            <i class="fa fa-sign-in"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-account-agree">
                                    <label class="checkbox-form checkbox-primary">
                                        <input type="checkbox" checked="checked" id="agree" name="remember">
                                        <span class="checkbox-check"></span>
                                    </label>
                                    <label for="agree">مرا به خاطر داشته باش</label>
                                </div>
                            </form>
                            <p><a href="{{route('activation.user.view')}}">تایید حساب کاربری</a></p>
                            <p><a href="{{route('reset.password.update.sms.view')}}">بازیابی رمز عبور</a></p>
                            <p><a href="{{route('send.activation.code.again')}}">ارسال مجدد کد فعالسازی</a></p>
                        </div>
                        <div class="account-box-footer">
                            <span>کاربر جدید هستید؟</span>
                            <a href="{{ route('register') }}" class="btn-link-border">ثبت‌ نام در
                                {{ env('SITE_NAME_FA') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
