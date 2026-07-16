<div class="row clearfix morph" id="product">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
        <label for="status">@lang('cms.role')</label>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
        <select id="select2-multiple" class="form-control select-option js-example-basic-multiple"  name="discountable_id[]" multiple>
        <option value="">@lang('cms.choose')</option>
            @if (isset($AllRole) && $AllRole->count() >0)
                @foreach($AllRole as  $key => $value)
                    <option value="{{ $value->id }}" {{ isset($data) && in_array($value->id,$data) ? 'selected' : '' }}>{{ $value->name }}-{!! $value->label !!}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>


