<h3 class="h3-desc">@lang('cms.request-new-product')
    <span class="img-gallery-position">
        <img width="24" src="{{url('admin_theme/img/barcode.png')}}" alt="gallery">
    </span>
</h3>
<br>




<p class="alert alert-default border-right-dark"> @lang('cms.description-request-product') <a><i
            class="icon-question question margin-right-1"></i></a></p>

<p class="alert alert-info border-right-info"> موارد ستاره دار الزامی می باشند. </p>


<div class="row">

    <div class="col-md-12">

        <div class="form-group">
            <div class="col-md-2">
                <label for="product_id"> @lang('cms.choose-product') </label>
                <span class="red">*</span>
                <a href="#showQuestion" data-toggle="modal"><i class="icon-question question"></i></a>
            </div>
            <div class="col-md-10">
                <select class="form-control select-option js-example-basic-multiple" name="product_id" id="product_id">
                    <option value=""> @lang('cms.choose') </option>
                    <option class="unavailables"   {{ isset($findIdProducts) && !empty($findIdProducts) && $findIdProducts->product_id == 0 ? "selected" : ""   }}  name="product_id" value="0">@lang('cms.unavailable-product-in-list')</option>
                    @if(isset($allProduct) && count($allProduct) > 0)
                        @foreach($allProduct as $itemProduct)
                            <option value="{{$itemProduct->id}}"  {{ isset($findIdProducts) && !empty($findIdProducts) && $findIdProducts->product_id != 0 && $findIdProducts->product_id == $itemProduct->id ? "selected" : ""   }} >{{$itemProduct->title}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="clearfix"></div>

        <div id="result-found-product">
            {{-- result found product request user --}}
        </div>

        <div class="form-group desc-product">
            <div class="row col-md-12 col-md-push-5">
                <a class="example11" href="#">
                    <div class="col-md-2">نمونه1 : <span><i><img width="32"
                                                                 src="{{url('admin_theme/img/question-product.png')}}"
                                                                 alt="question"></i></span></div>
                </a>
                <a class="example22" href="#" data-toggle="modal">
                    <div class="col-md-2">نمونه2 : <span><i><img width="32"
                                                                 src="{{url('admin_theme/img/question-product.png')}}"
                                                                 alt="question"></i></span></div>
                </a>
                <a class="example33" href="#" data-toggle="modal">
                    <div class="col-md-2">نمونه3 : <span><i><img width="32"
                                                                 src="{{url('admin_theme/img/question-product.png')}}"
                                                                 alt="question"></i></span></div>
                </a>
            </div>
            <br>

            @include('panel-old.request-product.partials.example.example1')
            @include('panel-old.request-product.partials.example.example2')
            @include('panel-old.request-product.partials.example.example3')

            <br>

            <div class="col-md-2">
                <label for="">@lang('cms.description')</label>
                <span class="red">*</span>
                <a href="#showDescription" data-toggle="modal"><i class="icon-question question"></i></a>
            </div>


            <div class="col-md-10">
                <textarea name="description" id="" cols="30" rows="10" placeholder="@lang('cms.description')">{{isset($findIdProducts) && !empty($findIdProducts->description) ? $findIdProducts->description : null }}</textarea>
            </div>

            <hr>

            <div id="details-request">
                <div class="clearfix"></div>
                <br>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label for="">@lang('cms.details')</label>
                            <span class="red">*</span>
                            <a href="#showDetails" data-toggle="modal"><i class="icon-question question"></i></a>
                        </div>

                        <div class="col-md-10">
                            <textarea name="details" id="" cols="30" rows="10">{{isset($findIdProducts) && !empty($findIdProducts->details) ? $findIdProducts->details : null }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<br>
{{-- button --}}
<div class="form-group">

    <div class="col-lg-offset-2 col-lg-10">
            <span class="btn btn-info pull-left" id="next">
                @lang('cms.next')
            </span>
    </div>
</div>

