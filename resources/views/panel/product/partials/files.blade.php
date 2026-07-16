<h3 class="h3-details mt-5">@lang('cms.file')
    <span class="img-video-position"><img width="24" src="{{url('admin_theme/img/video.png')}}"
                                          alt="video"></span>
</h3>
<br>


<p class="alert alert-warning border-right-dark"> @lang('cms.alert-video') </p>
{{--<p class="alert alert-info border-right-dark"> لطفا اولین ویدیو یا ویس را بعنوان پیش نمایش وارد نمایید و فایل دوم فایل--}}
{{--    کامل باشد</p>--}}


<div class="row">

    <div class="col-md-12">

        {{-- video --}}
        <div class="col-md-12">
            <div class="form-group ">
                <label for="video" class="control-label col-lg-2">@lang('cms.video') | @lang('cms.voice')
                </label>
               <div class="row">
                   <div class="col-md-6">
                       <div class="input-group">
                            <span class="input-group-btn">
                                <a data-input="videonail3" data-preview="hold3"
                                   class="btn btn-primary lfm1">
                                  <i class="fa fa-picture-o"></i>@lang('cms.choose')
                                </a>
                            </span>

                           <input id="videonail3" class="form-control input-first-video" type="text"
                                  value="{{ isset($findIdProducts) && count($findIdProducts->video) > 0 && isset($findIdProducts->video[0]) ?  $findIdProducts->video[0]->url : null }}"
                                  name="video[]">


                           <span class="icon-trash trash-icons" id="trashVideo"></span>
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="input-group">
                           <input  class="form-control" type="text"
                                  value="{{ isset($findIdProducts) && count($findIdProducts->video) > 0 && isset($findIdProducts->video[0]) ?  $findIdProducts->video[0]->title : null }}"
                                  name="video_title[]" placeholder="تیتر/عنوان ویدیو ...">
                       </div>
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
                        @if(isset($findIdProducts->video[0]) && count($findIdProducts->video) > 0)
                            <video width="200" height="150" controls>
                                <source src="{{ asset($findIdProducts->video[0]->url) }}">
                            </video>
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

</div>

