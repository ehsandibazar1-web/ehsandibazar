<h3 class="h3-desc">@lang('cms.product-catalog')
    <span class="img-catalog-position"><img width="24" src="{{url('admin_theme/img/catalog.png')}}"
                                            alt="catalog"></span>
</h3>

<p class="alert alert-default border-right-dark"> @lang('cms.alert-catalog') </p>

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
                           value="{{isset($findIdProducts) && !empty($findIdProducts->catalog) && isset($findIdProducts->catalog) && file_exists(base_path().$findIdProducts->catalog) ? $findIdProducts->catalog : null }}"
                           name="catalog">

                    <span class="icon-trash trash-icons" id="trashCatalog"></span>
                    <br>

                </div>
            </div>
            <div class="col-md-3 div-first-catalog">

                @if (isset($findIdProducts) && !empty($findIdProducts) )
                    @if (isset($findIdProducts->catalog) && !empty($findIdProducts->catalog))
                        @if(file_exists(base_path().$findIdProducts->catalog))
                            <a class="pdf" href="{{ $findIdProducts->catalog }}">
                                    <span class="margin-pdf"><img src="{{url('admin_theme/img/catalogp.png')}}"
                                                                  width="24" alt=""></span>
                                @lang('cms.download-product-catalog')
                            </a>
                        @else
                            <br>
                            <img width="50" src="{{url('general/img/404-error.png')}}" alt="inten">
                        @endif
                    @endif
                @endif
            </div>
            <br>
        </div>

        <br>


    </div>

</div>
</div>

{{-- button --}}
<div class="form-group">

    @if(isset($findIdProducts) )
        <div class="col-lg-12">
            <input class="btn btn-warning pull-left" type="submit"
                   value="@lang('cms.edit')">
        </div>

    @else
        <div class="col-lg-12">
            <input class="btn btn-success pull-left" type="submit"
                   value="@lang('cms.save')">
        </div>

    @endif

    <div class="col-lg-12">
            <span class="btn btn-info pull-right" id="previousToVideo">
                @lang('cms.previous')
            </span>
    </div>

</div>

