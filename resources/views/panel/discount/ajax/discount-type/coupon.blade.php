<div class="row clearfix">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
        <label for="title">@lang('cms.coupon-expire_date')
            <span class="redAlert">*</span>
        </label>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
        <div class="form-group">
            <div class="form-line">
                <input id="datepicker1" class="input-small datepicker" name="expire_date" type="text">
                <span class="red-date">{{ isset($time)  && !empty($time) ? \App\Http\Controllers\Admin\DiscountController::convertToJalali((int)$time) : null }}</span>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
        <label for="title">@lang('cms.code-coupon')
            <span class="redAlert">*</span>
        </label>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
        <div class="form-group">
            <div class="form-line">
                <input class=" form-control" name="code" type="text" value=" {{ isset($code) ? $code : '' }}"/>
            </div>
        </div>
    </div>
</div>


<div class="row clearfix alltype">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
        <label for="status">@lang('cms.discount-on')</label>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
        <select class="form-control type select-option"
                name="discountable_type">
            <option value="">@lang('cms.choose-type-discount')</option>
            @foreach(App\Utility\DiscountType::DiscountONEach(isset($coupon) ? $coupon : null) as $key=> $value)
                <option value="{{ $key }}" {{ isset($typeOn) ? App\Utility\DiscountType::SelectedDiscountType($key,$typeOn) : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
