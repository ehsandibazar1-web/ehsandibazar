@extends('site.layout.master')

@section('content')
    <main class="site-login-register">
        <div class="site-login-register-inner">

            <div class="site-login-register-inner-header">
                <h1>فعالسازی</h1>
                <p>حساب خود را فعال کنید.</p>
            </div>
            <div class="site-login-register-inner-form">
                @if(session()->has('type'))
                            <form action="{{ route('reset.password.check.code') }}" method="post">
                    @else
                            <form action="{{ route('activation.user') }}" method="post">
                @endif

                                @csrf
                                @include('generals.allErrors')
                    <div class="site-login-register-inner-form-item">
                        <label for="">کد تایید </label>
                        <input type="number" placeholder="- - - - -" style="font-family: Arial" name="activation_code">
                    </div>

                    <div class="site-login-register-inner-form-item">
                        @if(session()->has('type'))
                            <button type="submit">
                               بازیابی
                            </button>
                        @else
                            <button type="submit">
                               ورود
                            </button>
                        @endif

                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

