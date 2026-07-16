@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت | ویرایش مقام
@endsection--}}


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.header-permission-edit')
                </header>

                <div class="panel-body">
                    <div class=" form">
                        <form class="cmxform form-horizontal tasi-form"  id="commentForm" method="post" action="{{ route('roles.update' , ['id' => $role->id ]) }}" >
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}


                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-2"> @lang('cms.title') </label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="name" required value="{{ $role->name }}">

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2">@lang('cms.description')</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" name="label" >{{ $role->label }}</textarea>
                                </div>
                            </div>


                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2">@lang('cms.accept')</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="permission_id[]" id="permission_id" multiple>
                                        @foreach(\App\Model\Permission::latest()->get() as $permission)
                                            <option value="{{ $permission->id }}" {{ in_array(trim($permission->id) , $role->permissions->pluck('id')->toArray()) ? 'selected' : ''  }}>{{ $permission->name }} - {!! $permission->label !!}</option>
                                        @endforeach
                                    </select>
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
