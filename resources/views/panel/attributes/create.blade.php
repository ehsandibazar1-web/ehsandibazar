@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('admin-js')

@endsection

@section('content')

    <!-- Horizontal Layout -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ $title ?? '' }}
                        <div class="pull-left margin-5">
                            @can('panel.attributes.index')
                                <a href="{{ route('panel.attribute.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> فهرست ویژگی ها </a>
                            @endcan
                        </div>
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
                                @can('panel.attributes.index')
                                    <li>
                                        <a href="{{ route('panel.attributes.index')  }}" style="font-size: 10px">فهرست
                                            ویژگی ها</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                    <br>
                    @if(isset($find))
                        <form class="form-horizontal" method="post"
                              action="{{ route('panel.attribute.update',$find->id)  }}">
                            {{ method_field("PATCH") }}
                            @else
                                <form class="form-horizontal" method="post"
                                      action="{{ route('panel.attribute.store')  }}">
                                    @endif

                                    @csrf

                                    {{-- title --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">عنوان
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="name" type="text" id="name" class="form-control"
                                                           placeholder="عنوان را بنویسید"
                                                           value="{{ isset($find) ? $find->name : null }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- label --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">برچسب
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="label" type="text" id="label" class="form-control"
                                                           placeholder="عنوان را بنویسید"
                                                           value="{{ isset($find) ? $find->label : null }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- attribute Group --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="attr-group">گروه ویژگی
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                @if(isset($attributeAllGroup) && !empty($attributeAllGroup))
                                                    <select name="attribute_group_id" id="attr-group"
                                                            class="form-group">
                                                        @foreach($attributeAllGroup as $key => $attributeGroup)
                                                            <option
                                                                    value="{{ $attributeGroup->id  }}" {{ isset($find) && $find->attribute_group_id == $attributeGroup->id ? 'selected' : null }}> {{ $attributeGroup->name  }} </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                    </div>




                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="status">قابلیت فیلتر؟</label>
                                        </div>
                                        <div class="demo-switch">
                                            <div class="switch">
                                                <label>بله
                                                    <input name="filterCheck" type="checkbox" @if(isset($find) && !empty($find) && $find->is_filter == 1) checked @endif>
                                                    <span class="lever"></span>خیر</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- status --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="status">وضعیت
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="">
                                                    <select name="status" id="status" class="form-group">

                                                        <option value="0"> -- وضعیت را انتخاب کنید --</option>
                                                        @foreach(\App\Utility\Status::Status() as $key => $itemStatus)
                                                            <option
                                                                    value="{{ $key  }}" {{ isset($find) && $key == $find->status ? 'selected' : null }}> {{ $itemStatus  }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    @if(isset($find))
                                        @can('panel.attribute.update')
                                            {{-- button --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                    <button type="submit" class="btn-hover color-1 pull-left">ویرایش
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
                                    @else
                                        @can('panel.attribute.store')
                                            {{-- button --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                    <button type="submit" class="btn-hover color-1 pull-left">ذخیره
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
                                    @endif


                                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Horizontal Layout -->

@stop
