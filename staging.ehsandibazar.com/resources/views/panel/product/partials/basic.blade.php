<div class=" mt-5">
    {{-- type --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="status"> @lang('cms.type')
                <span class="redAlert">*</span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="">
                    <select class="form-group" name="type"
                            onchange="auction(this.options[this.selectedIndex].value)">
                        @foreach(\App\Utility\ProductType::typeEach() as $key => $type)
                            <option value="{{$key}}" {{isset($findIdProducts) && $findIdProducts->type == $key  ? 'selected' : null }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- title --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="title">عنوان
                <span class="redAlert">*</span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="form-line">
                    <input class="form-control"
                           value="{{isset($findIdProducts)  ? $findIdProducts->title : old('title') }}"
                           id="title" name="title" minlength="2" type="text"
                           required/>
                </div>
            </div>
        </div>
    </div>

    @include('panel.layout.inputs.slug',['find' => isset($findIdProducts) ? $findIdProducts : null])


    {{-- image --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="status">@lang('cms.featuring-image')
                <span class="redAlert">*</span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0 p-xs-0">
            <div class="form-group">
                <div class="">
                                        <span class="input-group-btn">
                                        <a id="lfm" data-input="thumbnail2" data-preview="holder2"
                                           class="btn btn-primary">
                                          <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                        </a>
                                      </span>
                    <input id="thumbnail2" class="form-control" type="text"
                           name="filepath[]"
                           value="{{ !empty($findIdProducts->image)  ? $findIdProducts->image[0]->url : null }}">
                </div>
                <img id="holder2" style="margin-top:15px;max-height:100px;">
                @if(isset($findIdProducts) && count($findIdProducts->image) > 0 && isset($findIdProducts->image[0]))
                    <img src="{{  $findIdProducts->image[0]->url  }}"
                         id="holder2"
                         style="margin-top:15px;max-height:100px;">
                @endif
            </div>
        </div>
    </div>

    {{-- body --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="title">@lang('cms.description')
                <span class="redAlert">*</span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="form-line">
                                                <textarea required name="description" id="description" cols="30"
                                                          class="form-control ckeditor"
                                                          rows="10">{{isset($findIdProducts)  ? $findIdProducts->description : old('description') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- package_detail --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="title">جزییات پکیج
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="form-line">
                   <textarea name="package_detail" id="package_detail" cols="30"
                             class="form-control ckeditor"
                             rows="10">{{isset($findIdProducts)  ? $findIdProducts->package_detail : old('package_detail') }}</textarea>
                </div>
            </div>
        </div>
    </div>


    {{-- category --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="status">@lang('cms.choose-cat')
                <span class="redAlert">*</span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="">
                    <select required name="category_id"
                            class="form-group col-lg-4 select-option categorySelect js-example-basic-multiple">
                        <option value="">@lang('cms.choose-category')</option>

                        @if(isset($allCategoryProducts) && !empty($allCategoryProducts))
                            @foreach($allCategoryProducts as $item)

                                @if (request()->old('category_id') == $item->id)
                                    <option value="{{ $item->id }}"
                                            selected>{{ $item->title }}</option>
                                @else
                                    <option name="category_id"
                                            value="{{$item->id}}"
                                            {{ isset($findIdProducts,$findIdProducts->categories[0]) && !empty($findIdProducts) && $item->id == $findIdProducts->categories[0]->id ? "selected" : null  }}
                                    >{{$item->title}}
                                        - {{ isset($item->parentCategory->title) && !empty($item->parentCategory->title) ? $item->parentCategory->title : 'دسته بندی اصلی'  }}</option>
                                @endif

                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- brand --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="status">@lang('cms.choose-brand')
                <span class="redAlert">*</span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="">
                    <select required name="brand"
                            class="form-group col-lg-4 select-option categorySelect js-example-basic-multiple">
                        <option value="">@lang('cms.choose-brand-select-option')</option>
                        @if(isset($allBrand) && !empty($allBrand))
                            @foreach($allBrand as $item)

                                @if (\Illuminate\Support\Facades\Request::old('brand') == $item->id)
                                    <option value="{{ $item->id }}"
                                            selected>{{ $item->title }}</option>
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
        </div>
    </div>



    {{-- weight --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="title">وزن محصول</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="form-line">
                    <input class=" form-control"
                           value="{{isset($findIdProducts)  ? $findIdProducts->weight : old('weight') }}"
                           id="weight" name="weight" type="text"/>
                </div>
            </div>
        </div>
    </div>

    {{-- code --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="title">@lang('cms.product-code')</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="form-line">
                    <input class=" form-control"
                           value="{{isset($findIdProducts)  ? $findIdProducts->code : old('code') }}"
                           id="code" name="code" type="text"/>
                </div>
            </div>
        </div>
    </div>

    {{-- sorting --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="title">مرتب سازی</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="form-line">
                    <input class=" form-control"
                           value="{{isset($findIdProducts)  ? $findIdProducts->sorting : old('sorting') }}"
                           id="sorting" name="sorting"  type="number" />
                </div>
            </div>
        </div>
    </div>

    {{-- true / false --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="status">@lang('cms.special')</label>
        </div>
        <div class="demo-switch">
            <div class="switch">
                <label>بله
                    <input name="special" type="checkbox"
                            {{\Illuminate\Support\Facades\Request::old('special') ? "checked" : null}} {{ isset($findIdProducts) && $findIdProducts->special == 1 ? "checked" : null  }}>
                    <span class="lever"></span>خیر</label>
            </div>
        </div>

        {{--                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">--}}
        {{--                                                    <label for="status"> @lang('cms.momentary')</label>--}}
        {{--                                                </div>--}}
        {{--                                                <div class="demo-switch">--}}
        {{--                                                    <div class="switch">--}}
        {{--                                                        <label>بله--}}
        {{--                                                            <input name="momentary" type="checkbox"--}}
        {{--                                                                    {{\Illuminate\Support\Facades\Request::old('momentary') ? "checked" : null}} {{ isset($findIdProducts) && $findIdProducts->momentary == 1 ? "checked" : null  }} >--}}
        {{--                                                            <span class="lever"></span>خیر</label>--}}
        {{--                                                    </div>--}}
        {{--                                                </div>--}}
    </div>


    {{-- related Product --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="status">محصولات مرتبط
                <span class="redAlert">*</span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="">
                    <select data-select="false" class="form-group select2"
                            name="related[]" multiple="multiple">
                        @foreach($allProduct as $itemProduct)
                            <option value="{{$itemProduct->id}}" {{ isset($findIdProducts) && in_array($itemProduct->id , $findIdProducts->related->pluck('related_id')->toArray()) ? "selected" :null }} >{{ $itemProduct->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    @include('panel.layout.inputs.tags')

    {{-- shipping_cost --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="status">  هزینه ارسال
                <span class="redAlert">*</span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="">
                    <select name="shipping_cost" class="form-group select-option" id="">
                        @foreach(\App\Model\ShippingCost::SHIPPING_COST as $key => $value)
                            @php \App\Model\ShippingCost::$preventAttrSet = false  @endphp
                            <option
                                    value="{{$value}}" {{ isset($findIdProducts) && $value == $findIdProducts->shipping_cost ? 'selected' : null }} >{{ \App\Model\ShippingCost::getTypeShippingCost($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>



    {{-- status --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="status">وضعیت
                <span class="redAlert">*</span>
            </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 p-xs-0">
            <div class="form-group">
                <div class="">
                    <select name="status" class="form-group select-option" id="">
                        @foreach(\App\Utility\Status::Status() as $key => $value)
                            <option
                                    value="{{$key}}" {{ isset($findIdProducts) && $key == $findIdProducts->status ? 'selected' : null }} >{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

</div>