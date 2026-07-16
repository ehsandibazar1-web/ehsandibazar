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
                        <a href="#" class="logo">
                            <img src="assets/img/logo.png" alt="">
                        </a>
                        <div class="account-box-title text-center"> بازیابی رمز عبور | {{ env('SITE_NAME_FA') }}</div>
                        <div class="account-box-content">
                            @include('generals.allErrors')
                            @include('generals.sessionMessage')
                            <form method="POST" action="{{ route('reset.password.send.sms') }}" class="form-account">
                                @csrf

                                <div class="form-account-title"> شماره موبایل</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="number" name="mobile" autocomplete="mobile" value="{{ old('mobile') }}" placeholder="شماره همراه خود را وارد کنید" autofocus>
                                </div>

                                <div class="form-account-row form-account-submit">
                                    <div class="parent-btn">
                                        <button class="dk-btn dk-btn-info">
                                            ارسال
                                            <i class="fa fa-sign-in"></i>
                                        </button>
                                    </div>
                                </div>

                            </form>
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
