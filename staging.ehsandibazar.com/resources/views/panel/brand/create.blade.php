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
                            @can('panel.brand.index')
                                <a href="{{ route('panel.brand.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> لیست برند ها </a>
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
                                @can('panel.brand.index')
                                    <li>
                                        <a href="{{ route('panel.brand.index')  }}" style="font-size: 10px">لیست برند
                                            ها</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation">
                            <a href="#home" data-toggle="tab" class="active show"> اطلاعات </a>
                        </li>
                        <li role="presentation">
                            <a href="#seo" data-toggle="tab"> سئو </a>
                        </li>
                        <li role="presentation">
                            <a href="#messages" data-toggle="tab"> گالری تصاویر </a>
                        </li>
                    </ul>

                    @if(isset($find))
                        <form class="form-horizontal" method="post"
                              action="{{ route('panel.brand.update',$find->id)  }}">
                            {{ method_field("PATCH") }}
                            @else
                                <form class="form-horizontal" method="post"
                                      action="{{ route('panel.brand.store')  }}">
                                    @endif

                                    @csrf
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade in active show" id="home">

                                            {{-- title --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="title">نام
                                                        <span class="redAlert">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input name="title" type="text" id="title"
                                                                   class="form-control title"
                                                                   placeholder="نام برند خود را بنویسید"
                                                                   value="{{ isset($find) ? $find->title : old('title') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @include('panel.layout.inputs.slug')

                                            {{-- latin_title--}}
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="title">نام لاتین
                                                        <span class="redAlert">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input name="latin_title" type="text" id="latin_title"
                                                                   class="form-control"
                                                                   placeholder="نام لاتین برند را بنویسید"
                                                                   value="{{ isset($find) ? $find->latin_title : old('latin_title') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- description --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="title">توضیحات
                                                    </label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                    <textarea rows="7" placeholder="توضیحات را وارد نمایید"
                                                              name="description"
                                                              class="form-control ckeditor">{{ isset($find) ? $find->description : null }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-lg-1 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="status">تاپ برند ؟</label>
                                                </div>
                                                <div class="demo-switch">
                                                    <div class="switch">
                                                        <label>بله
                                                            <input name="top" type="checkbox"
                                                                   @if(isset($find) && !empty($find) && $find->top == 1) checked @endif>
                                                            <span class="lever"></span>خیر</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="status"> برند های جدید؟</label>
                                                </div>
                                                <div class="demo-switch">
                                                    <div class="switch">
                                                        <label>بله
                                                            <input name="new" type="checkbox"
                                                                   @if(isset($find) && !empty($find) && $find->new == 1) checked @endif>
                                                            <span class="lever"></span>خیر</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="status"> برند ویژه؟</label>
                                                </div>
                                                <div class="demo-switch">
                                                    <div class="switch">
                                                        <label>بله
                                                            <input name="special" type="checkbox"
                                                                   @if(isset($find) && !empty($find) && $find->special == 1) checked @endif>
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
                                        </div>
                                        @include('panel.layout.inputs.seo')
                                        <div role="tabpanel" class="tab-pane fade" id="messages">
                                            <b>گالری تصاویر</b>

                                            {{-- image --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="status">تصویر
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
                                                                   name="filepath[]"
                                                                   value="{{ isset($find->image[0]) && !empty($find->image)  ? $find->image[0]->url : null }}">
                                                        </div>
                                                        <img id="holder2" style="margin-top:15px;max-height:100px;">
                                                        <div class="col-md-3">
                                                            <span id="plusImage" class="btn btn-xs btn-info">+</span>
                                                            <span id="minImage" class="btn btn-xs btn-danger">-</span>
                                                        </div>
                                                        <br>
                                                        @if(isset($find) && isset($find->image[0]) && count($find->image) > 0)
                                                            <img src="{{  $find->image[0]->url  }}" id="holder2"
                                                                 style="margin-top:15px;max-height:100px;">

                                                        @endif

                                                    </div>

                                                    <br>
                                                    {{-- append image input for gallery --}}
                                                    <div class="resultGalleryImage">
                                                    </div>
                                                </div>
                                            </div>


                                            @if(isset($find))
                                                @can('panel.brand.update')
                                                    {{-- button --}}
                                                    <div class="row clearfix">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                            <button type="submit" class="btn-hover color-1 pull-left">
                                                                ویرایش
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endcan
                                            @else
                                                @can('panel.brand.store')
                                                    {{-- button --}}
                                                    <div class="row clearfix">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                            <button type="submit" class="btn-hover color-1 pull-left">
                                                                ذخیره
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endcan
                                            @endif

                                        </div>
                                    </div>


                                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Horizontal Layout -->

@endsection
@section('admin-js')
    @include('panel.layout.ckjs')
    {{-- button add or delete image and add image input when edit Model --}}
    <script>
        $(document).ready(function () {
            var plusImage = $('#plusImage');
            var minImage = $('#minImage');
            var result = $('.resultGalleryImage');
            var count = 5;

            /* add image */
            plusImage.click(function (e) {
                count++;
                e.preventDefault();
                result.append("<div id='gallery' data-att='" + count + "' class=\"form-group \">\n" +
                    "                    <label for=\"images\" class=\"control-label col-lg-2\">تصویر\n" +
                    "                    </label>\n" +
                    "                    <div class=\"col-md-6\">\n" +
                    "                        <div class=\"input-group\">\n" +
                    "                            <span class=\"input-group-btn\">\n" +
                    "                                                    <a data-input=\"thumbnail" + count + "\" data-preview=\"holder" + count + "\"\n" +
                    "                                                       class=\"lfm btn btn-primary\">\n" +
                    "                                                      <i class=\"fa fa-picture-o\"></i> انتخاب کنید\n" +
                    "                                                    </a>\n" +
                    "                                                  </span>\n" +
                    "                            <input id=\"thumbnail" + count + "\" class=\"form-control\" type=\"text\"\n" +
                    "                                   name=\"filepath[]\">\n" +
                    "                        </div>\n" +
                    "                    </div>\n" +
                    "<div class=\"col-md-3\">" +
                    "<span id=\"minImage\" data-attrss=\"" + count + "\" class=\"btn btn-xs btn-danger\">-</span>" +
                    "</div>" +
                    "                    <br>\n" +
                    "<div class=\"col-md-11 text-center\">" +
                    "                    <img  id=\"holder" + count + "\" style=\"margin-top:15px;max-height:100px;\">\n" +
                    "</div>" +
                    "                </div>");


                $('.lfm').filemanager('image', {prefix: route_prefix});

                /* remove each input splity */
                $('span').click(function () {
                    var id = $(this).attr('data-attrss');
                    var myDiv = $('div[data-att="' + id + '"]').remove();
                });

            });

            /* delete image */
            minImage.click(function (e) {
                e.preventDefault();
                $('#gallery:last-child').remove();
            });

            /* when edit product mode */

            @if(isset($find) && !empty($find) && count($find->image) > 1)
            var count = 5;
            @foreach($find->image as $key => $itemImages)
                    @if($key != 0)
                count++;
            result.append("<div id='gallery' data-att= " + count + "  class=\"form-group \">\n" +
                "                    <label for=\"images\" class=\"control-label col-lg-2\">تصویر\n" +
                "                    </label>\n" +
                "                    <div class=\"col-md-6\">\n" +
                "                        <div class=\"input-group\">\n" +
                "                            <span class=\"input-group-btn\">\n" +
                "                                                    <a data-input=\"thumbnail" + count + "\" data-preview=\"holder" + count + "\"\n" +
                "                                                       class=\"lfm btn btn-primary\">\n" +
                "                                                      <i class=\"fa fa-picture-o\"></i> انتخاب کنید\n" +
                "                                                    </a>\n" +
                "                                                  </span>\n" +
                "                            <input value=\" {{$itemImages->url}} \" id=\"thumbnail" + count + "\" class=\"form-control\" type=\"text\"\n" +
                "                                   name=\"filepath[]\">\n" +
                "                        </div>\n" +
                "                    </div>\n" +
                "<div class=\"col-md-3\">" +
                "<span  data-attrss=\"" + count + "\" class=\"btn btn-xs btn-danger minImageEx\">-</span>" +
                "</div>" +
                "                    <br>\n" +
                "<div class=\"col-md-11 text-center\">" +
                "                    <img src=\" {{$itemImages->url}} \" id=\"holder" + count + "\" style=\"margin-top:15px;max-height:100px;\">\n" +
                "</div>" +
                "                </div>");
            $('.lfm').filemanager('image', {prefix: route_prefix});

            /* remove each input splity */
            $('span').click(function () {
                var id = $(this).attr('data-attrss');
                var myDiv = $('div[data-att="' + id + '"]').remove();
            });
            @endif
            @endforeach
            @endif

        });
    </script>
@endsection