<div class="row clearfix">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
        <label for="title">تاریخ شروع
            <span class="redAlert">*</span>
        </label>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
        <div class="form-group">
            <div class="form-line">
                <input id="datepicker1" class="input-small datepicker" name="start_date" type="text">
                <span class="red-date">{{ isset($data)  && !empty($data) ? \App\Http\Controllers\Admin\DiscountController::convertToJalali(\App\Http\Controllers\Admin\DiscountController::TimestampToMiladi((int)$startDate)) : null }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
        <label for="title">@lang('cms.expire_date')
            <span class="redAlert">*</span>
        </label>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
        <div class="form-group">
            <div class="form-line">
                <input id="datepicker1" class="input-small datepicker" name="expire_date" type="text">
                <span class="red-date">{{ isset($data)  && !empty($data) ? \App\Http\Controllers\Admin\DiscountController::convertToJalali(\App\Http\Controllers\Admin\DiscountController::TimestampToMiladi((int)$data)) : null }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
        <label for="title">@lang('cms.discount-on')</label>
    </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 alltype">
            <select class="form-control type select-option" id="lang" name="discountable_type">
                <option value="">@lang('cms.choose-type-discount')</option>
                @foreach(App\Utility\DiscountType::DiscountONAmazingEach() as $key=> $value)
                    <option value="{{ $key }}" {{ isset($typeOn) ? App\Utility\DiscountType::SelectedDiscountType($key,$typeOn) : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
</div>



