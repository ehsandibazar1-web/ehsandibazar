@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@endsection

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@endsection



@section('content')

    <!-- Horizontal Layout -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.news.index')
                                <a href="{{ route('panel.news.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> لیست اخبار </a>
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
                                @can('panel.news.index')
                                    <li>
                                        <a href="{{ route('panel.news.index')  }}" style="font-size: 10px">لیست
                                            اخبار</a>
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
                              action="{{ route('panel.news.update',$find->id)  }}">
                            {{ method_field("PATCH") }}
                            @else
                                <form class="form-horizontal" method="post"
                                      action="{{ route('panel.news.store')  }}">
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
                                                    <input name="title" type="text" id="title" class="form-control"
                                                           placeholder="عنوان خود را بنویسید"
                                                           value="{{ isset($find) ? $find->title : null }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- lead --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="lead">خلاصه خبر
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="lead" type="text" id="lead" class="form-control"
                                                           placeholder="عنوان خود را بنویسید"
                                                           value="{{ isset($find) ? $find->lead : null }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- body --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">محتوا
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <textarea rows="7" placeholder="محتوا را وارد نمایید" name="body"
                                                              class="form-control ckeditor">{{ isset($find) ? $find->description : null }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- category --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="status">دسته بندی
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group default-select select2Style">

                                                <select name="cat_id" id="parent" class="form-control select2"
                                                        data-placeholder="انتخاب دسته بندی...">
                                                    @if(isset($category))
                                                        @foreach($category as $key => $item)
                                                            <option
                                                                value="{{ $item->id  }}" {{ isset($find) && in_array($item->id,$find->categories->pluck('id')->toArray()) ? 'selected' : null }}> {{ $item->title  }} </option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                    {{-- image --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="status">تصویر شاخص
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="">
                                                    <span class="input-group-btn">
                                                    <a id="lfm" data-input="thumbnail2" data-preview="holder2"
                                                       class="btn btn-primary">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>
                                                    <input id="thumbnail2" class="form-control" type="text"
                                                           name="filepath"
                                                           value="{{ !empty($find->image)  ? $find->image[0]->url : null }}">
                                                </div>
                                                <img id="holder2" style="margin-top:15px;max-height:100px;">

                                                @if(isset($find) && count($find->image) > 0 && isset($find->image[0]))
                                                    <img src="{{  $find->image[0]->url  }}" id="holder2"
                                                         style="margin-top:15px;max-height:100px;">

                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                    {{-- extra meta --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="extra_meta">متای اضافه
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <textarea
                                                        name="extra_meta">{{ isset($find) ? $find->extra_meta : null }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    @if(isset($countries) && count($countries) > 0)
                                        {{-- countries --}}
                                        <div class="row clearfix">
                                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                <label for="countries"> کشور ها
                                                </label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                <div class="form-group">
                                                    <div class="">
                                                        <select name="countries[]" id="countries"
                                                                class="form-group select2"
                                                                multiple="multiple" style="display: block">
                                                            @foreach($countries as $item)
                                                                <option
                                                                    value="{{$item->id}}" {{ isset($find) && in_array($item->id , $find->countries->pluck('id')->toArray()) ? "selected" :null }} >{{ $item->fa_name." - ".$item->en_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                   @include('panel.layout.inputs.tags')


                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="selected">خبر منتخب ؟</label>
                                        </div>
                                        <div class="demo-switch">
                                            <div class="switch">
                                                <label>بله
                                                    <input name="selected" type="checkbox"
                                                           @if(isset($find) && !empty($find) && $find->selected == 1) checked @endif>
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
                                        @can('panel.news.update')
                                            {{-- button --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                    <button type="submit" class="btn-hover color-1 pull-left">ویرایش
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
                                    @else
                                        @can('panel.news.store')
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
@section('admin-js')
    @include('panel.layout.ckjs')
@endsection
