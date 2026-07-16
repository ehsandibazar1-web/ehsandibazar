<div class="col-12 col-md-3 gap-col-mob">
    <div class="sticky-filter">
{{--        <div class="filter-applied">--}}
{{--            <div class="filter-applied-header">--}}
{{--                <span class="float-right">فیلتر های انتخاب شده</span>--}}
{{--                <div class="clear"></div>--}}
{{--            </div>--}}
{{--            <div class="filter-applied-list">--}}
{{--                <ul>--}}
{{--                    @if(isset($selected) && !empty($selected))--}}
{{--                        @foreach($selected as $itemSelect)--}}
{{--                            <li>--}}
{{--                                <span>{{ $itemSelect }}</span>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
        <form action="#" class="form" id="filter">
{{--            @if(isset($brands) && count($brands) > 0)--}}
{{--                <div id="sort-filter" class="filter-item">--}}
{{--                    <div class="filter-item-header">--}}
{{--                                         <span class="float-left">--}}
{{--                                            <i class="fas fa-plus"></i>--}}
{{--                                         </span>--}}
{{--                        <span class="float-right">--}}
{{--                                         برند ها--}}
{{--                                         </span>--}}
{{--                    </div>--}}
{{--                    <div class="filter-item-body">--}}
{{--                        <div class="filter-item-choose-options">--}}
{{--                            <ul>--}}
{{--                                @foreach($brands as $brand)--}}
{{--                                    <li>--}}
{{--                                        <label class="filter-checkbox-container">--}}
{{--                                            <span>{{ $brand->title }}</span>--}}
{{--                                            <input type="radio" name="brand" class="brand" value="{{ $brand->id }}">--}}
{{--                                            <span class="checkmark2"></span>--}}
{{--                                        </label>--}}
{{--                                    </li>--}}
{{--                                @endforeach--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}

            @if(isset($categoryProduct) && count($categoryProduct) > 0)
                <div id="sort-filter" class="filter-item">
                    <div class="filter-item-header">
                                         <span class="float-left">
                                            <i class="fas fa-plus"></i>
                                         </span>
                        <span class="float-right">
                                        categories
                                         </span>
                    </div>
                    <div class="filter-item-body" style="display: block">
                        <div class="filter-item-choose-options">
                            <ul>
                                @foreach($categoryProduct as $categoryItem)
                                    <li>
                                        <a href="{{ $categoryItem->path() }}" class="float-right">{{ $categoryItem->title }}</a>
                                        <span class="badge badge-dark float-left blog_badge_category">{{ $categoryItem->products->count() }}</span>
                                        <span class="clearfix"></span>
                                    </li>
{{--                                    <li>--}}
{{--                                        <label class="filter-checkbox-container">--}}
{{--                                            <span>{{ $categoryItem->title }}</span>--}}
{{--                                            <input type="radio" name="category" class="category"--}}
{{--                                                   value="{{ $categoryItem->id }}" {{ isset($category) && $category !=false && $categoryItem->id == $category->id ? 'checked' : null }} >--}}
{{--                                            <span class="checkmark2"></span>--}}
{{--                                        </label>--}}
{{--                                    </li>--}}
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

{{--            @if(isset($attributes))--}}
{{--                @foreach($attributes as $itemAttributes)--}}
{{--                    <div class="search-product-filter">--}}
{{--                        <div class="filter-item">--}}
{{--                            <div class="filter-item-header">--}}
{{--                                         <span class="float-left">--}}
{{--                                            <i class="fas fa-plus"></i>--}}
{{--                                         </span>--}}
{{--                                <span class="float-right">--}}
{{--                                            {{ $itemAttributes->name }}--}}
{{--                                         </span>--}}
{{--                            </div>--}}
{{--                            @if(isset($itemAttributes->attributevalue) && count($itemAttributes->attributevalue) > 0)--}}
{{--                                <div class="filter-item-body">--}}
{{--                                    <div class="filter-item-choose-options">--}}
{{--                                        <ul>--}}
{{--                                            @foreach($itemAttributes->attributevalue->unique('value') as $itemAttributeValue)--}}
{{--                                                <li>--}}
{{--                                                    <label class="filter-checkbox-container">--}}
{{--                                                        <span>{{ $itemAttributeValue->value }}</span>--}}
{{--                                                        <input type="checkbox" value="{{ $itemAttributeValue->id }}"--}}
{{--                                                               name="attr[]" class="attr">--}}
{{--                                                        <span class="checkmark"></span>--}}
{{--                                                    </label>--}}
{{--                                                </li>--}}
{{--                                            @endforeach--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            @endif--}}

{{--            @if(isset($maxPrice) && !empty($maxPrice))--}}
{{--                <div class="search-product-filter">--}}
{{--                    <div class="filter-item">--}}
{{--                        <div class="filter-item-header">--}}
{{--                                         <span class="float-left">--}}
{{--                                            <i class="fas fa-plus"></i>--}}
{{--                                         </span>--}}
{{--                            <span class="float-right">--}}
{{--                                            محدوده قیمت--}}
{{--                                         </span>--}}
{{--                        </div>--}}
{{--                        <div class="filter-item-body">--}}
{{--                            <div class="filter-item-choose-options">--}}
{{--                                <ul class="mt-5">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-sm-12">--}}
{{--                                            <div id="slider-range"></div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="row slider-labels">--}}
{{--                                        <div class="col-sm-6 caption">--}}
{{--                                            <strong>از:</strong> <span id="slider-range-value1"></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-sm-6 text-right caption">--}}
{{--                                            <strong>تا:</strong> <span id="slider-range-value2"></span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-sm-12">--}}
{{--                                            <form>--}}
{{--                                                <input type="hidden" name="min-value" value="0" id="min-value">--}}
{{--                                                <input type="hidden" name="max-value" value="{{$maxPrice}}" id="max-value">--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}

{{--            @endif--}}

{{--            <div class="search-product-filter">--}}
{{--                <input type="submit" value="فیلتر" class="btn btn-waiting btn-filter">--}}
{{--            </div>--}}
        </form>
    </div>

</div>
