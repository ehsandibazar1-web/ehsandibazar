{{--<h3 class="h3-desc">مشخصات کلی
    <span class="img-gallery-position"><img width="24" src="{{url('admin_theme/img/barcodecode.png')}}"
                                            alt="gallery"></span>
</h3>--}}

<h3 class="h3-desc">@lang('cms.all-details')
    <span class="img-gallery-position">
        <img width="24" src="{{url('admin_theme/img/barcode.png')}}" alt="gallery">
    </span>
</h3>


<br>


<div class="row">
    <div class="col-md-12">
        @if(isset($findIdProducts)  )
            <div class="col-md-11">
                @else
                    <div class="col-md-12">
                        @endif
                        <p class="alert alert-default border-right-dark">
                            @lang('cms.alert-pic')
                        </p>
{{--                        <p class="alert alert-info border-right-info"> @lang('cms.alert-date') </p>--}}
                    </div>
                    <div class="col-md-1">
                        <div>
                            @if(isset($findIdProducts)  )
                                <span> <img width="100"
                                            src="{{ url('public/upload/qr/'.$findIdProducts->slug.".png") }}"
                                            alt=""> </span>
                            @endif
                        </div>
                    </div>
            </div>
    </div>





    <div class="form-group ">
        <label for="title" class="control-label col-lg-2">
            @lang('cms.type')
            <span class="red">*</span>
        </label>
        <div class="col-lg-10">
            <select class="form-control" name="type" onchange="auction(this.options[this.selectedIndex].value)">
                @foreach(\App\Utility\ProductType::typeEach() as $key => $type)
                    <option value="{{$key}}" {{isset($findIdProducts) && $findIdProducts->type == $key  ? 'selected' : null }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group ">
        <label for="title" class="control-label col-lg-2">
            @lang('cms.title')
            <span class="red">*</span>
        </label>
        <div class="col-lg-10">
            <input class=" form-control"
                   value="{{isset($findIdProducts)  ? $findIdProducts->title : old('title') }}"
                   id="title" name="title" minlength="2" type="text"
                   required/>
        </div>
    </div>


    {{-- image --}}
    <div class="form-group">
        <label for="images" class="control-label col-lg-2">
            @lang('cms.featuring-image')
            <span class="red">*</span>
        </label>
        <div class="col-md-10">
            <div class="input-group">
                      <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail2" data-preview="holder2"
                           class="btn btn-primary">
                          <i class="fa fa-picture-o"></i>
                            @lang('cms.choose')
                        </a>
                      </span>
                <input required id="thumbnail2" class="form-control" type="text"
                       value="{{isset($findIdProducts) && isset($findIdProducts->image[0]) && !empty($findIdProducts)  ? $findIdProducts->image[0]->url : null }}"
                       name="filepath[]">
            </div>
        </div>

        <br>
        <br>
        <br>

        @if (isset($findIdProducts) && $findIdProducts->count() > 0)
            @if(isset($findIdProducts->image) && !empty($findIdProducts->image) && count($findIdProducts->image) > 0)
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <img id="holder2" src="{{url($findIdProducts->image[0]->url)}}"
                                 style="margin-top:15px;max-height:100px;">
                        </div>
                    </div>

            @else
                <img width="50" src="{{url('general/img/404-error.png')}}" alt="webpentest">
            @endif
        @endif
        <img id="holder2" style="margin-top:15px;max-height:100px;">
    </div>


    {{-- description --}}
    <div class="form-group">
        <label for="description"
               class="control-label col-lg-2">
            @lang('cms.description')
            <span class="red">*</span>
        </label>
        <div class="col-lg-10">
            <textarea required name="description" id="description" cols="30"
                      rows="10">{{isset($findIdProducts)  ? $findIdProducts->description : old('description') }}</textarea>
        </div>
    </div>
    

    
    {{-- package_detail --}}
    <div class="form-group">
        <label for="package_detail"
               class="control-label col-lg-2">
           جزییات محصولات پکیجی
        </label>
        <div class="col-lg-10">
            <textarea  name="package_detail" id="package_detail" cols="30"
                      rows="10">{{isset($findIdProducts)  ? $findIdProducts->package_detail : old('package_detail') }}</textarea>
        </div>
    </div>



    {{-- category _ id --}}
    <div class="form-group ">
        <label for="category_id" class="control-label col-lg-2 ">
            @lang('cms.choose-cat')
            <span class="red">*</span>
        </label>
        <div class="col-lg-10">
            <select required name="category_id"
                    class="form-control col-lg-4 select-option categorySelect js-example-basic-multiple">
                <option value="">@lang('cms.choose-category')</option>

                @if(isset($allCategoryProducts) && !empty($allCategoryProducts))
                    @foreach($allCategoryProducts as $item)

                        @if (\Illuminate\Support\Facades\Input::old('category_id') == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->title }}</option>
                        @else
                            <option name="category_id" value="{{$item->id}}"
                                {{ isset($findIdProducts) && !empty($findIdProducts) && $item->id == $findIdProducts->category_id ? "selected" : null  }}
                            >{{$item->title}} - {{ isset($item->parentCategory->title) && !empty($item->parentCategory->title) ? $item->parentCategory->title : 'دسته بندی اصلی'  }}</option>
                        @endif

                    @endforeach
                @endif
            </select>
        </div>
    </div>

    {{-- brand --}}
    <div class="form-group">
        <label for="brand" class="control-label col-lg-2 ">
            @lang('cms.choose-brand')
            <span class="red">*</span>
        </label>
        <div class="col-lg-10">
            <select required name="brand"
                    class="form-control col-lg-4 select-option categorySelect js-example-basic-multiple">
                <option value="">@lang('cms.choose-brand-select-option')</option>
                @if(isset($allBrand) && !empty($allBrand))
                    @foreach($allBrand as $item)

                        @if (\Illuminate\Support\Facades\Input::old('brand') == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->title }}</option>
                        @else
                            <option name="brand" value="{{$item->id}}"
                                {{ isset($findIdProducts) && !empty($findIdProducts) && $item->id == $findIdProducts->brand_id ? "selected" : null  }}
                            >{{$item->title}}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <br>
    <div id="resultAjaxAllAttributeCategory"></div>
    <br>


    {{-- weight --}}
    <div class="form-group">
        <label for="weight" class="control-label col-lg-2">
          وزن محصول
        </label>
        <div class="col-lg-10">
            <input class=" form-control"
                   value="{{isset($findIdProducts)  ? $findIdProducts->weight : old('weight') }}"
                   id="weight" name="weight" type="text"/>
        </div>
    </div>


    {{-- code --}}
    <div class="form-group">
        <label for="code" class="control-label col-lg-2">
            @lang('cms.product-code')
        </label>
        <div class="col-lg-10">
            <input class=" form-control"
                   value="{{isset($findIdProducts)  ? $findIdProducts->code : old('code') }}"
                   id="code" name="code" type="text"/>
        </div>
    </div>
    
    
      <div class="form-group ">
        <label for="sorting" class="control-label col-lg-2">
        مرتب سازی
        </label>
        <div class="col-lg-10">
            <input class=" form-control"
                   value="{{isset($findIdProducts)  ? $findIdProducts->sorting : old('sorting') }}"
                   id="sorting" name="sorting"  type="number" />
        </div>
    </div>




    <div class="form-group">
        <label for="" class="col-md-2">@lang('cms.suggest')</label>

        <div class="col-md-10">
            <div class="col-md-2">
                <label for="special">@lang('cms.special')</label>
                <input type="checkbox"
                       {{\Illuminate\Support\Facades\Input::old('special') ? "checked" : null}} {{ isset($findIdProducts) && $findIdProducts->special == 1 ? "checked" : null  }} value="special"
                       id="special" name="special">
            </div>
            {{-- <div class="col-md-2">
                 <label for="momentary">@lang('cms.momentary')</label>
                 <input type="checkbox" {{\Illuminate\Support\Facades\Input::old('momentary') ? "checked" : null}} {{ isset($findIdProducts) && $findIdProducts->momentary == 1 ? "checked" : null  }} value="momentary" id="momentary"  name="momentary">
             </div>--}}
        </div>

    </div>

    {{-- related Product --}}
    <div class="form-group">
        <label for="title" class="control-label col-lg-2">محصولات مرتبط</label>
        <div class="col-lg-10">
            <select class="form-group default-select select2Style"
                    name="related[]" multiple="multiple">
                @foreach($allProduct as $itemProduct)
                    <option
                        value="{{$itemProduct->id}}" {{ isset($findIdProducts) && in_array($itemProduct->id , $findIdProducts->related->pluck('related_id')->toArray()) ? "selected" :null }} >{{ $itemProduct->title}}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{--tags--}}
    <div class="form-group">
        <label for="title" class="control-label col-lg-2">برچسب ها </label>
        <div class="col-lg-10">
            <select id="select2-multiple"
                    class="form-control js-example-basic-multiple"
                    name="tags[]" multiple="multiple">
                @foreach($tags as $tag)
                    <option
                        value="{{$tag->id}}" {{ isset($findIdProducts) && in_array($tag->id , $findIdProducts->tags->pluck('id')->toArray()) ? "selected" :null }} >{{ $tag->title}}</option>
                @endforeach
            </select>
        </div>
    </div>


    {{-- lang --}}
    {{--    <div class="form-group ">--}}
    {{--        <label for="lang" class="control-label col-lg-2">@lang('cms.lang')</label>--}}
    {{--        <div class="col-lg-10">--}}
    {{--            <select name="lang" class="form-control select-option" id="">--}}
    {{--                @foreach(\App\Utility\lang::langEach() as $key => $value)--}}
    {{--                    <option--}}
    {{--                        value="{{$key}}" {{ isset($findIdProducts) && $key == $findIdProducts->lang ? 'selected' : null }} >{{$value}}</option>--}}
    {{--                @endforeach--}}
    {{--            </select>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    {{-- shipping_cost--}}
    <div class="form-group ">
        <label for="shipping_cost" class="control-label col-lg-2">
           هزینه ارسال
            <span class="red">*</span>
        </label>
        <div class="col-lg-10">
            <select name="shipping_cost" class="form-control select-option" id="">
                @foreach(\App\Model\ShippingCost::SHIPPING_COST as $key => $value)
                    @php \App\Model\ShippingCost::$preventAttrSet = false  @endphp
                    <option
                            value="{{$value}}" {{ isset($findIdProducts) && $value == $findIdProducts->shipping_cost ? 'selected' : null }} >{{ \App\Model\ShippingCost::getTypeShippingCost($value) }}</option>
                @endforeach
            </select>
        </div>
    </div>


    {{-- status--}}
    <div class="form-group ">
        <label for="title" class="control-label col-lg-2">
            @lang('cms.status')
            <span class="red">*</span>
        </label>
        <div class="col-lg-10">
            <select name="status" class="form-control select-option" id="">
                @foreach(\App\Utility\Status::Status() as $key => $value)
                    <option
                        value="{{$key}}" {{ isset($findIdProducts) && $key == $findIdProducts->status ? 'selected' : null }} >{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- button --}}
    <div class="form-group">

        <div class="col-lg-offset-2 col-lg-10">
            <span class="btn btn-info pull-left" id="next">
                @lang('cms.next')
            </span>
        </div>
    </div>
