@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
@endsection
@section('content')
    <main class="profile-user-page default">
        <div class="container wrapper default">
            <div class="row">
                <div class="profile-page col-xl-9 col-lg-8 col-md-12 order-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="col-12">
                                <h1 class="title-tab-content">Change password</h1>
                            </div>
                            <div class="content-section default">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 class="title-tab-content">Change account password</h1>
                                    </div>
                                </div>
                                @include('generals.allErrors')
                                <div class="account-box">
                                    <a href="#" class="logo">
                                        <img src="assets/img/logo.png" alt="">
                                    </a>
                                    <div class="account-box-title">change Password</div>
                                    <div class="account-box-content">
                                        <form class="form-account" action="{{ route('users.change.password') }}" method="post">
                                            @csrf
                                            <div class="form-account-title">Previous password</div>
                                            <div class="form-account-row">
                                                <label class="input-label"><i class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                                <input name="current-password" class="input-field" type="password" placeholder="Enter your previous password">
                                            </div>
                                            <div class="form-account-title">New password</div>
                                            <div class="form-account-row">
                                                <label class="input-label"><i class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                                <input name="new-password" class="input-field" type="password" placeholder="Enter your new password">
                                            </div>
                                            <div class="form-account-title">Repeat the new password</div>
                                            <div class="form-account-row">
                                                <label class="input-label"><i class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                                <input name="new-password-confirmation" class="input-field" type="password" placeholder="Re-enter your new password">
                                            </div>
                                            <div class="form-account-row form-account-submit">
                                                <div class="parent-btn">
                                                    <button class="dk-btn dk-btn-info">
                                                        change Password
                                                        <i class="now-ui-icons arrows-1_refresh-69"></i>
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
                @include('users.layouts.partials.aside-menu')
            </div>
        </div>
    </main>
@endsection
