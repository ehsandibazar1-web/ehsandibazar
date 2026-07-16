@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
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
    <main class="profile-user-page default">
        <div class="container wrapper default">
            <div class="row">
                <div class="profile-page col-xl-9 col-lg-8 col-md-12 order-2 ">
                    <div class="row">
                        <div class="col-12 p-xs-0">
                            <div class="col-12 ">
                                <h1 class="title-tab-content">ویرایش اطلاعات شخصی</h1>
                            </div>
                            <div class="content-section default">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 class="title-tab-content">حساب شخصی</h1>
                                    </div>
                                </div>
                                @include('generals.allErrors')
                                <form class="form-account" action="{{ route('users.panel.profileUpdate') }}" method="post"
                                enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">نام</div>
                                            <div class="form-account-row">
                                                <input value="{{ $user->name }}" name="name" class="input-field text-right" type="text" placeholder="نام خود را وارد نمایید">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">نام خانوادگی</div>
                                            <div class="form-account-row">
                                                <input  value="{{ $user->family }}" name="family" class="input-field text-right" type="text" placeholder="نام خانوادگی خود را وارد نمایید">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">شماره موبایل</div>
                                            <div class="form-account-row">
                                                <input value="{{ $user->mobile }}" disabled class="input-field" type="number" placeholder="شماره موبایل خود را وارد نمایید">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">تلفن ثابت</div>
                                            <div class="form-account-row">
                                                <input value="{{ $user->tell }}" name="tell" class="input-field" type="number" placeholder="شماره موبایل خود را وارد نمایید">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">آدرس ایمیل</div>
                                            <div class="form-account-row">
                                                <input value="{{ $user->email }}" name="email" class="input-field" type="email" placeholder=" آدرس ایمیل خود را وارد نمایید">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">کد ملی</div>
                                            <div class="form-account-row">
                                                <input value="{{ $user->national_code }}" name="national_code" class="input-field" type="number" placeholder="کد ملی خود را وارد نمایید">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">آواتار</div>
                                            <div class="form-account-row">
                                                <input type="file" name="avatar" class="input-field">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center p-xs-0">
                                        <div class="row">
                                            <div class="col-md-2 col-6 ps-0"> <button class="btn btn-default w-100">ذخیره</button></div>
                                            <div class="col-md-2 col-6 pe-0"> <a href="{{ route('users.dashboard.index') }}" class="btn btn-default w-100">انصراف</a></div>
                                        </div>
                                       
                                       
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>
                @include('users.layouts.partials.aside-menu')
            </div>
        </div>
    </main>
@endsection
