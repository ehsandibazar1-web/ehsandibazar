@extends('panel-old.layout.master')

@section('admin-css')
    <link href="{{ url('admin_theme/css/select2.css') }}" rel="stylesheet"/>
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">

                <header class="panel-heading">
                    @lang('cms.header-article')

                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                </header>
                <br>
                <div class="col-md-12">
                    <div class="alert alert-info border-right-info"> @lang('cms.alert-article') </div>
                </div>


                <div class="panel-body">
                    <div class=" form">
                        <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                              action="{{route('panel.article.store')}}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2">@lang('cms.name')</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                            </div>


                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2">@lang('cms.content')</label>
                                <div class="col-lg-10">
                                    <textarea name="body" class="form-control ckeditor"></textarea>
                                </div>
                            </div>


                            {{-- image --}}
                            <div class="form-group ">
                                <label for="images" class="control-label col-lg-2">
                                    @lang('cms.featuring-image')
                                </label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                                  <span class="input-group-btn">
                                                    <a id="lfm" data-input="thumbnail2" data-preview="holder2"
                                                       class="btn btn-primary">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>
                                        <input id="thumbnail2" class="form-control" type="text"
                                               name="filepath">
                                    </div>
                                </div>

                                <img id="holder2" style="margin-top:15px;max-height:100px;">
                            </div>


                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-2">@lang('cms.category')</label>
                                <div class="col-lg-10">
                                    <select class=" form-control select-option" id="lang" name="cat_id">
                                        <option value="">@lang('cms.choose-category')</option>
                                        @foreach($category as $value)
                                            <option value="{{$value->id}}">{{$value->title}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            {{--tags--}}
                            <div class="form-group">
                                <label for="title" class="control-label col-lg-2">برچسب ها </label>
                                <div class="col-lg-10">
                                    <select id="select2-multiple"
                                            class="form-control js-example-basic-multiple"
                                            name="tags[]" multiple="multiple">
                                        {{-- <optgroup label="دسته بندی کاربران">--}}
                                        @foreach($tags as $tag)
                                            <option value="{{$tag->id}}" {{ isset($article) && in_array($tag->id , $article->tags->pluck('id')->toArray()) ? "selected" :null }} >{{ $tag->title}}</option>
                                        @endforeach
                                        {{--  </optgroup>--}}
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <input class="btn btn-success pull-left" type="submit" value="@lang('cms.publish')">
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </section>
        </div>
    </div>

@endsection

@section('admin-js')
    <script src="{{ url('admin_theme/js/select2.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endsection
