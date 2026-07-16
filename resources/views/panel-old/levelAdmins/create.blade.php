@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت | افزودن مقام جدید
@endsection--}}


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   @lang('cms.header-permission-create')
                </header>




                <div class="panel-body">
                    <div class=" form">
                        <form class="cmxform form-horizontal tasi-form"  id="commentForm"  action="{{ route('level.store') }}" method="post" >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">


                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-2"> @lang('cms.users') </label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="user_id" id="user_id" data-live-search="true" multiple>
                                        @foreach(\App\User::get() as $user)
                                            <option value="{{ $user->id }}">{{ $user->email }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2">@lang('cms.levels')</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="role_id" id="role_id" multiple>
                                        @foreach(\App\Model\Role::all() as $role)
                                            <option value="{{ $role->id }}">{!! $role->name !!} - {!! $role->label !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <input class="btn btn-danger" type="submit" value="@lang('cms.create')">

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
