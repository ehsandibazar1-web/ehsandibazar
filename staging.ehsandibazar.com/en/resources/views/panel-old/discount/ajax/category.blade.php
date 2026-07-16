<div class="form-group morph">
    <label for="cname" class="control-label  col-lg-2">@lang('cms.category')</label>
    <div class="col-lg-10">
        <select id="select2-multiple" class="form-control select-option js-example-basic-multiple"
                name="discountable_id[]" multiple>
            <option value="">@lang('cms.choose-category')</option>

            @if (isset($AllCategory))
                @foreach ($AllCategory as $key => $value)
                    <option
                        value="{{ $key }}" {{ isset($data) && in_array($key,$data) ? 'selected' : '' }}>{{ $value }}
                    </option>
                @endforeach
            @endif

        </select>

    </div>
</div>
