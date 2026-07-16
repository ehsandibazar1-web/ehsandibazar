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
                        <div class="account-box-title text-right">login to {{ env('SITE_NAME_FA') }}</div>
                        <div class="account-box-content">
                            @include('generals.allErrors')
                            @include('generals.sessionMessage')
                            <form action="{{ route('login') }}" method="post" class="form-account">
                                @csrf
                                <div class="form-account-title"> email</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="email" name="email" value="{{ old('email') }}"
                                           placeholder="Enter your email">
                                </div>
                              
                                <div class="form-account-title"> password</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i
                                                class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                    <input class="input-field" type="password" name="password"
                                           placeholder="Enter your password">
                                </div>

                                <div class="form-account-title">
                                    <a href="{{ route('reset.password.update.sms.view') }}"
                                       class="btn-link-border form-account-link">
                                        I forgot my password</a>
                                </div>
                                <div class="form-account-row form-account-submit">
                                    <div class="parent-btn">
                                        <button class="dk-btn dk-btn-info">
                                             Login To {{ env('SITE_NAME_FA') }}
                                            <i class="fa fa-sign-in"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-account-agree">
                                    <label class="checkbox-form checkbox-primary">
                                        <input type="checkbox" checked="checked" id="agree" name="remember">
                                        <span class="checkbox-check"></span>
                                    </label>
                                    <label for="agree">Remember me</label>
                                </div>
                            </form>
                        </div>
                        <div class="account-box-footer">
                            <span>Are you a new user?</span>
                            <a href="{{ route('register') }}" class="btn-link-border">sign up in
                                {{ env('SITE_NAME_FA') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
