<h3 class="h3-desc">مزایده
    <span class="img-catalog-position">
        <img width="24" src="{{url('admin_theme/img/polling.png')}}" alt="poll">
    </span>
</h3>
<br>

<div class="row">

    <div class="col-md-12">
        <p class="alert alert-default border-right-dark">مبلغ هر کلیک بصورت اتوماتیک حساب میشود</p>

        {{-- auction --}}
        <div class="col-md-12">


            {{-- Start date --}}
            <div class="form-group ">
                <label for="start_date" class="control-label col-lg-2">
                    تاریخ شروع
                    <span class="red">*</span>
                </label>
                <div class="col-lg-8">
                    <div class="col-xs-10 margin-right-p">
                        <input
                            value="{{ old('start_date') }}"
                            id="datepicker1" name="start_date"
                            class="form-control expire_date_value start_date"
                            type="text">
                        <span class="red-date">{{ isset($findIdProducts)  && isset($findIdProducts->auction) && !empty($findIdProducts) ? \App\Http\Controllers\Admin\ProductController::convertToJalali((int)$findIdProducts->auction->start_date) : null }}</span>
                    </div>

                    <div class="col-xs-2">
                        <button id="datepicker1btn" class="btn" type="button"><i
                                class="icon-calendar"></i></button>
                    </div>

                </div>

            </div>
            {{-- Start date --}}


            {{-- Start Price --}}
            <div class="form-group ">
                <label for="start_price" class="control-label col-lg-2">
                    قیمت شروع
                    <span class="red">*</span>
                </label>
                <div class="col-lg-8">
                    <div class="col-xs-10 margin-right-p">
                        <input
                            value="{{ isset($findIdProducts)  && isset($findIdProducts->auction) && !empty($findIdProducts) ? $findIdProducts->auction->start_price : old('start_price') }}"
                            name="start_price"
                            class="form-control expire_date_value"
                            type="number">
                    </div>
                </div>

            </div>
            {{-- Start Price --}}

            {{-- End Price --}}
            <div class="form-group ">
                <label for="end_price" class="control-label col-lg-2">
                    قیمت پایان
                    <span class="red">*</span>
                </label>
                <div class="col-lg-8">
                    <div class="col-xs-10 margin-right-p">
                        <input
                            value="{{ isset($findIdProducts)  && isset($findIdProducts->auction) && !empty($findIdProducts) ? $findIdProducts->auction->end_price : old('end_price') }}"
                            name="end_price"
                            class="form-control expire_date_value"
                            type="number">
                    </div>
                </div>

            </div>
            {{-- Edn Price --}}

            {{-- participant count --}}
            <div class="form-group ">
                <label for="participant_count" class="control-label col-lg-2">
                    تعداد شرکت کنندگان
                    <span class="red">*</span>
                </label>
                <div class="col-lg-8">
                    <div class="col-xs-10 margin-right-p">
                        <input
                            value="{{ isset($findIdProducts)  && isset($findIdProducts->auction) && !empty($findIdProducts) ? $findIdProducts->auction->participant_count : old('participant_count') }}"
                            name="participant_count"
                            class="form-control"
                            type="number">
                    </div>
                </div>

            </div>
            {{-- participant count --}}

            {{-- every_click_price --}}
            <div class="form-group ">
                <label for="click_price" class="control-label col-lg-2">
                    مبلغ افزایش قیمت هر کلیک
                    <span class="red">*</span>
                </label>
                <div class="col-lg-8">
                    <div class="col-xs-10 margin-right-p">
                        <input
                            value="{{ isset($findIdProducts) && isset($findIdProducts->auction) &&!empty($findIdProducts) ? $findIdProducts->auction->every_click_price : old('every_click_price') }}"
                            name="every_click_price"
                            class="form-control"
                            type="number" placeholder="مبلغ افزایش قیکت هر کلیک در مزایده">
                        <span class="red">مبلغ هر کلیک برای پرداخت کاربر بصورت اتوماتیک حساب میشود</span>
                    </div>
                </div>

            </div>
            {{-- every_click_price --}}

            @if(isset($findIdProducts) && isset($findIdProducts->auction) && !empty($findIdProducts) && !empty($findIdProducts->auction))
                {{-- every_click_price_for_pay --}}
                <div class="form-group ">
                    <label for="click_price" class="control-label col-lg-2">مبلغ هر کلیک برای پرداخت کاربر :</label>
                    <div class="col-lg-8">
                        <div class="col-xs-10 margin-right-p">
                            <input
                                value="{{ isset($findIdProducts)  && isset($findIdProducts->auction) && !empty($findIdProducts->auction) &&!empty($findIdProducts) ? $findIdProducts->auction->every_click_price_for_pay : old('every_click_price_for_pay') }}"
                                class="form-control"
                                type="number" disabled>
                        </div>
                    </div>

                </div>
                {{-- every_click_price_for_pay --}}
            @endif

            <br>
        </div>

    </div>


</div>

<br><br>


{{-- button --}}
<div class="form-group">

    @if(isset($findIdProducts) )
        <div class="col-lg-12">
            <input class="btn btn-warning pull-left submit-product" type="submit"
                   value="@lang('cms.edit')">
        </div>

    @else
        <div class="col-lg-12">
            <input class="btn btn-success pull-left submit-product" type="submit"
                   value="@lang('cms.save')">
        </div>
    @endif

    <div class="col-lg-12">
            <span class="btn btn-info pull-right" id="previousToCatalog">
                @lang('cms.previous')
            </span>
    </div>

</div>
