
<h3 class="h3-desc">@lang('cms.category-products')
    <span class="img-gallery-position">
        <img width="36" style="margin-top: -7px" src="{{url('admin_theme/img/category-product.png')}}" alt="category-product">
    </span>
</h3>


<br>

{{-- name --}}
<div class="form-group ">
    <label for="title" class="control-label col-lg-2">@lang('cms.title')</label>
    <div class="col-lg-10">
        <input class=" form-control"
               value="{{isset($findCategoryProductId)  ? $findCategoryProductId->title : old('title') }}"
               id="title" name="title" minlength="2" type="text" required/>
    </div>
</div>


{{-- description --}}
<div class="form-group">
    <label for="description"
           class="control-label col-lg-2">
        @lang('cms.description')
        <span class="red">*</span>
    </label>
    <div class="col-lg-10">
            <textarea  name="description" id="description" cols="30"
                      rows="10">{{isset($findCategoryProductId)  ? $findCategoryProductId->description : old('description') }}</textarea>
    </div>
</div>


{{-- select option --}}
<div class="form-group ">
    <label for="cname" class="control-label col-lg-2 ">@lang('cms.choose-cat')</label>
    <div class="col-lg-10">
        <select name="parent_id" class="form-control col-lg-4 select-option">
            <option value="0">@lang('cms.main')</option>
            @if(isset($allCategoryProducts) && !empty($allCategoryProducts))
                @foreach($allCategoryProducts as $item)
                    <option name="parent_id" value="{{$item->id}}"
                        {{ isset($findCategoryProductId) && !empty($findCategoryProductId) && $item->id == $findCategoryProductId->parent_id ? "selected" : null  }}
                    >{{$item->title}}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<div class="form-group">
    <label for="" class="col-md-2">نمایش در</label>

    <div class="col-md-10">
        <div class="col-md-2">
            <label  for="showpage">باکس های صفحه اول</label>
            <input type="checkbox" {{\Illuminate\Support\Facades\Input::old('showpage') ? "checked" : null}} {{ isset($findCategoryProductId) && $findCategoryProductId->showpage == 1 ? "checked" : null  }} value="showpage"  id="showpage" name="showpage">
        </div>
{{--        <div class="col-md-2">--}}
{{--            <label  for="showtab">تب صفحه اول</label>--}}
{{--            <input type="checkbox" {{\Illuminate\Support\Facades\Input::old('showtab') ? "checked" : null}} {{ isset($findCategoryProductId) && $findCategoryProductId->showtab == 1 ? "checked" : null  }} value="showtab"  id="showtab" name="showtab">--}}
{{--        </div>--}}
    </div>
</div>

{{-- sort --}}
<div class="form-group ">
    <label for="sort" class="control-label col-lg-2">@lang('cms.how-to-display') </label>
    <span class="alerts"> @lang('cms.description-category-of-product') </span>
    <div class="col-lg-10">
        <input class=" form-control"
               value="{{isset($findCategoryProductId)  ? $findCategoryProductId->sort : null }}"
               id="sort" min="0" name="sort" type="number" required/>
    </div>
</div>



{{-- status--}}
<div class="form-group ">
    <label for="title" class="control-label col-lg-2">@lang('cms.status')</label>
    <div class="col-lg-10">
        <select name="status" class="form-control select-option" id="">
            @foreach(\App\Utility\Status::Status() as $key => $value)
                <option
                    value="{{$key}}" {{ isset($findCategoryProductId) && $key == $findCategoryProductId->status ? 'selected' : null }} >{{$value}}</option>
            @endforeach
        </select>
    </div>
</div>


{{-- all attribute --}}


@if(isset($allAttributeGroup) && !empty($allAttributeGroup) &&  $allAttributeGroup->count() > 0)
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">

            @foreach($allAttributeGroup as $item)
                <div class="panel-heading">

                    <h4 class="panel-title attribute-title">
                        <a data-toggle="collapse" data-parent="#accordion"
                           href="#collapse{{$item->id}}">
                            {{$item->name}} - {{ isset($item->label) ? $item->label : null  }}
                        </a>
                    </h4>
                </div>

                @if($loop->first)
                    <div id="collapse{{$item->id}}"
                         class="panel-collapse collapse in">
                        @else
                            <div id="collapse{{$item->id}}"
                                 class="panel-collapse collapse">
                                @endif
                                <div class="row">
                                    <div class="col-md-12">
                                        @foreach($item->attributes as $itemAttribute)

                                            <div class="col-md-3 text-center">
                                                <label
                                                    for="{{$itemAttribute->name}}">{{$itemAttribute->name}}</label>
                                                <input type="checkbox"
                                                       {{ isset($findCategoryProductId) && !empty($findCategoryProductId) &&
                                                       in_array($itemAttribute->id,$arrayOfAttributes)
                                                         ? "checked" : null
                                                         }}
                                                       id="{{$itemAttribute->name}}"
                                                       value="{{$itemAttribute->id}}"
                                                       name="attributes[]">
                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @endforeach
                    </div>
        </div>
        @endif


        <br>
        <br>


        {{-- button --}}
        <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <input  class="btn btn-info pull-left w-100" id="next"
                           value="@lang('cms.next')">
                </div>
        </div>


