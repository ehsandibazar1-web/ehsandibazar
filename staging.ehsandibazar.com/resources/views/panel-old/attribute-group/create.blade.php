@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @if(isset($findIdAttributeGroup) )
                       @lang('cms.header-category-attribute-group-edit')
                    @else
                       @lang('cms.header-category-attribute-group-create')
                    @endif
                    <a class="btn btn-primary btn-xs pull-left" href="{{route('panel.attributeGroup.index')}}">@lang('cms.back')</a>
                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')


                <div class="panel-body">
                    <div class=" form">

                        @if(isset($findIdAttributeGroup) )
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.attributeGroup.update' , ['id' => $findIdAttributeGroup->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.attributeGroup.store')}}">

                                        @endif

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">@lang('cms.title') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($findIdAttributeGroup)  ? $findIdAttributeGroup->name : null }}"
                                                       id="title"  name="name" minlength="2" type="text" required/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">label</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($findIdAttributeGroup)  ? $findIdAttributeGroup->label : null }}"
                                                       id="title"  name="label"  type="text" />
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">@lang('cms.status') </label>
                                            <div class="col-lg-10">
                                                <select name="status" class="form-control select-option" id="">
                                                @foreach(\App\Utility\Status::Status() as $key => $value)
                                                    <option value="{{$key}}" {{ isset($findIdAttributeGroup) && $key == $findIdAttributeGroup->status ? 'selected' : null }} >{{$value}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            @if(isset($findIdAttributeGroup) )
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

