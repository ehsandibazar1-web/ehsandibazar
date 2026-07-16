<div class="form-group morph" id="product">
    <label for="cname" class="control-label col-lg-2">@lang('cms.role')</label>
    <div class="col-lg-10">
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
