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
                <form action="{{ route('reset.password.send.code') }}" method="post">
                    @csrf
                    <div class="site-login-register-inner-form-item">
                        <label for="">
                            شماره همراه
                        </label>
                        <input type="number"  name="mobile" placeholder="شماره همراه خود را وارد کنید">
                        @if($errors->has('mobile'))
                            {{ $errors->first('mobile') }}
                        @endif
                    </div>
                    <div class="site-login-register-inner-form-item">
                        <button type="submit">دریافت کد تایید</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
