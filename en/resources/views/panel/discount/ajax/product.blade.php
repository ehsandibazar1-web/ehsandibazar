<div class="row clearfix morph"  id="product">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
        <label for="title">@lang('cms.products')</label>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
        <select class="form-control p-all select-option js-example-basic-multiple" id="select2-multiple"
                name="discountable_id[]" multiple>
            <option value="">@lang('cms.choose')</option>
            @if (isset($AllProduct) && $AllProduct->count() >0)
                @foreach($AllProduct as  $key => $value)
                    @if(isset( $value->product->title) && !empty( $value->product->title))
                        <option class="direction-style"
                                value="{{ $value->id }}"  {{ isset($data) && in_array($value->id,$data) ? 'selected' : '' }}  > {{ $value->product->title ."-" . $value->attributeTypeValue->value . " - " . \App\Utility\Variation::checkRelationVariation($value->id) . " فروشنده : " . $value->user->name }}  </option>
                    @endif
                @endforeach
            @endif
        </select>
        <button class="btn btn-primary" type="button" onClick="selectAll();"> همه</button>

    </div>
</div>

