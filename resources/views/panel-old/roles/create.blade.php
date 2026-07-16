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
                        <form class="cmxform form-horizontal tasi-form"  id="commentForm" method="post" action="{{ route('roles.store') }}" >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">


                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-2"> @lang('cms.title') </label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="name" required>

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2">@lang('cms.description')</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" name="label" ></textarea>
                                </div>
                            </div>


                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2">@lang('cms.accept')</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="permission_id[]" id="permission_id" multiple>
                                        @foreach(\App\Model\Permission::latest()->get() as $permission)
                                            <option value="{{ $permission->id }}">{{ $permission->name }} - {!! $permission->label !!}</option>
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
