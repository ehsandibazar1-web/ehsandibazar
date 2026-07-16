@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/internal/style.css"/>
@endsection
@section('content')
    <main class="site-login-register">
        <div class="site-login-register-inner">

            <div class="site-login-register-inner-header">
                <h1>ورود به سایت</h1>
                <p>
                    برای استفاده از تمامی امکانات سایت
                </p>
            </div>
            <div class="site-login-register-inner-form">
                @include('generals.allErrors')

                <form action="{{ route('reset.password.update.store') }}" method="post">
                    @csrf
                    <div class="site-login-register-inner-form-item">
                        <label for="">
                            رمزعبور
                        </label>
                        <input type="password" name="password" placeholder="رمز عبور خود را وارد کنید">
                    </div>

                    <div class="site-login-register-inner-form-item">
                        <label for="">
                            تکرار کلمه عبور
                        </label>
                        <input type="password"  name="password_confirmation" placeholder="تکرار رمز عبور خود را وارد کنید">
                    </div>

                    <div class="site-login-register-inner-form-item">
                        <button  type="submit">بازیابی</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
