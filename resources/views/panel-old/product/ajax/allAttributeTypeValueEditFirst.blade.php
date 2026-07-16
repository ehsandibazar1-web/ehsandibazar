{{--@if(isset($allAttributeTypeValue) && !empty($allAttributeTypeValue) && count($allAttributeTypeValue) > 0)--}}


@if(isset($allAttributeTypeValue))
    @if(!empty($allAttributeTypeValue))
    <div class="col-md-4">
            <select name="attribute_type_id_related[]"
                    class="form-control select-option attribute_type_value_related_id js-example-basic-multiple">
                <option value="">-- انتخاب کنید --</option>
                @foreach($allAttributeTypeValue as $itemAttributeTypeValue)
                    <option {{ isset($findIdProducts) && isset($findIdProducts->variations[0]->relatedVariations[0]) && $findIdProducts->variations[0]->relatedVariations[0]->attribute_type_value_id == $itemAttributeTypeValue->id ? "selected" : null  }} value="{{$itemAttributeTypeValue->id}}">{{$itemAttributeTypeValue->value}}</option>
                @endforeach
            </select>
    </div>
        @else
        <div class="col-md-4 display-none">
            <select name="attribute_type_id_related[]"
                    class="form-control select-option attribute_type_value_related_id js-example-basic-multiple">
                <option value="">-- انتخاب کنید --</option>
            </select>
        </div>
    @endif
    @if(!empty($allAttributeTypeValue))
        <div class="col-md-4">
            @else
                <div class="col-md-6">
                    @endif
                    <span class="">
        <input value="{{isset($findIdProducts) ? $findIdProducts->variations[0]->price : null}}" class="form-control input-height" min="0" placeholder="قیمت" type="number" name="prices[]">
        <img src="{{url('admin_theme/img/fill.png')}}" style='margin-bottom: 5px;margin-top: 5px' alt="" width="16px">
        </span>
                </div>

                @if(!empty($allAttributeTypeValue))
                    <div class="col-md-4">
                        @else
                            <div class="col-md-6">
                                @endif
                                <span class="">
        <input value="{{isset($findIdProducts) ? $findIdProducts->variations[0]->count : null}}" class="form-control input-height" min="0" placeholder="موجودی" type="number" name="countes[]">
        <img src="{{url('admin_theme/img/fill.png')}}" style='margin-bottom: 5px;margin-top: 5px' width="16px" alt="">
        </span>
                            </div>

                            <br>
                            <br>
                            <br>

                        {{-- image --}}
{{--                        <div class="imageVariation">--}}
{{--                            <div class="col-md-12">--}}
{{--                                <div class="col-md-12">--}}
{{--                                    <div class="input-group">--}}
{{--                                                  <span class="input-group-btn">--}}
{{--                                                    <a  data-input="thumbnail3" data-preview="holder3"--}}
{{--                                                       class="btn btn-primary lfm">--}}
{{--                                                      <i class="fa fa-picture-o"></i>--}}
{{--                                                        @lang('cms.choose')--}}
{{--                                                    </a>--}}
{{--                                                  </span>--}}

{{--                                        <input id="thumbnail3" class="form-control" type="text"--}}
{{--                                               value=""--}}
{{--                                               name="filepath[]">--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <br>--}}
{{--                                <br>--}}
{{--                                <br>--}}

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-12 text-center">--}}
{{--                                        <img id="holder3" src="" style="margin-top:15px;max-height:100px;">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <img id="holder3" style="margin-top:15px;max-height:100px;">--}}

{{--                                --}}{{-- image --}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{-- image --}}


        <div style='width: 784px;margin-top : 20px; margin-right: -25px' class="col-md-12 text-center col-md-pull-4">
            <textarea id="descss" placeholder="توضیحات اضافی"  class="form-control  t "
                      name="desc[]"  cols="30" rows="10">{{isset($findIdProducts) ? $findIdProducts->variations[0]->description : null}}</textarea>
        </div>
    @endif



