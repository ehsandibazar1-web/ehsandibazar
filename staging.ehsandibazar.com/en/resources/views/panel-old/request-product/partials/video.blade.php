<h3 class="h3-details">@lang('cms.video')
    <span class="img-video-position"><img width="24" src="{{url('admin_theme/img/video.png')}}"
                                          alt="video"></span>
</h3>


<p class="alert alert-default border-right-dark"> @lang('cms.alert-video') </p>


<div class="row">

<div class="col-md-12">

</div>

    {{-- video --}}
    <div class="col-md-12">
        <div class="form-group ">
            <label for="video" class="control-label col-lg-2">@lang('cms.video')
            </label>
            <div class="col-md-6">
                <div class="input-group">
                            <span class="input-group-btn">
                                                    <a data-input="videonail3" data-preview="hold3"
                                                       class="btn btn-primary lfm1">
                                                      <i class="fa fa-picture-o"></i>@lang('cms.choose')
                                                    </a>
                                                  </span>

                    <input id="videonail3" class="form-control input-first-video" type="text"
                           value="{{ isset($findIdProducts) && isset($videos) &&  !empty($videos[0]) && file_exists(base_path().$videos[0]) ? $videos[0] : null  }}"
                           name="video[]">


                    <span class="icon-trash trash-icons" id="trashVideo"></span>
                </div>
            </div>
            <div class="col-md-3">
                <span id="plusVideo" class="btn btn-xs btn-info">+</span>
                <span id="minVideo" class="btn btn-xs btn-danger">-</span>
            </div>
            <br>
        </div>

        <div class="row div-first-video">
            <div class="col-md-12 text-center">
                @if (isset($findIdProducts))
                    @if(isset($videos) && !empty($videos[0]))
                        @if(file_exists(base_path().$videos[0]))
                            <video width="200" height="150" controls>
                                <source src="{{ $videos[0] }}">
                            </video>
                        @else
                            <img width="50" src="{{url('general/img/404-error.png')}}" alt="inten">
                        @endif
                    @endif
                @endif
            </div>
        </div>

        <br>


        {{-- append video input for product video --}}
        <div class="resultGalleryVideo">
        </div>


    </div>

</div>


<br>

{{-- button --}}
<div class="form-group">

    <div class="row">
        <div class="col-lg-12">
            <div class="col-md-2 text-center pull-left">
                <span class="btn btn-info pull-left" id="nextToCatalog">
                    @lang('cms.next')
                </span>
            </div>
            <div class="col-md-2 text-center pull-right">
                <span class="btn btn-info pull-right"
                      id="prevToGallery">
                    @lang('cms.previous')
                </span>
            </div>
        </div>
    </div>

</div>

