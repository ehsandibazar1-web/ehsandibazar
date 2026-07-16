@extends('site.layout.master')
@section('site.css')
    @include('site.users.partials.user-style-area')
    <script>
    function text(name){
        var str = $(name).val();
        just_persian(str,name);
        
    }
    
    function just_persian(str,name){
        var p = /^[\u0600-\u06FF\s]+$/;
        if(!p.test(str)){
              $(name).val("");
        }
        return true;
    }
</script>
@endsection
@section('content')
    <section class="page-section account-page">
        <div class="uk-container uk-containcer-center uk-margin-large-top uk-margin-large-bottom">

            <div class="uk-grid" uk-grid>
                @include('site.users.partials.menu')
                <div class="uk-width-3-4@m uk-background-muted">
                    @include('generals.allErrors')
                    <form id="addressform" class="inputform" method="post"
                          action="{{ route('user-profile.update', $profile->id) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <h5>اطلاعات کاربری</h5>
                        <div class="uk-margin">
                            <input class="uk-input" type="text"
                                   value="{{ !empty($profile->name) ? $profile->name : null  }}" name="name"
                                   placeholder="نام" onkeypress="text(this)" onchange="text(this)" keyup="text(this)" keydown="text(this)">
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="text"
                                   value="{{ !empty($profile->family) ? $profile->family : null }}" name="family" onkeypress="text(this)" onchange="text(this)" keyup="text(this)" keydown="text(this)"
                                   placeholder="نام خانوادگی">  
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="number"
                                   value="{{ !empty($profile->tell) ? $profile->tell : null }}" name="tell"
                                   placeholder="تلفن">
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="number"
                                   value="{{ !empty($profile->mobile) ? $profile->mobile : null }}" name="mobile"
                                   placeholder="موبایل" disabled="disabled">
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="email"
                                   value="{{ !empty($profile->email) ? $profile->email : null }}" name="email"
                                   placeholder="ایمیل">
                        </div>
                        @if(auth()->user()->isColleague())

                            <div class="uk-margin">
                                <input class="uk-input" type="number"
                                       value="{{ !empty($profile->national_code) ? $profile->national_code : null }}" name="national_code"
                                       placeholder="کد ملی" disabled="disabled">
                            </div>

                            <div class="uk-margin">
                                <input class="uk-input" type="text"
                                       value="{{ !empty($profile->economic_code) ? $profile->economic_code : null }}" name="economic_code"
                                       placeholder="کد اقتصادی" disabled="disabled">
                            </div>

                            <div class="uk-margin">
                                <input class="uk-input" type="text"
                                       value="{{ !empty($profile->full_address) ? $profile->full_address : null }}" name="full_address"
                                       placeholder="آدرس ...">
                            </div>
                        @endif

                        <button type="submit" class="uk-button uk-button-danger">ذخیره</button>
                    </form>
                    <hr>

                    <form id="addressform" class="inputform" method="post"
                          action="{{ route('users.change.password') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="uk-margin">
                            <h5>تغییر رمز عبور</h5>
                            <input class="uk-input" type="password" name="current-password"
                                   placeholder="رمز عبور فعلی را وارد نمایید" required>
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="password" name="new-password"
                                   placeholder="رمز عبور جدید را وارد نمایید" required>
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="password" name="new-password_confirmation"
                                   placeholder="تکرار رمز عبور جدید" required>
                        </div>

                        <button type="submit" class="uk-button uk-button-danger">ارسال</button>
                    </form>
                </div>
            </div>

        </div>
    </section>
@endsection
