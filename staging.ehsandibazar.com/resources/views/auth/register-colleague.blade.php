@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/internal/style.css"/>
@endsection
@section('content')
    <main class="site-login-register">
        <div class="site-login-register-inner">

            <div class="site-login-register-inner-header">
                <h1>ثبت نام</h1>
                <p>
                    برای استفاده از تمامی امکانات سایت ثبت نام کنید
                </p>
            </div>
            <div class="site-login-register-inner-form">
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
                <form action="{{ route('register.colleague.store') }}" method="post">
                    @csrf
                    <div class="site-login-register-inner-form-item">
                        <label for="">
                            شماره همراه
                        </label>
                        <input type="text" name="mobile" placeholder="شماره همراه خود را وارد کنید" value="{{ old('mobile') }}">
                    </div>
                    <div class="site-login-register-inner-form-item">
                        <label for="">
                            نام
                        </label>
                        <input type="text" class="family" name="name" id="name" onkeypress="text(this)" onchange="text(this)" keyup="text(this)" keydown="text(this)" placeholder="نام خود را وارد کنید" value="{{ old('name') }}">
                    </div>
                    <div class="site-login-register-inner-form-item">
                        <label for="">
                            نام خانوادگی
                        </label>
                        <input type="text" class="family" name="family" id="family" onkeypress="text(this)" onchange="text(this)" keyup="text(this)" keydown="text(this)" placeholder="نام خانوادگی خود را وارد کنید" value="{{ old('family') }}">
                    </div>
                    <div class="site-login-register-inner-form-item">
                        <label for="national_code">
                         کد ملی
                        </label>
                        <input type="number" name="national_code" placeholder="کد ملی خود را وارد کنید">
                    </div>

                    <div class="site-login-register-inner-form-item">
                        <label for="economic_code">
                            کد اقتصادی
                        </label>
                        <input type="text" name="economic_code" placeholder="کد اقتصادی خود را وارد کنید">
                    </div>

                    <div class="site-login-register-inner-form-item">
                        <label for="tell">
                           تلفن ثابت
                        </label>
                        <input type="number" name="tell" placeholder="تلفن ثابت...">
                    </div>

                    <div class="site-login-register-inner-form-item">
                        <label for="address">
                          آدرس
                        </label>
                        <input type="text" name="address" placeholder="آدرس خود را بصورت کامل بنویسید">
                    </div>

                    <div class="site-login-register-inner-form-item">
                        <label for="">
                            رمز عبور
                        </label>
                        <input type="password" name="password" placeholder="رمز عبور خود را وارد کنید">
                    </div>
                    <div class="site-login-register-inner-form-item">
                        <label for="">
                            تکرار رمزعبور
                        </label>
                        <input type="password" name="password_confirmation" placeholder="تکرار رمز عبور را وارد نمایید">
                    </div>
                    <div class="site-login-register-inner-form-item site-login-register-inner-form-item-check">
                        <p>

                            <input type="checkbox" name="low" placeholder="">
                            <span>قوانین و مقررات سایت را خوانده ام و آن را پذیرفته ام</span>
                        </p>
                    </div>
                    <div class="site-login-register-inner-form-item">
                        <button type="submit">
                            ثبت نام در سایت
                        </button>

                        <a href="{{ route('login') }}">
                            ورود
                        </a>
                        <br>
                        <a href="{{ route('activation.user.view')  }}">
                            تایید حساب کاربری
                        </a>
                        <br>
                        <a href="{{ route('reset.password.update.sms.view') }}">
                            بازیابی رمز عبور
                        </a>
                        <br>
                        <a href="{{ route('send.activation.code.again')  }}">
                            ارسال مجدد کد فعالسازی
                        </a>

                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

