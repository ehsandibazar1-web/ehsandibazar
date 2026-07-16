<h3 class="h3-details mt-5">@lang('cms.details')
    <span class="img-gallery-position"><img width="24" src="{{url('admin_theme/img/extra.png')}}"
                                            alt="details"></span>
</h3>
<br>
<div class="col-md-12">
    <div class="alert alert-warning border-right-dark">
         <span class="marging-left-right">
             @lang('cms.alert-required-fields')

            <img src="{{url('admin_theme/img/fill.png')}}" width="16px" alt="inten" class="margin-right-10"/>
        </span>

    </div>
</div>



<div class="row">
    <div class="col-md-12" id="firstInput">

        <div class="col-md-4 div-line-height">
            <label for="attribute_type_id">@lang('cms.choose-value')</label>
        </div>
        <div class="row">
            <div class="col-md-12">
                <select data-id="0" name="attribute_type_value_id[]"
                        class="form-group select-option attribute_type_value_id js-example-basic-multiple">
                    <option value="">@lang('cms.choose')</option>
                    @if(isset($allAttributeTypeValue) && $allAttributeTypeValue->count() > 0)
                        @foreach($allAttributeTypeValue as $itemAttributeTypeValue)
                            <option
                                    {{ isset($findIdProducts)  && isset($findIdProducts->variations[0]) && $findIdProducts->variations[0]->attribute_type_value_id ==  $itemAttributeTypeValue->id ? "selected" : null }} value="{{$itemAttributeTypeValue->id}}">{{$itemAttributeTypeValue->value}}</option>
                        @endforeach
                    @endif
                </select>
                <img src="{{url('admin_theme/img/fill.png')}}" style='margin-bottom: 5px;margin-top: 5px' alt=""
                     width="16px">
            </div>

            <input type="hidden" name="variationUserId" value="@if(isset($findIdProducts->variations[0]->user_id)) {{ $findIdProducts->variations[0]->user_id }} @else  @if(isset($findIdProducts)) {{$findIdProducts->user_id}} @else 'null' @endif @endif">

            {{-- ajax --}}
            <div class="col-md-12">
                <div id="resultAjaxAllAttributeTypeValue0"></div>
                {{-- <div id="resultRelated"></div>--}}
            </div>
        </div>


        <div class="col-md-2 pull-left margin-top-1">

            <!--<div class="col-md-3 ">-->
        <!--    <span data-toggle="tooltip" data-placement="right" title="@lang('cms.add-field')" class="btn btn-xs btn-primary w-button" id="addAttributeTypeValue">+</span>-->
            <!--</div>-->
            <!--<div class="col-md-2">-->
            <!--    <span style="position: absolute;margin-right: 3px;padding: 2.3px 3px 5px;" class="btn btn-xs btn-danger icon-trash" id="removeAttributeTypeValue"></span>-->
            <!--</div>-->
        </div>


        <div class="row">
            <div class="col-md-12">
                <div id="resultAjaxAllAttributeTypeValue"></div>
            </div>
        </div>

    </div>
    <div class="col-md-12">
        <div id="add-attribute-typeValue"></div>
    </div>

</div>

