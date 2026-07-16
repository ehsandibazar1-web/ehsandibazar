<h3 class="h3-desc">@lang('cms.create-brand')
    <span class="img-gallery-position">
        <img width="36" style="margin-top: -7px" src="{{url('admin_theme/img/brand.png')}}" alt="brand">
    </span>
</h3>


<br>

{{-- name --}}
<div class="form-group ">
    <label for="title" class="control-label col-lg-2">@lang('cms.title')</label>
    <div class="col-lg-10">
        <input class=" form-control"
               value="{{isset($findBrand)  ? $findBrand->title : null }}"
               id="title" name="title" minlength="2" type="text" required/>
    </div>
</div>

{{-- latin title --}}
<div class="form-group ">
    <label for="title" class="control-label col-lg-2">عنوان لاتین</label>
    <div class="col-lg-10">
        <input class=" form-control"
               value="{{isset($findBrand)  ? $findBrand->latin_title : null }}"
               id="latin_title" name="latin_title" minlength="2" type="text" required/>
    </div>
</div>


{{-- description --}}
<div class="form-group">
    <label for="description"
           class="control-label col-lg-2">
        @lang('cms.description')
    </label>
    <div class="col-lg-10">
                                            <textarea name="description" id="description" cols="30"
                                                      rows="10">{{isset($findBrand)  ? $findBrand->description : old('description') }}</textarea>
    </div>
</div>





{{-- sort --}}
<div class="form-group ">
    <label for="sort" class="control-label col-lg-2">@lang('cms.how-to-display') </label>
    <span class="alerts"> @lang('cms.description-category-of-product') </span>
    <div class="col-lg-10">
        <input class=" form-control"
               value="{{isset($findBrand)  ? $findBrand->sort : null }}"
               id="sort" min="0" name="sort" type="number" required/>
    </div>
</div>


{{-- status--}}
<div class="form-group ">
    <label for="title" class="control-label col-lg-2">@lang('cms.status')</label>
    <div class="col-lg-10">
        <select name="status" class="form-control select-option" id="">
            @foreach(\App\Utility\Status::Status() as $key => $value)
                <option
                    value="{{$key}}" {{ isset($findBrand) && $key == $findBrand->status ? 'selected' : null }} >{{$value}}</option>
            @endforeach
        </select>
    </div>
</div>


<div class="form-group">
    <label for="" class="col-md-2">@lang('cms.suggest')</label>

    <div class="col-md-10">
        <div class="col-md-2">
            <label  for="top">تاپ برند</label>
            <input type="checkbox" {{\Illuminate\Support\Facades\Input::old('top') ? "checked" : null}} {{ isset($findBrand) && $findBrand->top == 1 ? "checked"  : null  }} value="top"  id="top" name="top">
        </div>
        <div class="col-md-2">
            <label  for="new">برند های جدید</label>
            <input type="checkbox" {{\Illuminate\Support\Facades\Input::old('new') ? "checked" : null}} {{ isset($findBrand) && $findBrand->new == 1 ? "checked" : null  }} value="new"  id="new" name="new">
        </div>

    </div>

</div>


{{-- button --}}
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <input class="btn btn-info pull-left w-100" id="next"
               value="@lang('cms.next')">
    </div>
</div>

