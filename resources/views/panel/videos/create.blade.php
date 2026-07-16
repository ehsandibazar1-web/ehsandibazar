@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop
@section('admin-css')
    <link href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css" rel="stylesheet"/>
@endsection
@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('admin-js')
    <script type="text/javascript">
        function videoType(type) {
            var typeVideo = type.value;
            if (typeVideo == {{ \App\Utility\VideoType::FREE }}) {
                $('.price').css("display", "none");
            } else {
                $('.price').css("display", "block");

            }
        }
    </script>

    <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js" type="text/javascript"></script>
    <script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".start_date").persianDatepicker({
                format: 'YYYY/MM/DD H:m:s',
                // initialValue: false,
                timePicker: {
                    enabled: true,
                    meridiem: {
                        enabled: true
                    }
                }
            });
        });
    </script>
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
                            @can('panel.video.index')
                                <a href="{{ route('panel.video.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> لیست ویدیو ها </a>
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
                                @can('panel.video.index')
                                    <li>
                                        <a href="{{ route('panel.video.index')  }}" style="font-size: 10px">لیست
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
                    @if(isset($find))
                        <form class="form-horizontal" method="post"
                              action="{{ route('panel.video.update',$find->id)  }}" enctype="multipart/form-data">
                            {{ method_field("PATCH") }}
                            @else
                                <form class="form-horizontal" method="post"
                                      action="{{ route('panel.video.store')  }}" enctype="multipart/form-data">
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
                                                           value="{{ isset($find) ? $find->title : old('title') }}">
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
                                                    <textarea rows="7" placeholder="محتوا را وارد نمایید"
                                                              name="description"
                                                              class="form-control ckeditor">{{ isset($find) ? $find->description : old('description') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- category --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="category">دسته بندی
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="">
                                                    <select name="category" id="category" class="form-group">

                                                        <option value=""> -- دسته بندی را انتخاب کنید --</option>
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
                                                           value="{{ !empty($find->image) ? $find->image[0]->url : old('filepath') }}">
                                                </div>
                                                <img id="holder2" style="margin-top:15px;max-height:100px;">

                                                @if(isset($find) && count($find->image) > 0 && isset($find->image[0]))
                                                    <img src="{{  $find->image[0]->url  }}" id="holder2"
                                                         style="margin-top:15px;max-height:100px;">

                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                    {{-- video --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">ویدیو
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="file" name="file">
                                                    @if(isset($find))
                                                        <label for="filepath">فایل :</label>
                                                        <a href="{{ asset('storage/'.$find->video) }}"
                                                           target="_blank">{{ $find->video }}</a>
                                                    @endif
                                                </div>
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


                                    @if(isset($find))
                                        @can('panel.video.update')
                                            {{-- button --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                    <button type="submit" class="btn-hover color-1 pull-left">ویرایش
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
                                    @else
                                        @can('panel.video.store')
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
@endsection

