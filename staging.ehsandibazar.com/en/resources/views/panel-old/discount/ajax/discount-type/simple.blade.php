<div class="form-group alltype">
    <label for="cname" class="control-label col-lg-2">@lang('cms.discount-on')</label>
    <div class="col-lg-10">
        <select class="form-control type select-option" id="lang" name="discountable_type">
            <option value="">@lang('cms.choose-type-discount')</option>
            @foreach(App\Utility\DiscountType::DiscountONEach(null,'simple') as $key=> $value)
                <option value="{{ $key }}"  {{ isset($typeOn) ? App\Utility\DiscountType::SelectedDiscountType($key,$typeOn) : '' }} >{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>

