@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت | ویرایش دسترسی
@endsection--}}


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.header-access-user-edit')
                </header>


                <div class="panel-body">
                    <div class=" form">
                        <form class="cmxform form-horizontal tasi-form"  id="commentForm" method="post" action="{{ route('permissions.update' , ['id' => $permission->id ]) }}" >
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}


                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-2"> @lang('cms.title-level')</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="name" required value="{{ $permission->name  }}">

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2">@lang('cms.description')</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" name="label" >{{ $permission->label }}</textarea>
                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <input class="btn btn-warning pull-left" type="submit" value="@lang('cms.edit')">

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
