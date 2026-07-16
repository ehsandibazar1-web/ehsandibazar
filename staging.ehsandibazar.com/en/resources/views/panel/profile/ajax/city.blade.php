@if(isset($allCity) && !empty($allCity) && count($allCity) > 0)
    <select style="display: block" name="city_id" id="city_id">
        <option value="">-- شهر خود را وارد نمایید --</option>
        @foreach($allCity as $itemCity)
            <option value="{{ $itemCity->id  }}"> {{ $itemCity->name }} </option>
        @endforeach
    </select>
@endif

