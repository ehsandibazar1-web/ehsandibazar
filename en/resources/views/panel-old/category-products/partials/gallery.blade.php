<h3 class="h3-gallery">@lang('cms.pic-gallery')
    <span class="img-gallery-position"><img width="24" src="{{url('admin_theme/img/gallery.png')}}"
                                            alt="gallery"></span>
</h3>
<br>

<p class="alert alert-default border-right-dark"> @lang('cms.alert-pic') </p>


<div class="row">


    <div class="col-md-12">

        {{-- image --}}
        <div class="col-md-12">
            <div class="form-group ">
                <label for="images" class="control-label col-lg-2">@lang('cms.picture')
                </label>
                <div class="col-md-6">
                    <div class="input-group">
                            <span class="input-group-btn">
                                                    <a id="lfm1" data-input="thumbnail3" data-preview="holder3"
                                                       class="btn btn-primary">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>


                                    <input id="thumbnail3" class="form-control input-first-gallery" type="text"
                                           value="{{ isset($findCategoryProductId) && count($findCategoryProductId->image) > 0 &&  isset($findCategoryProductId->image[0])  ?   $findCategoryProductId->image[0]->url : null }}"
                                           name="filepath[]">


                        <span class="icon-trash trash-icons" id="trashImages"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <span id="plusImage" class="btn btn-xs btn-info">+</span>
                    <span id="minImage" class="btn btn-xs btn-danger">-</span>
                </div>
                <br>
                <div class="col-md-11 text-center">
                    @if (isset($findCategoryProductId))
                        @if(count($findCategoryProductId->image) > 0 && isset($findCategoryProductId->image[0]))
                                <img class="div-first-gallery" id="holder3"
                                     src="{{ $findCategoryProductId->image[0]->url }}"
                                     style="margin-top:15px;max-height:100px;">

                            @else
                            <img src="{{url('general/img/404-error.png')}}" width="50" alt="inten">
                        @endif
                        @else
                        <img class="div-first-gallery" id="holder3"
                             src=""
                             style="margin-top:15px;max-height:100px;">
                    @endif
                </div>
            </div>

            <br>


            {{-- append image input for gallery --}}
            <div class="resultGalleryImage">
            </div>

        </div>

    </div>


    <div class="col-md-12">

    </div>

</div>

<br><br>


{{-- button --}}
<div class="form-group">
    <div class="col-lg-12">
        <div class="col-lg-6">
            <input class="btn btn-info pull-right w-100" id="previous"
                   value="@lang('cms.previous')">
        </div>

        @if(isset($findCategoryProductId) )
            <div class="col-lg-6">
                <input class="btn btn-warning pull-left" type="submit"
                       value="@lang('cms.edit')">
            </div>
        @else
            <div class="col-lg-6">
                <input class="btn btn-success pull-left" type="submit"
                       value="@lang('cms.save')">
            </div>
        @endif

    </div>
</div>
