<div class="col-sm-12 col-md-12" id="result-ajax">
    <div class="form-account-title">شهر</div>
    <div class="form-account-row">
        <select class="input-field text-right city" name="city_id">
            <option value="">@lang('cms.choose-city')</option>
            @if (isset($city) && !empty($city))
                @foreach($city as $item)
                    <option value="{{$item->id}}" {{(isset($user_city)) && $item->id == $user_city->city_id ? "selected" : null}}>{{$item->name}}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
