@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @if(isset($findTag) && !empty($findTag))
                       @lang('cms.header-list-tag-edit')
                    @else
                        @lang('cms.header-list-tag-create')
                    @endif

                    <a class="btn btn-xs btn-primary pull-left" href="{{route('panel.tag.index')}}"> @lang('cms.show-list-tag')</a>
                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')


                <div class="panel-body">
                    <div class=" form">

                        @if(isset($findTag) && !empty($findTag))
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.tag.update' , ['id' => $findTag->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.tag.store')}}">

                                        @endif

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">@lang('cms.title') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($findTag) && !empty($findTag) ? $findTag->title : null }}"
                                                       id="title"  name="title" minlength="2" type="text" required/>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            @if(isset($findTag) && !empty($findTag) > 0)
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-warning pull-left" type="submit" value="@lang('cms.edit')">
                                                </div>
                                            @else
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-success pull-left" type="submit" value="@lang('cms.save')">
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

