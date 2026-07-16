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
                               value="{{ isset($findIdProducts) && count($findIdProducts->image) > 0 && isset($findIdProducts->image[1])  ?   $findIdProducts->image[1]->url  : null }}"
                               name="filepath[]">
                        <br>


                        <span class="icon-trash trash-icons" id="trashImages"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <span id="plusImage" class="btn btn-xs btn-info">+</span>
                    <span id="minImage" class="btn btn-xs btn-danger">-</span>
                </div>
                <br>
                <div class="col-md-11 text-center">

                    @if (isset($findIdProducts))
                        @if(count($findIdProducts->image) > 0 && isset($findIdProducts->image[1]))
                                <img class="div-first-gallery" id="holder3"
                                     src="{{ $findIdProducts->image[1]->url }}"
                                     style="margin-top:15px;max-height:100px;">

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
            <span class="btn btn-info pull-left" id="nextToVideo">
                @lang('cms.next')
            </span>
    </div>

    <div class="col-lg-12">
            <span class="btn btn-info pull-right" id="previousToDetails">
                @lang('cms.previous')
            </span>
    </div>

</div>
