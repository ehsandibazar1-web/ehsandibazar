@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @if(isset($category) && count($category) > 0)
                       @lang('cms.header-category-edit')
                    @else
                       @lang('cms.header-category-create')
                    @endif


                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')


                <div class="panel-body">
                    <div class=" form">

                        @if(isset($find) )
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.category.update' , ['id' => $find->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.category.store')}}">

                                        @endif

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">@lang('cms.title') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($find)  ? $find->title : null }}"
                                                       id="title"  name="title" minlength="2" type="text" required/>
                                            </div>
                                        </div>


                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2 ">@lang('cms.choose-categories')</label>
                                            <div class="col-lg-10">
                                                <select name="parent_id" class="form-control col-lg-4 select-option">
                                                    <option value="0"> @lang('cms.main') </option>
                                                    @if(isset($categoryAll) && !empty($categoryAll))
                                                        @foreach($categoryAll as $item)
                                                            <option name="parent_id" value="{{$item->id}}"
                                                            {{ isset($find) && !empty($find) && $item->id == $find->parent_id ? "selected" : null  }}
                                                            >{{$item->title}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            @if(isset($find) )
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

