{{-- images --}}
<div class="row clearfix mt-5">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
        <label for="status">تصاویر گالری
            <span class="redAlert">*</span>
        </label>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
        <div class="form-group">
            <div class="">
                                                    <span class="input-group-btn">
                                                    <a id="lfm1" data-input="thumbnail3" data-preview="holder2"
                                                       class="btn btn-primary">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>
                <input id="thumbnail3" class="form-control" type="text"
                       name="filepath[]"
                       value="{{ isset($findIdProducts->image[0]) && !empty($findIdProducts->image)  ? $findIdProducts->image[0]->url : null }}">
            </div>
            <img id="holder2" style="margin-top:15px;max-height:100px;">
            <div class="col-md-3">
                <span id="plusImage" class="btn btn-xs btn-info">+</span>
                <span id="minImage" class="btn btn-xs btn-danger">-</span>
            </div>
            <br>
            @if(isset($findIdProducts) && isset($findIdProducts->image[0]) && count($findIdProducts->image) > 0)
                <img src="{{  $findIdProducts->image[0]->url  }}" id="holder2"
                     style="margin-top:15px;max-height:100px;">

            @endif

        </div>

        <br>
        {{-- append image input for gallery --}}
        <div class="resultGalleryImage">
        </div>
    </div>
</div>