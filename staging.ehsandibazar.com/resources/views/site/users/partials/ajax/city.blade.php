<div class="uk-margin" id="result-ajax">
    <div class="uk-form-controls">
    <select class="uk-select city" id="form-stacked-select " name="city_id">
        <option value="">@lang('cms.choose-city')</option>
        @if (isset($city) && !empty($city))
            @foreach($city as $item)
                <option value="{{$item->id}}" {{(isset($user_city)) && $item->id == $user_city->city_id ? "selected" : null}}>{{$item->name}}</option>
            @endforeach
        @endif
    </select>
    </div>
</div>
