<div class="row clearfix morph">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
        <label for="status">@lang('cms.brand')</label>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                <select class="form-control select-option js-example-basic-multiple"
                        name="discountable_id[]" multiple>
                    <option value="">@lang('cms.choose')</option>
                    @if (isset($AllBrand))
                        @foreach($AllBrand as $key =>  $value)
                            <option value="{{ $key }}" {{ isset($data) && in_array($key,$data) ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
    </div>
</div>

