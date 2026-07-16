@extends('panel-old.layout.master')

@section('script')

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.create-new-user')

                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                </header>




                <div class="panel-body">
                    <div class=" form">
                        <form class="cmxform form-horizontal tasi-form"  id="commentForm" method="post" action="{{ route('panel.users.store')}}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">


                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-2"> @lang('cms.name') </label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-2"> @lang('cms.family')</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="family" value="{{ old('family') }}" required>

                                </div>
                            </div>



                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2">@lang('cms.mobile')</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control" name="mobile" value="{{ old('mobile') }}" required >
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2">@lang('cms.email')</label>
                                <div class="col-lg-10">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"  >
                                </div>
                            </div>



                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2">@lang('cms.password')</label>
                                <div class="col-lg-10">
                                    <input type="password" class="form-control" name="password" >
                                </div>
                            </div>


                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-2"> @lang('cms.type') </label>
                                <div class="col-lg-10">
                                    <select class="form-control select-option" name="level">
                                        <option value="">@lang('cms.choose-type-of-user')</option>
                                        @foreach(\App\Utility\Level::levelEach() as $key => $value)
                                            <option value="{{ $key  }}">{{ $value  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group ">
                                <label for="role" class="control-label col-lg-2">نقش کاربری </label>
                                <div class="col-lg-10">
                                    <select class="form-control select-option" name="role">
                                        <option value="">@lang('cms.choose-type-of-user')</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id  }}">{!! $role->name."-".$role->label !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <input class="btn btn-success pull-left" type="submit" value="@lang('cms.create')">

                                </div>
                            </div>
                            <br><br><br><br><br>
                        </form>
                    </div>

                </div>

            </section>
        </div>
    </div>

@endsection
