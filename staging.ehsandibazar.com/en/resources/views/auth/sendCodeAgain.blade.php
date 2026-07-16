@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/internal/style.css"/>
@endsection
@section('content')
    <main class="site-login-register">
        <div class="site-login-register-inner">

            <div class="site-login-register-inner-header">
                <h1>ارسال مجدد کد فعالسازی</h1>
            </div>
            <div class="site-login-register-inner-form">
                @include('generals.allErrors')
                @include('generals.sessionMessage')
                <form method="POST" action="{{ route('send.activation.code.request') }}">
                    @csrf
                    <div class="site-login-register-inner-form-item">
                        <label for="">
                            شماره همراه
                        </label>
                        <input type="number" name="mobile" autocomplete="mobile" value="{{ old('mobile') }}" placeholder="شماره همراه خود را وارد کنید" autofocus>
                    </div>


                    <div class="site-login-register-inner-form-item">
                        <button type="submit">
                            ارسال
                        </button>

                        <a href="{{ route('login')  }}">
                            ورود
                        </a>

                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
