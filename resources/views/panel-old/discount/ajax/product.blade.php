<style>
    .selection {
    width: 100%;
    float: right;
    max-height: 200px;
    overflow-y: auto;
    height: 200px;
}
.box-select {
    width: 100%;
    float: right;
    overflow: hidden;
    min-height: 200px;
}
</style>
<div class="form-group morph" id="product">
    <label for="cname" class="control-label col-lg-2">@lang('cms.products')</label>
    <div class="col-lg-10">
       <div class="row">
           <div class="col-xs-12 p-0">
               <div class="box-select">
                    <select id="select2-multiple" class="p-all form-control select-option js-example-basic-multiple"
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
               </div>
           </div>
       </div>

        <div class="row mt-10 mt-20 row-btn">
            <div class="col-xs-12">
                <button class="btn btn-primary" type="button" onClick="selectAll();"> همه</button>
            </div>
        </div>

    </div>
</div>
