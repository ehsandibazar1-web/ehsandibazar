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
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.article.index')
                                <a href="{{ route('panel.article.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> لیست مقالات </a>
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
                                @can('panel.article.index')
                                    <li>
                                        <a href="{{ route('panel.article.index')  }}" style="font-size: 10px">لیست
                                            مقاله ها</a>
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
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation">
                            <a href="#home" data-toggle="tab" class="active show"> اطلاعات </a>
                        </li>
                        <li role="presentation">
                            <a href="#seo" data-toggle="tab"> سئو </a>
                        </li>
                    </ul>
                    @if(isset($find))
                        <form class="form-horizontal" method="post"
                              action="{{ route('panel.article.update',$find->id)  }}">
                            {{ method_field("PATCH") }}
                            @else
                                <form class="form-horizontal" method="post"
                                      action="{{ route('panel.article.store')  }}">
                                    @endif

                                    @csrf

                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade in active show" id="home">

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
                                                            <input name="title" type="text" id="title"
                                                                   class="form-control title"
                                                                   placeholder="عنوان خود را بنویسید"
                                                                   value="{{ isset($find) ? $find->title : null }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @include('panel.layout.inputs.slug')

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
                                                              class="form-control ckeditor">{{ isset($find) ? $find->body : null }}</textarea>
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
                                                    <div class="form-group">
                                                        <div class="">
                                                            <select name="cat_id" id="parent" class="form-group">

                                                                <option value=""> -- دسته بندی را انتخاب کنید --
                                                                </option>
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

                                        </div>
                                        @include('panel.layout.inputs.seo')
                                        @if(isset($find))
                                            @can('panel.article.update')
                                                {{-- button --}}
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                        <button type="submit" class="btn-hover color-1 pull-left">ویرایش
                                                        </button>
                                                    </div>
                                                </div>
                                            @endcan
                                        @else
                                            @can('panel.article.store')
                                                {{-- button --}}
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                        <button type="submit" class="btn-hover color-1 pull-left">ذخیره
                                                        </button>
                                                    </div>
                                                </div>
                                            @endcan
                                        @endif
                                    </div>




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
