@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت | ویرایش مقام
@endsection--}}

@section('script')

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                  @lang('cms.header-permission-edit')
                </header>



                    <div class="panel-body">
                        <div class=" form">
                            <form class="cmxform form-horizontal tasi-form"  id="commentForm" action="{{ route('level.update' , ['id' => $user->id]) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}

                                <div class="form-group ">
                                    <label for="cname" class="control-label col-lg-2"> @lang('cms.levels') - {{ $user->email }}</label>
                                    <div class="col-lg-10">
                                        <select class="form-control" name="role_id" id="role_id">
                                            @foreach(\App\Model\Role::all() as $role)
                                                <option value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }} - {!! $role->label !!}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>



                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <input class="btn btn-danger" type="submit" value="@lang('cms.record')">

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

