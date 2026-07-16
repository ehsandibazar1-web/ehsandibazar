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
                        <div class="account-box-title text-right"> Password recovery |  {{ env('SITE_NAME_FA') }}</div>
                        <div class="account-box-content">
                            <div class="margin-top">
                                @if($errors->count() > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('reset.password.send.sms.change.store') }}" class="form-account">
                                @csrf
                                <div class="form-account-title"> email</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="email" name="email" value="{{ old('email') }}">
                                </div>

                                <div class="form-account-title">code
                                </div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="number" name="code" value="{{ old('code') }}">
                                </div>

                                <div class="form-account-title">
                                    password
                                </div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="password" name="password" placeholder="Enter your password">
                                </div>


                                <div class="form-account-title">
                                    repeat the password
                                </div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="password" name="password_confirmation" placeholder="Enter the password again">
                                </div>


                                <div class="form-account-row form-account-submit">
                                    <div class="parent-btn">
                                        <button class="dk-btn dk-btn-info">
                                            send
                                            <i class="fa fa-sign-in"></i>
                                        </button>
                                    </div>
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
