@extends('panel-old.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.header-about-us')
                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')


                <div class="panel-body">
                    <div class=" form">
                        {{--@dd($about)--}}

                        @if(isset($about) && count($about) > 0)
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  action="{{route('about.update' , ['id' => $about->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          action="{{route('about.store')}}">

                                        @endif

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.title')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($about) && count($about) > 0 ? $about->title : null }}"
                                                       id="cname" name="title" minlength="2" type="text" required/>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="cemail" class="control-label col-lg-2">
                                                @lang('cms.content-of-about-us')
                                            </label>
                                            <div class="col-lg-10">
                                                    <textarea name="content"
                                                              class="form-control ckeditor"> {!! isset($about) && count($about) > 0 ?  $about->content  : null !!} </textarea>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            @if(isset($about) && count($about) > 0)
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-warning pull-left" type="submit" value="@lang('cms.edit')">
                                                </div>
                                            @else
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-success pull-left" type="submit" value=" @lang('cms.save')">
                                                </div>
                                            @endif

                                        </div>
                                    </form>

                    </div>

                </div>

            </section>
        </div>
    </div>

@endsection

