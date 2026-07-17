@extends('site.layout.master')
@section('site.css')

<link rel="stylesheet" type="text/css" href="{{ url('') }}/site_themes/css/internal/style.css" />

@if(isset($maxPrice) && !empty($maxPrice))
<link rel="stylesheet" type="text/css" href="{{ url('') }}/site_themes/css/nouislider.min.css" />
@endif

@endsection

@section('content')

<div class="container-fluid body-inner p-xs-0">
    <!-- Path lists -->
    <div class="container">

        @if(isset($category) && $category != false)
        <div class="row">
            <div class="col-12 path-list p-0">
                <ul class="p-0">
                    <li><a href="{{ route('site.index') }}"> خانه </a></li>
                    <li><a href="{{ $category->path() }}">{{ $category->title }}</a></li>
                </ul>
            </div>
        </div>
        @endif
    </div>
    <!--Path lists End-->


    @if(isset($category) && isset($category->image) && count($category->image) > 0 && $category != false)
    <div class=" brand-slider">
        <div id="demo" class="carousel slide" data-ride="carousel">
            @if(isset($category->image))
            <!-- Indicators -->
            <ul class="carousel-indicators">
                @foreach($category->image as $key => $image)
                <li data-target="#demo" data-slide-to="{{ $key }}" {{ $key==1 ? 'class="active"' : null }}></li>
                @endforeach
            </ul>

            <!-- The slideshow -->
            <div class="carousel-inner">
                @foreach($category->image as $key => $image)
                <div class="carousel-item {{ $key == 1 ? 'active' : null }}">
                    <img loading="lazy" src="{{ $image->url }}" alt="{{ $category->title }}">
                </div>
                @endforeach
            </div>
            @endif

            @if( isset($category->image) && count($category->image) > 2)
            <!-- Left and right controls -->
            <a class="carousel-control-prev" href="#demo" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#demo" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
            @endif

        </div>
    </div>
    @endif

    <div class="container site-blog__box">
        <div class="row search-result-header">
            <!--Search result text-->
            @if(isset($title))
            <h1 style="font-size:1.5rem;">{{ count($products) }} محصول برای {{ $title }} یافت شد</h1>
            @else
            <h1 style="font-size:1.5rem;">فروشگاه آکادمی دفاع شخصی احسان دیبازر</h1>
            @endif
        </div>
        <div class="row">
            <!--Search result slider-->
            <div class="serach-reslut-slider">
            </div>
        </div>


        <div class="row">
            <div class="search-product">

                <div class="row">

                    @include('site.product.partials.filter',compact('category'))
                    <div class="col-12 col-md-9 ">
                        <div class="d-block">
                            <div class="search-product-header">
                                <h3>
                                    {{ isset($category) && $category != false ? $category->title : ' محصولات
                                    ' }}
                                    </h3>
                            </div>
                        </div>
                        <div class="search-product-results search-product-results-ajax">
                            <div class="row row-category">
                                @if(isset($products))
                                @foreach($products as $product)
                                <div class="col-12 col-lg-4 col-md-6  col-lg-4 col-xl-4 col-sm-6 px-2 mb-3">
                                    <article class="card-wrapper">
                                        <div class="image-holder">
                                            <a href="{{ $product->path() }}" class="image-holder__link"></a>
                                            <div class="image-liquid image-holder--original"
                                                style="background-image: url('{{ isset($product->image[0]) && !empty($product->image[0]) ? url($product->image[0]->url) : null }}')">
                                            </div>
                                        </div>

                                        <div class="product-description">
                                            <!-- title -->
                                            <h2 class="product-description__title">
                                                <a href="{{ $product->path() }}">
                                                    {{ $product->title }}
                                                </a>
                                            </h2>

                                            <!-- category and price -->
                                            <div class="row">

                                                <div class="col-12 col-sm-12 product-description__price">

                                                    <span class="old-cost"></span>
                                                    @if(\App\Utility\DiscountType::hasDiscount($product) == true)
                                                    <span class="offer-mob">
                                                        <span class="off">
                                                            {{ \App\Utility\DiscountType::showCentDiscount($product)}}
                                                        </span>
                                                    </span>
                                                    @endif
                                                    <br>
                                                    <span class="cost-total">
                                                        @if($product->type=App\Utility\ProductType::PDF)
                                                            @if(count($product->variations) > 0)
                                                                @if($product->variations && $product->variations[0]->price)
                                                                    <div class="col-12 text-center"> 
                                                                        <span class="red">{{number_format($product->variations[0]->price,0)}}
                                                                            تومان
                                                                        </span>
                                                                    </div>
                                                                @else
                                                                    <div class="col-12 text-center"><span class="red">رایگان</span>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @elseif($product->type=App\Utility\ProductType::SIMPLE)
                                                            @if(count($product->variations) > 0)
                                                                @if($product->variations && $product->variations[0]->count)
                                                                    @if($product->variations && $product->variations[0]->price)
                                                                        <div class="col-12 text-center ">
                                                                            <span class="red">{{number_format($product->variations[0]->price,0)}}   
                                                                                تومان
                                                                            </span>
                                                                        </div>
                                                                    @else
                                                                        <div class="col-12 text-center"><span class="red">رایگان</span>
                                                                        </div>
                                                                    @endif

                                                                @else
                                                                    <div class="col-12 text-center"><span class="red">ناموجود</span>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endif
                                                     
                                                    </span>
                                                    <span class="unit"></span>
                                                </div>
                                            </div>

                                            <!-- divider -->
                                            <hr />

                                            <!-- sizes -->
                                            <div class="sizes-wrapper">

                                                <span class="secondary-text text-uppercase">
                                                    <ul class="list-inline p-0 text-center">
                                                        <li>
                                                            <button class="btn-favo"
                                                                onclick="favorite('{{ $product->id }}')">
                                                                <i class="fa fa-heart"></i>
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="add-crd" title="مقایسه"
                                                                onclick="compare('{{$product->id}}')">
                                                                <i class="fas fa-exchange-alt"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </span>
                                            </div>


                                        </div>

                                    </article>

                                </div>
                                @endforeach
                                {{-- <div class="item position-relative">
                                        <div class="lbl-pro">
                                            <span>فروش ویژه</span>

                                        </div>
                                        <button class="btn-favo" onclick="favorite('{{ $product->id }}')">
                                            <i class="fa fa-heart"></i>
                                        </button>
                                        <div class="img-pro">

                                            <a href="{{ $product->path() }}" class="d-block">

                                                <img loading="lazy" src="{{ isset($product->image[0]) && !empty($product->image[0]) ? url($product->image[0]->url) : null }}"
                                                    class="img-fluid" alt="{{ $product->title }}">
                                            </a>
                                        </div>
                                        <div class="product-box-text d-block">
                                            <h3>
                                                <a href="{{ $product->path() }}">{{ $product->title }}</a>
                                            </h3>
                                        </div>
                                        <div class="row align-items-center product-box-price-row">
                                            <div class="col-2 p-0 text-center">
                                                <a href="javascript:void(0)" class="add-crd" title="مقایسه"
                                                    onclick="compare('{{$product->id}}')">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </a>
                                            </div>
                                            <div class="col-10 cost text-end pl-md-0">

                                                <span class="old-cost"></span>
                                                @if(\App\Utility\DiscountType::hasDiscount($product) == true)
                                                <span class="offer-mob">
                                                    <span class="off">{{
                                                        \App\Utility\DiscountType::showCentDiscount($product) }}</span>
                                                </span>
                                                @endif
                                                <br>
                                                <span class="cost-total">
                                                    @php
                                                    $allPrice = \App\Utility\sortPrice::sortPrice($product);
                                                    echo \App\Utility\sortPrice::totalPrice($allPrice);
                                                    @endphp

                                                </span>
                                                <span class="unit"></span>
                                            </div>
                                        </div>
                                    </div>
                                        </div>
                                    </div>
                                    --}}
                        <div>
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                        @else
                        <div class="alert alert-info">چیزی یافت نشد!</div>
                        @endif
                    </div>
                </div>
                <div class="loadmore-result text-center">
                    <button class="btn btn-waiting load-more">بیشتر</button>
                    <input type="hidden" class="countProduct" value="{{ isset($countProduct) ? $countProduct : 0 }}">
                </div>
            </div>
        </div>
    </div>

</div>

</div>




</div>
@if(isset($similarProducts) && count($similarProducts) > 0)
<div class="container-fluid similarProducts pb-4">
    <div class="container p-0">
        <div class=" row grey-slider-bg">
            <div class="brand-sliders-item">
                <div class="row brand-slider-header">
                    <div class="col-12 p-0 text-center">
                        <h4 class="title-section text-center wow fadeInUp">محبوب ترین ها</h4>
                    </div>
                </div>
                <div class="brand-slider-body row">
                    <div class="col-12 p-0">
                        <div class="owl-carousel owl-theme  owl-news">
                            @foreach($similarProducts as $product)


                            <div class="item">
                                <article class="card-wrapper wow fadeInUp">
                                    <div class="image-holder">
                                        <a href="{{ $product->path() }}" class="image-holder__link"></a>
                                        <div class="image-liquid image-holder--original"
                                            style="background-image: url('{{ isset($product->image[0]) && !empty($product->image[0]) ? url($product->image[0]->url) : null }}')">
                                        </div>
                                    </div>

                                    <div class="product-description">
                                        <!-- title -->
                                        <h2 class="product-description__title">
                                            <a href="{{ $product->path() }}">
                                                {{ $product->title }}
                                            </a>
                                        </h2>

                                        <!-- category and price -->
                                        <div class="row">
                                            <div class="col-12 col-sm-12 product-description__price">
                                                {!! $product->prices !!}
                                            </div>
                                        </div>

                                        <!-- divider -->
                                        <hr />

                                        <!-- sizes -->
                                        <div class="sizes-wrapper">

                                            <span class="secondary-text text-uppercase">
                                                <ul class="list-inline p-0 text-center">
                                                    <li>
                                                        <button class="btn-favo"
                                                            onclick="favorite('{{ $product->id }}')">
                                                            <i class="fa fa-heart"></i>
                                                        </button>


                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" class="add-crd" title="مقایسه"
                                                            onclick="compare('{{$product->id}}')">
                                                            <i class="fas fa-exchange-alt"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </span>
                                        </div>


                                    </div>

                                </article>
                            </div>





                            @endforeach
                            {{-- <div class="item wow fadeInUp">
                                <div class="result-item">

                                    <div class="result-offered-image">
                                        <img
                                            src="{{ isset($product->image[0]) && !empty($product->image[0]) ? url($product->image[0]->url) : null }}">
                                    </div>
                                    <div class="result-offered-title">
                                        <h5>{{ $product->title }}</h5>
                                    </div>
                                    <div class="row result-offered-price">

                                        @php
                                        $allPrice = \App\Utility\sortPrice::sortPrice($product);
                                        echo \App\Utility\sortPrice::totalPrice($allPrice);
                                        @endphp

                                    </div>


                                    <div class="result-offerd-button-add">
                                        <p class="addto-cart">
                                            <a href="javascript:void(0)" class="det-like"
                                                onclick="favorite('{{ $product->id }}')">
                                                <i class="far fa-heart"></i>
                                            </a>
                                            <a href="{{ $product->path() }}">
                                                نمایش
                                            </a>
                                        </p>
                                    </div>

                                </div>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@section('site-js')
<script src="{{ url('') }}/site_themes/js/first.js"></script>
<script src="{{ url('') }}/site_themes/js/javascript.js"></script>


{{--**** Start Of Filter *****--}}
<script>
    $(".btn-filter").click(function (event) {
            event.preventDefault();
            $('.search-product-results-ajax').hide();

            $('.load-more').css('display', 'content');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var brand = $('.brand:checked').val();
            var category = $('.category:checked').val();
            var attr = $(".attr:checked");
            var min = $('input:hidden[name=min-value]').val()
            var max = $('input:hidden[name=max-value]').val()
            var attribute = [''];
            $.each(attr, function (key, value) {
                attribute.push($(value).val());
            });

            $.ajax({
                type: "post",
                url: "{{ route('site.filter.attr') }}",
                data: {
                    brand: brand,
                    category: category,
                    attr: attribute,
                    min: min,
                    max: max,
                    _token: CSRF_TOKEN
                },
                success: function (data) {
                    $(".search-product-results-ajax").html(data.html).fadeIn(2000);
                    $(".filter-applied-list").html(data.selected);
                },
                error: function (error) {
                    Swal.fire({
                        title: "@lang('cms.error')",
                        text: "@lang('cms.try-again-few-moments')",
                        icon: "error",
                        button: "@lang('cms.accept-2')",
                    });
                }
            });
        });
        $(".load-more").on('click', function (event) {
            event.preventDefault();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var lastID = $('#lastID').val();
            var countProduct = $('.countProduct').val();
            var brand = $('.brand:checked').val();
            var category = $('.category:checked').val();
            var attr = $(".attr:checked");
            var attribute = [''];
            $.each(attr, function (key, value) {
                attribute.push($(value).val());
            });

            $.ajax({
                type: "post",
                url: "{{ route('site.filter.attr') }}",
                data: {
                    load: true,
                    lastID: lastID,
                    brand: brand,
                    countProduct: countProduct,
                    category: category,
                    attr: attribute,
                    _token: CSRF_TOKEN
                },
                success: function (data) {

                    if (data.status == 100) {
                        Swal.fire({
                            title: "کاربر گرامی",
                            text: data.message,
                            icon: "info",
                            button: "@lang('cms.accept-2')",
                        });
                        $('.load-more').css('display', 'none');
                    } else {
                        $('.load-more').css('display', 'content');
                    }

                    console.log(data);
                    $(".search-product-results").html(data.html);
                    $(".filter-applied-list").html(data.selected);
                },
                error: function (error) {

                    Swal.fire({
                        title: "@lang('cms.error')",
                        text: $err,
                        icon: "error",
                        button: "@lang('cms.accept-2')",
                    });
                }
            });
        });
</script>
{{--****** End Of Filter ******--}}

@if(isset($maxPrice) && !empty($maxPrice))
<script>
    // Initialize slider:
            $(document).ready(function () {
                $('.noUi-handle').on('click', function () {
                    $(this).width(50);
                });
                var rangeSlider = document.getElementById('slider-range');
                var moneyFormat = wNumb({
                    decimals: 0,
                    thousand: ',',
                    postfix: 'تومان',
                });
                noUiSlider.create(rangeSlider, {
                    start: [0, {{$maxPrice}}],
                    step: 1,
                    direction: 'rtl',
                    range: {
                        'min': [0],
                        'max': [{{$maxPrice}}]
                    },
                    format: moneyFormat,
                    connect: true
                });

                // Set visual min and max values and also update value hidden form inputs
                rangeSlider.noUiSlider.on('update', function (values, handle) {
                    document.getElementById('slider-range-value1').innerHTML = values[0];
                    document.getElementById('slider-range-value2').innerHTML = values[1];
                    $('input[name=min-value]').val(moneyFormat.from(values[0]));
                    $('input[name=max-value]').val(moneyFormat.from(values[1]));
                });
            });
</script>
<script src="{{ url('') }}/site_themes/js/nouislider.min.js"></script>

@endif

@endsection