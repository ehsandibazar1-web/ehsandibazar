@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')
    <!-- Horizontal Layout -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                      ویرایش تنظیم
                    </h2>

                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="#" onClick="return false;" class="dropdown-toggle" data-toggle="dropdown"
                               role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="{{ route('panel.dashboard.index')  }}">داشبورد</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                    <br>
                    <form method="post" action="{{route('panel.manage.update',['id' => $val->id])}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PATCH">

                        <div class="form-group">
                            <input type="text" name="name" class="form-control"
                                   placeholder="نام ..." value="{{$val->name}}" >
                        </div>

                        <div class="form-group">
                            <input type="text" name="code" class="form-control"
                                   placeholder="مقدار ..." value="{{$val->code}}">
                        </div>

                        <div class="form-group">
                            <input type="text" name="code2" class="form-control"
                                   placeholder="مقدار2 ..." value="{{$val->code2}}">
                            <a target="_blank" href="https://fontawesome.com/"><span> آیکون </span></a>
                        </div>

                        <div class="form-group">
                            <input type="text" name="code3" class="form-control"
                                   placeholder="مقدار3 ..." value="{{$val->code3}}">
                        </div>


                        <div class="form-group">
                            <textarea rows="7" placeholder="مقدار 4 را وارد نمایید یا paste کنید"  name="code4" class="form-control ckeditor">{{ $val->code4 }}</textarea>
                        </div>


                        {{-- image --}}
                        <div class="form-group ">
                            <label for="images" class="control-label col-lg-2">@lang('cms.featuring-image')
                            </label>
                            <div class="col-md-10">
                                <div class="input-group">
                                                  <span class="input-group-btn">
                                                    <a id="lfm1" data-input="thumbnail{{$val->id}}"
                                                       data-preview="holder{{$val->id}}"
                                                       class="btn btn-primary lfm1">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>
                                    <input id="thumbnail{{$val->id}}" class="form-control" type="text"
                                           value="{{!empty($val->code5) ? $val->code5 : null}}"
                                           name="filepath">
                                </div>

                                <img id="holder{{$val->id}}"
                                     src="{{isset($val) && !empty($val->code5) ? $val->code5 : null}}"
                                     style="margin-top:15px;max-height:100px;">
                                <br>
                                <img id="holder{{$val->id}}" style="margin-top:15px;max-height:100px;">
                                <br>
                            </div>
                        </div>





                        <button type="submit" class="btn btn-info btn-round">ذخیره</button>
                        <a href="{{ route('panel.setting.index') }}" class="btn btn-default waves-effect pull-left"
                                data-dismiss="modal">انصراف
                        </a>
                    </form>




                </div>
            </div>
        </div>
    </div>
    <!-- #END# Horizontal Layout -->

@stop
@section('admin-js')
    @include('panel.layout.ckjs')
@endsection
