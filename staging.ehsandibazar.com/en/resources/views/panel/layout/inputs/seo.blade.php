<div role="tabpanel" class="tab-pane fade" id="seo">
    <b>اطلاعات سئو</b>

    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="title">متای عنوان(عنوان صفحه)
                <span class="redAlert">*</span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            <div class="form-group">
                <div class="form-line">
                    <input name="metaTitle" type="text" id="metaTitle" class="form-control"
                           placeholder="متای عنوان خود را بنویسید"
                           value="{{ isset($find,$find->seo) ? $find->seo->title : old('metaTitle') }}">
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="title">متای توضیحات
                <span class="redAlert">*</span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            <div class="form-group">
                <div class="form-line">
                    <textarea rows="7" placeholder="متای توضیحات را وارد نمایید" name="metaDescription"
                        class="form-control">{{ isset($find,$find->seo) ? $find->seo->description : old('metaDescription') }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="title">متای کلمات کلیدی
                <span class="redAlert"></span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            <div class="form-line">
                <input type="text" class="form-control" data-role="tagsinput"
                       name="metaKeyword"
                       value="{{ isset($find,$find->seo) ? $find->seo->keyword : old('metaKeyword') }}">
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="title">متای canonical
                <span class="redAlert"></span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            <div class="form-group">
                <div class="form-line">
                    <input name="metaCanonical" type="text" id="metaCanonical" class="form-control"
                           placeholder="canonical ..."
                           value="{{ isset($find,$find->seo) ? $find->seo->canonical : old('metaCanonical') }}">
                </div>
            </div>
        </div>
    </div>
    {{-- extra meta --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="extra_meta">متای اضافه
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            <div class="form-group">
                <div class="form-line">
                                                    <textarea
                                                            name="extra_meta">{{ isset($find) ? $find->extra_meta : null }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>