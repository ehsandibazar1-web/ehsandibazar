@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @if(isset($findAttributeType) )
                       @lang('cms.header-multi-attribute-edit')
                    @else
                        @lang('cms.header-multi-attribute-create')
                    @endif
                    <a class="btn btn-primary btn-xs pull-left" href="{{route('panel.attribute-type.index')}}">@lang('cms.back')</a>
                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')


                <div class="panel-body">
                    <div class=" form">

                        @if(isset($findAttributeType) )
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.attribute-type.update' , ['id' => $findAttributeType->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.attribute-type.store')}}">

                                        @endif

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="name" class="control-label col-lg-2">@lang('cms.title')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($findAttributeType)  ? $findAttributeType->name : null }}"
                                                       id="name"  name="name" minlength="2" type="text" required/>
                                            </div>
                                        </div>

                                        {{--<div class="form-group ">
                                            <label for="label" class="control-label col-lg-2">label</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($findAttributeType)  ? $findAttributeType->label : null }}"
                                                       id="label"  name="label"  type="text" />
                                            </div>
                                        </div>--}}

                                       {{-- <div class="form-group ">
                                            <label for="label" class="control-label col-lg-2">label</label>
                                            <div class="col-lg-10">
                                                <select name="lang" id="" class="form-control select-option">
                                                    <option  value="0">@lang('cms.choose-lang')</option>
                                                    @foreach(\App\Utility\lang::langEach() as $key =>  $itemLang)
                                                        <option value="{{$key}}">{{ $itemLang  }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>--}}

                                        <div class="form-group ">
                                            <label for="status" class="control-label col-lg-2">@lang('cms.status')</label>
                                            <div class="col-lg-10">
                                                <select name="status" class="form-control select-option" id="status">
                                                @foreach(\App\Utility\Status::Status() as $key => $value)
                                                    <option value="{{$key}}" {{ isset($findAttributeType) && $key == $findAttributeType->status ? 'selected' : null }} >{{$value}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            @if(isset($findAttributeType) )
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

