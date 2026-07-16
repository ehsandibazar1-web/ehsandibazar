<div class="row clearfix">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
        <label for="slug">اسلاگ/ آدرس صفحه
            <span class="redAlert">*</span>
        </label>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
        <div class="form-group">
            <div class="form-line">
                <input name="slug" type="text" id="{{ isset($find) && !empty($find) ? null : 'slug' }}"
                       class="form-control"
                       placeholder="آدرس صفحه"
                       value="{{ isset($find) ? $find->slug : old('slug') }}">
            </div>
        </div>
    </div>
</div>