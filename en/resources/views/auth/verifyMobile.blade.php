@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/internal/style.css"/>
@endsection
@section('content')
    <main class="site-login-register">
        <div class="site-login-register-inner">

            <div class="site-login-register-inner-header">
                <h1>تایید حساب کاربری</h1>
            </div>
            <div class="site-login-register-inner-form">
                @include('generals.allErrors')
                @include('generals.sessionMessage')
                <form action="{{ route('activation.user')  }}" method="POST">
                    @csrf
                    <div class="site-login-register-inner-form-item">
                        <label for="">
                            شماره همراه
                        </label>
                        <input type="text" name="mobile" placeholder="شماره همراه خود را وارد کنید">
                    </div>

                    <div class="site-login-register-inner-form-item">
                        <label for="">
                          کد فعالسازی
                        </label>
                        <input type="number" name="activationCode" placeholder="کد را وارد کنید">
                    </div>

                    <div class="site-login-register-inner-form-item">
                        <button type="submit">
                           ارسال
                        </button>

                        <a href="{{ route('login')  }}">
                            ورود
                        </a>
                        <br>
                        <a href="{{ route('register') }}">
                          ثبت نام
                        </a>

                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
