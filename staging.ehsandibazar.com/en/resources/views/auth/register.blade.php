@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
<script>
    function text(name){
        var name = $(name).val();
        just_persian(name);
        
    }
    
    function just_persian(str){
        var p = /^[\u0600-\u06FF\s]+$/;
        if(!p.test(str) && str.length > 0){
              $('.txt-fa').val("");
        }
        return true;
    }
</script>
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
                        <div class="account-box-title">sign up in {{ env('SITE_NAME_FA') }}</div>
                        <div class="message-light">
                            If you've already registered with your email, you need to
                            Re-register by email
                            do not have one
                            </div>
                               @include('generals.allErrors')
                            @include('generals.sessionMessage')
                        <div class="account-box-content">
                            <form action="{{ route('register') }}" method="post" class="form-account">
                                @csrf
                                <div class="form-account-title">name</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="text" name="name"
                                           onkeypress="text(this)" onchange="text(this)" keyup="text(this)" keydown="text(this)"
                                           placeholder=" Enter your name">
                                </div>
                                <div class="form-account-title">last name</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="text" name="family"
                                           onkeypress="text(this)" onchange="text(this)" keyup="text(this)" keydown="text(this)"
                                           placeholder=" Enter your last name">
                                </div>
                                <div class="form-account-title"> email</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="email" name="email"
                                           placeholder="Enter your email">
                                </div>
                                <div class="form-account-title">Password</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i
                                                class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                    <input class="input-field" type="password" name="password"
                                           placeholder="Enter your password">
                                </div>
                                <div class="form-account-title">Repeat password</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i
                                                class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                    <input class="input-field" type="password" name="password_confirmation"
                                           placeholder="Repeat your password">
                                </div>
                                <div class="form-account-agree">
                                    <label class="checkbox-form checkbox-primary">
                                        <input type="checkbox" checked="checked" name="low">
                                        <span class="checkbox-check"></span>
                                    </label>
                                    <label for="agree">
                                        <a target="_blank" href="https://ehsandibazar.com/en/page/Privacy-Policy" class="btn-link-border">Privacy</a> and  <a target="_blank" href="https://ehsandibazar.com/en/page/Terms-and-Conditions"
                                                                                                class="btn-link-border"> Terms and Conditions</a> Use of site services
                                        {{ env('SITE_NAME_FA') }} I read it and I agree with all its cases.</label>
                                </div>
                                <div class="form-account-row form-account-submit">
                                    <div class="parent-btn">
                                        <button class="dk-btn dk-btn-info">
                                            sign up in {{ env('SITE_NAME_FA') }}
                                            <i class="now-ui-icons users_circle-08"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="account-box-footer">
                            <span>Previously in {{ env('SITE_NAME_FA') }}     Are you registered?</span>
                            <a href="{{ route('login') }}" class="btn-link-border">login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

