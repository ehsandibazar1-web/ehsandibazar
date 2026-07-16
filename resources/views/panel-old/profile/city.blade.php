<div class="col-lg-6">
    <select class="form-control select-option" name="city_id" id="city">
        <option value="">@lang('cms.choose')</option>
        @if (isset($city) && !empty($city))
            @foreach($city as $item)
                <option value="{{$item->id}}" {{(isset($user_city)) && $item->id == $user_city->city_id ? "selected" : null}}>{{$item->name}}</option>
            @endforeach
        @endif
    </select>
    <span class="focus-input100"></span>
    <span class="symbol-input100 pr-4">
		<i class="fas fa-city" aria-hidden="true"></i>
	</span>
</div>
