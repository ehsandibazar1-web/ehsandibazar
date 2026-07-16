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
                                <h1 class="title-tab-content">Edit personal information</h1>
                            </div>
                            <div class="content-section default">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 class="title-tab-content">personal account</h1>
                                    </div>
                                </div>
                                @include('generals.allErrors')
                                <form class="form-account" action="{{ route('users.panel.profileUpdate') }}" method="post"
                                enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">name</div>
                                            <div class="form-account-row">
                                                <input value="{{ $user->name }}" name="name" class="input-field text-right" type="text" placeholder="Enter your name">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">family</div>
                                            <div class="form-account-row">
                                                <input  value="{{ $user->family }}" name="family" class="input-field text-right" type="text" placeholder="Enter your last name">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">mobile</div>
                                            <div class="form-account-row">
                                                <input value="{{ $user->mobile }}" disabled class="input-field" type="number" placeholder="Enter your mobile number">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">tell</div>
                                            <div class="form-account-row">
                                                <input value="{{ $user->tell }}" name="tell" class="input-field" type="number" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">Email</div>
                                            <div class="form-account-row">
                                                <input value="{{ $user->email }}" name="email" class="input-field" type="email" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">national code</div>
                                            <div class="form-account-row">
                                                <input value="{{ $user->national_code }}" name="national_code" class="input-field" type="number" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">avatar</div>
                                            <div class="form-account-row">
                                                <input type="file" name="avatar" class="input-field">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center p-xs-0">
                                        <div class="row">
                                            <div class="col-md-2 col-6 ps-0"> <button class="btn btn-default w-100">save</button></div>
                                            <div class="col-md-2 col-6 pe-0"> <a href="{{ route('users.dashboard.index') }}" class="btn btn-default w-100">close</a></div>
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
