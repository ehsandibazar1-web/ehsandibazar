@extends('panel-old.layout.master')

@section('admin-css')
    <link rel="stylesheet" href="{{url('admin_theme/css/colorPicker/colorpicker.css')}}">
    <link rel="stylesheet" href="{{url('admin_theme/css/colorPicker/layout.css')}}">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @if(isset($findAttributeTypeValue) )
                        @lang('cms.header-multi-attribute-edit')
                    @else
                        @lang('cms.header-multi-attribute-create')
                    @endif
                    <a class="btn btn-primary btn-xs pull-left" href="{{route('panel.attribute-type-value.index')}}">برگشت</a>
                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')


                <div class="panel-body">
                    <div class=" form">

                        @if(isset($findAttributeTypeValue) )
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.attribute-type-value.update' , ['id' => $findAttributeTypeValue->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.attribute-type-value.store')}}">

                                        @endif

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="value"
                                                   class="control-label col-lg-2">@lang('cms.value') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($findAttributeTypeValue)  ? $findAttributeTypeValue->value : null }}"
                                                       id="value" name="value" type="text" required/>
                                            </div>
                                        </div>

                                        {{-- lang --}}
                                        <div class="form-group ">
                                            <label for="lang" class="control-label col-lg-2">@lang('cms.lang')</label>
                                            <div class="col-md-10">
                                                <select name="lang" id="lang" class="form-control select-option">
                                                    <option value=""> @lang('cms.choose-lang') </option>
                                                    @foreach(\App\Utility\lang::langEach() as $key => $itemLang)
                                                        <option {{ isset($findAttributeTypeValue) && $findAttributeTypeValue->lang == $itemLang ? "selected" : null  }} value="{{$key}}">{{$itemLang}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- variation --}}
                                        <div class="form-group">
                                            <label for="attribute_type_id"
                                                   class="control-label col-lg-2">@lang('cms.category')</label>
                                            <div class="col-md-10">
                                                <div class="col-md-11">
                                                    <select class="form-control select-option" name="attribute_type_id"
                                                            id="attribute_type_id">
                                                        <option value="">@lang('cms.choose')</option>
                                                        @if(isset($allAttributeType) && count($allAttributeType) > 0))
                                                        @foreach($allAttributeType as $itemAttributeType)
                                                            <option
                                                                value="{{$itemAttributeType->id}}" {{ isset($findAttributeTypeValue) &&  $itemAttributeType->id == $findAttributeTypeValue->attribute_type_id ? "selected" : null  }} >{{$itemAttributeType->name}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                {{-- color --}}
                                                <div class="col-md-1">
                                                    <div id="colorSelector">
                                                        <div id="default-color" style="background-color: #08c9ff">
                                                            <input type="hidden" id="colorPicks" name="color" value="#08c9ff">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        {{-- status --}}
                                        <div class="form-group ">
                                            <label for="status"
                                                   class="control-label col-lg-2">@lang('cms.status') </label>
                                            <div class="col-lg-10">
                                                <select name="status" class="form-control select-option" id="status">
                                                    @foreach(\App\Utility\Status::Status() as $key => $value)
                                                        <option
                                                            value="{{$key}}"  {{ isset($findAttributeTypeValue) && $key == $findAttributeTypeValue->status ? 'selected' : null }} >{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- submit --}}
                                        <div class="form-group">
                                            @if(isset($findAttributeTypeValue) )
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-warning pull-left" type="submit"
                                                           value="@lang('cms.edit')">
                                                </div>
                                            @else
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-success pull-left" type="submit"
                                                           value="@lang('cms.save')">
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

@section('admin-js')
    <script src="{{url('admin_theme/js/colorPicker/colorpicker.js')}}"></script>
    <script src="{{url('admin_theme/js/colorPicker/eye.js')}}"></script>
    <script src="{{url('admin_theme/js/colorPicker/layout.js')}}"></script>
    <script src="{{url('admin_theme/js/colorPicker/utils.js')}}"></script>
    <script>

        @if(isset($findAttributeTypeValue) && !empty($findAttributeTypeValue))

            @if($findAttributeTypeValue->attribute_type_id == \App\Utility\Variation::COLOR)

                $('#colorSelector').css('display','block');
                $('#default-color').css('background-color',"<?= $findAttributeTypeValue->color ?>");
                 $('#colorSelector').ColorPicker({
            color: '#08c9ff',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $('#colorSelector div').css('backgroundColor', '#' + hex);
                var colorChoose =  "#"+hex;
                $('#colorPicks').val(colorChoose);
            }

        });
            @endif
        @endif

        var changeCat = $('#attribute_type_id');
        changeCat.change(function () {
            /* when default */
            var selecteds = $("#attribute_type_id option:selected").val();
            if (selecteds == 1) {
                $('#colorSelector').css('display','block');
                $('#colorSelector').ColorPicker({
                    color: '#08c9ff',
                    onShow: function (colpkr) {
                        $(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        $(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        $('#colorSelector div').css('backgroundColor', '#' + hex);
                        var colorChoose =  "#"+hex;
                        $('#colorPicks').val(colorChoose);
                    }

                });


            }else{
                $('#colorSelector').css('display','none');
            }
        });

    </script>
@endsection

