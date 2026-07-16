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
                        <div class="account-box-title">ثبت ‌نام در {{ env('SITE_NAME_FA') }}</div>
                        <div class="message-light">اگر قبلا با شماره موبایل خود ثبت ‌نام کرده‌اید، نیاز به
                            ثبت‌نام مجدد با شماره
                            همراه ندارید</div>
                        <div class="account-box-content">
                            <form action="{{ route('register') }}" method="post" class="form-account">
                                @csrf
                                <div class="form-account-title">نام</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="text" name="name"
                                           onkeypress="text(this)" onchange="text(this)" keyup="text(this)" keydown="text(this)"
                                           placeholder=" نام خود را وارد نمایید">
                                </div>
                                <div class="form-account-title">نام خانوادگی</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="text" name="family"
                                           onkeypress="text(this)" onchange="text(this)" keyup="text(this)" keydown="text(this)"
                                           placeholder=" نام خانوادگی خود را وارد نمایید">
                                </div>
                                <div class="form-account-title"> شماره موبایل</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="number" name="mobile"
                                           placeholder=" شماره موبایل خود را وارد نمایید">
                                </div>
                                <div class="form-account-title">کلمه عبور</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i
                                                class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                    <input class="input-field" type="password" name="password"
                                           placeholder="کلمه عبور خود را وارد نمایید">
                                </div>
                                <div class="form-account-title">تکرار کلمه عبور</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i
                                                class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                    <input class="input-field" type="password" name="password_confirmation"
                                           placeholder="تکرار کلمه عبور خود را وارد نمایید">
                                </div>
                                <div class="form-account-agree">
                                    <label class="checkbox-form checkbox-primary">
                                        <input type="checkbox" checked="checked" name="low">
                                        <span class="checkbox-check"></span>
                                    </label>
                                    <label for="agree">
                                        <a target="_blank" href="https://ehsandibazar.com/page/%D8%B3%DB%8C%D8%A7%D8%B3%D8%AA-%D8%AD%D8%B1%DB%8C%D9%85-%D8%AE%D8%B5%D9%88%D8%B5%DB%8C" class="btn-link-border">حریم خصوصی</a> و <a target="_blank" href="https://ehsandibazar.com/page/قوانین-و-مقررات"
                                                                                                class="btn-link-border">شرایط و قوانین</a> استفاده از سرویس های سایت
                                        {{ env('SITE_NAME_FA') }} را مطالعه نموده و با کلیه موارد آن موافقم.</label>
                                </div>
                                <div class="form-account-row form-account-submit">
                                    <div class="parent-btn">
                                        <button class="dk-btn dk-btn-info">
                                            ثبت نام در {{ env('SITE_NAME_FA') }}
                                            <i class="now-ui-icons users_circle-08"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="account-box-footer">
                            <span>قبلا در {{ env('SITE_NAME_FA') }} ثبت‌نام کرده‌اید؟</span>
                            <a href="{{ route('login') }}" class="btn-link-border">وارد شوید</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

