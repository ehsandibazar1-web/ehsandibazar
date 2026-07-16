
<div id="catbrand_group" class="form-group ">
    <label for="catbrand_id" class="control-label col-lg-2">انتخاب  برند </label>
    <div class="col-lg-10">
        <select name="brand_id" class="form-control select-option brand_id"
                id="brand_id">
            <option value="0">-- انتخاب کنید --</option>
            @if(isset($brand) && $brand->count() > 0)
                @foreach($brand as $val)
                    <option value="{{ $val->id }}" {{ isset($brandEdit)  && $val->id == $brandEdit->id ? "selected=selected" : null    }}  >{{ $val->title }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
