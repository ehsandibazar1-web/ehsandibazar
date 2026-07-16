@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/internal/style.css"/>
@endsection
@section('content')
    <div class="wrapper default">
        <div class="container">
            <div class="row">
                <div class="main-content col-12 col-md-7 col-lg-5 mx-auto">
                    <div class="account-box">
                        <div class="account-box-title text-right">
                            ارسال مجدد کد فعال سازی
                        </div>
                        <div class="account-box-content">
                            @include('generals.allErrors')
                            @include('generals.sessionMessage')
                            <form action="{{ route('send.activation.code.request') }}" method="post" class="form-account">
                                @csrf
                                <div class="form-account-title"> شماره موبایل</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field" type="number" name="mobile" value="{{ old('mobile') }}"
                                           placeholder="  شماره موبایل خود را وارد نمایید" autofocus>
                                </div>
                                <div class="form-account-row form-account-submit">
                                    <div class="parent-btn">
                                        <button class="dk-btn dk-btn-info">
                                            ارسال
                                            <i class="fa fa-sign-in"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection