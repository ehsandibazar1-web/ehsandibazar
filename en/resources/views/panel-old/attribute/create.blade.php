@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @if(isset($findIdAttribute) )
                        @lang('cms.header-attribute-edit')
                    @else
                        @lang('cms.header-attribute-create')
                    @endif
                    <a class="btn btn-primary btn-xs pull-left" href="{{route('panel.attribute.index')}}">@lang('cms.back')</a>
                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')


                <div class="panel-body">
                    <div class=" form">

                        @if(isset($findIdAttribute) )
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.attribute.update' , ['id' => $findIdAttribute->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.attribute.store')}}">

                                        @endif

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="name" class="control-label col-lg-2">@lang('cms.title')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($findIdAttribute)  ? $findIdAttribute->name : null }}"
                                                       id="name"  name="name" minlength="2" type="text" required/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="label" class="control-label col-lg-2">label</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($findIdAttribute)  ? $findIdAttribute->label : null }}"
                                                       id="label"  name="label"  type="text" />
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="label" class="control-label col-lg-2">@lang('cms.attribute-category')</label>
                                            <div class="col-lg-10">

                                                <select name="attribute_group_id" class="form-control select-option" id="">

                                                    <option value="">@lang('cms.choose-category')</option>
                                                    @foreach($attributeAllGroup as $item)
                                                        <option value="{{$item->id}}" {{ isset($findIdAttribute)  ? "selected" : null  }} >{{ $item->name }}</option>
                                                    @endforeach

                                                </select>

                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="status" class="control-label col-lg-2"> @lang('cms.status') </label>
                                            <div class="col-lg-10">
                                                <select name="status" class="form-control select-option" id="status">
                                                    @foreach(\App\Utility\Status::Status() as $key => $value)
                                                        <option value="{{$key}}" {{ isset($findIdAttribute) && $key == $findIdAttribute->status ? 'selected' : null }} >{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>




                                        <div class="form-group">
                                            <div class="col-md-2">
                                                <label for=""> فیلتر </label>
                                            </div>
                                            <div class="col-md-10">
                                                <input {{ isset($findIdAttribute) && $findIdAttribute->is_filter == 1 ? "checked" : ""  }} type="checkbox" name="filterCheck" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            @if(isset($findIdAttribute) )
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

