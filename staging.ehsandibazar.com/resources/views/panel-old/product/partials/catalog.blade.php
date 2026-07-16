<h3 class="h3-desc">@lang('cms.product-pdf')
    <span class="img-catalog-position"><img width="24" src="{{url('admin_theme/img/catalog.png')}}"
                                            alt="catalog"></span>
</h3>
<br>

<p class="alert alert-default border-right-dark"> @lang('cms.alert-catalog') </p>
<p class="alert alert-default border-right-dark"> پی دی اف اول بعنوان پیش نمایش در نظر گرفته میشود</p>


<div class="row">

    <div class="col-md-12">

        {{-- catalog --}}
        <div class="col-md-12">
            <div class="form-group ">
                <label for="catalog" class="control-label col-lg-2">@lang('cms.catalog')
                </label>
                <div class="col-md-6">
                    <div class="input-group">
                            <span class="input-group-btn">
                                                    <a data-input="catalognail3" data-preview="holdcatalog3"
                                                       class="btn btn-primary lfm1">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>
                        <input id="catalognail3" class="form-control input-first-catalog" type="text"
                               value="{{isset($findIdProducts) && !empty($findIdProducts) && isset($findIdProducts->catalog[0]) ? $findIdProducts->catalog[0]->url : null }}"
                               name="catalog[]">

                        <span class="icon-trash trash-icons" id="trashCatalog"></span>

                        <br>

                    </div>
                </div>
                <div class="col-md-3">
                    <span id="plusCatalog" class="btn btn-xs btn-info">+</span>
                    <span id="minCatalog" class="btn btn-xs btn-danger">-</span>
                </div>

                <div class="col-md-3 div-first-catalog">
                    @if (isset($findIdProducts) && !empty($findIdProducts) )
                        @if (isset($findIdProducts->catalog[0]) && !empty($findIdProducts->catalog[0]->url))
                                <a class="pdf" href="{{ $findIdProducts->catalog[0]->url }}">
                                    <span class="margin-pdf"><img src="{{url('admin_theme/img/catalogp.png')}}"
                                                                  width="24" alt=""></span>
                                    @lang('cms.download-product-catalog')
                                </a>
                        @endif
                    @endif
                </div>
                <br>
            </div>

            <br>
        </div>

        {{-- append catalog input for product catalog --}}
        <div class="resultGalleryCatalog">
        </div>

    </div>


</div>

<br><br>


{{-- button --}}
<div class="form-group">

    <div class="finish-product" style="display: block">
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
    </div>

    <div class="col-lg-12 nextToAuction" style="display: none">
            <span class="btn btn-info pull-left" id="nextToAuction">
                @lang('cms.next')
            </span>
    </div>

    <div class="col-lg-12">
            <span class="btn btn-info pull-right" id="previousToVideo">
                @lang('cms.previous')
            </span>
    </div>

</div>
