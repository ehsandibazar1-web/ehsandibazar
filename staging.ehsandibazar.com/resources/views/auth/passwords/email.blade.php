@extends('site.layout.master')
@section('title')
    بازیابی رمز عبور
@endsection
@section('header')
    <header class="page-header fixed-header">
        @include('site.layout.partials.header')
    </header>
@endsection
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/style.css"/>
@endsection
@section('content')
    <div class="main-page">
        <div class="login-page">
            <div class="d-flex justify-content-center h-100">
                <div class="card">
                    <div class="card-header">
                        <h3>بازیابی رمز عبور</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="input-group form-group">
                                <input type="text" name="email" class="form-control" placeholder="ایمیل">
                                @if ($errors->has('email'))
                                    {{ $errors->first('email') }}
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="submit" value="بازیابی" class="btn pull-left login_btn btn-ctm">
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            <span>نام کاربری خود را فراموش کرده اید؟</span> &nbsp;<a href="{{route('send.email.to.phone')}}" class="links">بازیابی نام کاربری</a>
                        </div>
                        <div class="d-flex justify-content-center">
                            <span>هنوز اکانتی ندارید ؟</span> &nbsp;<a href="{{ route('register') }}" class="links"> ثبت نام </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
