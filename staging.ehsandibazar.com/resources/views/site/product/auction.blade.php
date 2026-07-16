`@extends('site.layout.master')

@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_themes/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_themes/css/custom-site.css"/>
    <script src="{{ url('') }}/site_themes/js/first.js"></script>
    <script src="{{ url('') }}/site_themes/js/javascript.js"></script>
    <style>
        .soon {
            display: inline;
        }

        #clockdiv {
            color: #fff;
            display: inline-block;
            font-weight: 100;
            text-align: center;
        }

        #clockdiv > div {
            padding: 9px !important;
            border-radius: 3px;
            background: #e7ba67ad;
            display: inline-block;
        }

        #clockdiv div > span {
            padding: 14px !important;;
            border-radius: 3px;
            background: #e7ba67;
            /*border: 1px solid #000000;*/
            display: inline-block;
        }

        .smalltext {
            padding-top: 5px !important;
            font-size: 16px !important;
        }
    </style>
    <script type="application/ld+json">
    {
    "@context": "http://schema.org/",
      "@type": "Product",
      "name": "{{ $product->title }}",
      "image": "{{ Url($product->image[0]->url) }}",
      "description": "{{ strip_tags(\Illuminate\Support\Str::limit($product->description,240)) }}",
    "brand": {
      "@type": "Thing",
      "name": "{{ $product->brand->title }}"
      }
    }


























    </script>
@endsection

@section('content')
    <!-- Path lists -->
    <div class="container">
        <div class="row">
            <div class="container path-list">
                <ul>
                    <li><a href="{{ route('site.index') }}"> خانه </a></li>
                    <li><a href="{{ $product->categories[0]->path() }}">{{ $product->categories[0]->title }}</a>
                    </li>
                    <li><a href="{{ $product->path() }}">{{ $product->title }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--Path lists End-->

    <!--Product Box-->
    <div class="container product-box">
        <div class="row">
            <div class="col-12 col-md-5">
                <div class=" product-image-like ">
                    <div class="">
                        <p class="col-12">
                            <a href="javascript:void(0)" class="det-like" data-url="{{ $product->id }}">
                                <i class="far fa-heart"></i>
                            </a>
                        <div class="clear"></div>

                        </p>
                    </div>

                </div>
                <div class="container-fluid product-box-image row">
                    @if(isset($product->image))
                        <div class="col-3">
                            <div class="product-image-list">
                                <ul>
                                    @foreach($product->image as $key => $image)
                                        <li {{ $key == 0 ? 'class="active"' : null }}>
                                            <a href="#">
                                                <img src="{{ url($image->url) }}" alt="{{ $product->title }}">
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="col-9">
                        <div class="product-image-main">
                            <div class="img-zoom-container">
                                <img id="myimage"
                                     src="{{ isset($product->image[0]) ? url($product->image[0]->url) : '#' }}"
                                     alt="{{ $product->title }}">
                            </div>
                            <div class="product-image-hover">
                            </div>
                        </div>
                    </div>
                    <div class="productt-image-zoom">
                        <img src="">
                    </div>

                </div>
            </div>
            <div class="row col-md-7">
                <div class="col-sm-6">
                    <div class="product-detail">
                        <h3>{{ $product->title }}</h3>
                        <div class="rating-product">
                            <!--After i will Add-->
                            <ul>

                                <li>{{ $product->viewCount }} مشاهده</li>
                                <li>{{ $product->commentCount }} دیدگاه</li>
                                <li class="cat-brand badge badge-success"><a
                                        href="{{ $product->categories[0]->path() }}">{{ $product->categoryproduct->title }}</a>
                                </li>
                            </ul>

                        </div>
                        <div class="product-select-size">
                            <p>
                                    <span class="size-choose">
                                        <div id="variety"></div>
                                    </span>
                                <a href="" class="float-right"></a>
                            </p>

                            <div class="clear"></div>

                        </div>
                        <div class="product-price">
                            <ul>
                                <li>
                                    <?php $count = 0 ?>
                                    @foreach($product->variations->toArray() as $itemVariationCount)
                                        <?php $count += $itemVariationCount['count']  ?>
                                    @endforeach
                                    <span>قیمت بازار :</span>
                                    <span class=""
                                          id="ajax-price">{{ count($product->variations->toArray())  <= 0 || $count <= 0  ? 'ناموجود' : null}}</span>
                                    <span class=""
                                          id="ajax-priceDiscount"> {{ count($product->variations->toArray())  <= 0 || $count <= 0  ? '' : null}}</span>


                                    <br>
                                    <span>قیمت شروع :</span>
                                    <span>{{ number_format($product->auction->start_price). " تومان " }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="product-offer" id="div-description">
                            <div class="p-description" id="result-description"></div>
                        </div>
                        @php
                            $capacity = $product->auction->participant_count - count($product->auction->users)
                        @endphp
                        @if($capacity == 0 && $product->auction->status == 1)
                            <div class="product-offer text-center">
                                <span class="bg-danger p-1 locked">قفل شده</span>
                            </div>
                        @else
                            <div class="product-offer">
                                <span>ظرفیت :</span>
                                {{   $capacity.' نفر '  }}
                            </div>
                        @endif

                        @if($count > 0 && $product->auction->status == 1)

                            <div id="clockdiv">
                                <div>
                                    <span class="days" id="day"></span>
                                    <div class="smalltext">روز</div>
                                </div>
                                <div>
                                    <span class="hours" id="hour"></span>
                                    <div class="smalltext">ساعت</div>
                                </div>
                                <div>
                                    <span class="minutes" id="minute"></span>
                                    <div class="smalltext">دقیقه</div>
                                </div>
                                <div>
                                    <span class="seconds" id="second"></span>
                                    <div class="smalltext">ثانیه</div>
                                </div>
                            </div>
                            <p id="date"></p>

                            <p id="the-final-moments"></p>
                            <div id="timer">
                                <div>
                                    <span class="seconds" id="second-final"></span>
                                    <div class="smalltext">ثانیه</div>
                                </div>
                            </div>

                            @if(in_array(auth()->user()->id,$product->auction->users->pluck('id')->toArray()))
                                @if($product->auction->start_date < \Carbon\Carbon::now()->timestamp)
                                    <div class="product-box-buy">
                                        <div class="row">
                                            <div class="col-12 ">
                                                <div class="product-auction">
                                                    <a class="btn-site add-suggestion">
                                                        پیشنهاد
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                @if($capacity > 0)
                                    <div class="product-box-buy">
                                        <div class="row">
                                            <div class="col-12 ">
                                                @php
                                                    $paymentAmount = $product->auction->click_count * $product->auction->every_click_price_for_pay + $product->auction->start_price;
                                                @endphp
                                                <div class="product-auction">
                                                    <a class="btn-site"
                                                       href="{{ route('site.buy.auction',$product->slug) }}">
                                                        شرکت در حراجی و خرید کلیک تنها
                                                        با {{ number_format($paymentAmount) }} تومان
                                                        <i class="fas fa-cart-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="product-box-buy1">
                                <div class="row">
                                    <div class="col-12 ">
                                        <div class="product-auction">
                                            <a class="btn-site">
                                                این حراجی به اتمام رسیده
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="product-features">
                        @if(isset($product->tags))
                            <div class="site-blog-post__box__body__cat">
                                <ul>
                                    @foreach($product->tags as $tag)
                                        <li class="badge badge-info">
                                            <a href="{{ $tag->path() }}">{{ $tag->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="product-powered">
                            <p>

                                {{-- if is admin / admin first --}}
                                @php
                                    $sortVariation = $product->variations;
                                    $sortVariation = collect($sortVariation)->sortBy('price');
                                    //$sortVariation  = $sortVariation->groupBy('user_id');

                                    $array = [];
                                    $arrayUniqUser = [];
                                @endphp

                                @foreach($sortVariation as  $itemVariation)
                                    @if(in_array($itemVariation->user->level,\App\Utility\Level::levelAdmins()) && $itemVariation->count > 0)
                                        <?php $array [0] = 0;?>
                                    @endif
                                @endforeach


                                <?php  $i = 1 ?>
                                @foreach($sortVariation as $key => $itemVariation)
                                    @if($itemVariation->count > 0)

                                        @if(!in_array($itemVariation->user_id,$arrayUniqUser))
                                            @if(isset($array) && !empty($array) && count($array) >= 0)
                                                @if($itemVariation->status == \App\Utility\Status::active && $itemVariation->count > 0)
                                                    {{-- when was admin --}}
                                                    {{-- todo store name --}}
                                                    @if(in_array($itemVariation->user->level,\App\Utility\Level::levelAdmins()) && $itemVariation->count > 0)
                                                        <span class="sellers active"
                                                              data-id="{{$itemVariation->user_id}}"></span>
                                                    @else
                                                        <span class="sellers"
                                                              data-id="{{$itemVariation->user_id}}"></span>
                                                    @endif

                                                @endif
                                            @else

                                                {{-- sort price and select price --}}
                                                {{-- todo store name --}}
                                                @if($i == 1 )
                                                    <span class="sellers active"
                                                          data-id="{{$itemVariation->user_id}}">{{$itemVariation->user->name}}</span>
                                                @else
                                                    <span class="sellers"
                                                          data-id="{{$itemVariation->user_id}}">{{$itemVariation->user->name}}</span>
                                                @endif
                                                <?php $i++ ?>
                                            @endif
                                            {{--@endif--}}
                                        @endif
                                        <?php $arrayUniqUser [] = $itemVariation->user_id ?>
                                    @endif
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="table-responsive balavar">
                        <div class="suggestion">
                            <h3 class="bg-suggetion-auction">آخرین پیشنهاد ها</h3>
                        </div>
                        <div class="latest-suggestion">
                            @include('site.product.partials.latest-suggestion',['suggestions' => $product->auction->suggestion])
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div id="myresult" class="img-zoom-result"></div>
    </div>
    <div class="container product-des-qa">
        <div class="row">
            <div class="col-12 col-md-12">
                <h4>توضیحات محصول:</h4>
                <div class="product-desc-header">
                    <ul>
                        <li id="prd" class="active">
                            <a href="javascript:void(0)">توضیحات محصول</a>
                        </li>
                        <li id="prh"><a href="javascript:void(0)">جزییات</a></li>
                    </ul>
                </div>
                <div class="product-desc">
                    <div id="prd-t" class="product-desc-de">
                        <div class="product-desc-de__sizeable" style="height:200px">
                            {!! $product->description !!}
                        </div>
                        <div class="pro-desc-load-more">
                            <a href="javascript:void(0)">
                                بیشتر بخوانید ...
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                    </div>
                    <div id="prh-t" class="product-desc-de">
                        <table class="table">
                            @include('site.product.partials.details',compact('product'))
                        </table>
                    </div>
                </div>
                @if(isset($similarProducts) && count($similarProducts) > 0)
                    <h4>محصولات مشابه :</h4>
                    <div class="product-similar">
                        <div class="product-similar-slider">
                            <div class="MultiCarousel gcarousel" data-items="1,3,5,6" data-slide="1"
                                 data-interval="1000">

                                <div class="MultiCarousel-inner ">
                                    @foreach($similarProducts as $item)
                                        <div class="item">
                                            <div class="result-item">

                                                <div class="result-offered-image">
                                                    <a href="{{ $item->path() }}" target="_blank">
                                                        <img
                                                            src="{{ isset($item->image[0]) ? url($item->image[0]->url) : null }}">
                                                    </a>
                                                </div>
                                                <div class="result-offered-title">
                                                    <a href="{{ $item->path() }}" target="_blank">
                                                        <h5>{{ $item->title }}</h5>
                                                    </a>
                                                </div>
                                                <div class="result-offered-price">
                                                    <ul>
                                                        <li>
                                                            @php
                                                                $allPrice =   \App\Utility\sortPrice::sortPrice($item);
                                                                echo \App\Utility\sortPrice::totalPrice($allPrice);
                                                            @endphp
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                                <div class="gallery-btn-group">
                                    <div class="gallery-btn">
                                        <a class=" leftLst"><i class="fas fa-chevron-left"></i></a>
                                        <a class=" rightLst"><i class="fas fa-chevron-right"></i></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <h4>دیدگاه کاربران:</h4>
                <div class="reviews-qa-tab">
                    <div class="qa-review-header">
                        <ul>

                            <li>
                                <a href="javascript:void(0)" class="active" id="question-btn">دیدگاه</a>
                            </li>
                        </ul>
                    </div>


                    <div id="question-tabBody" class=" tabss-body q-a-tab-body">
                        @include('site.product.partials.comment',compact('product'))
                    </div>
                </div>
            </div>
            <!--<div class="col-12 col-md-3">
                <div class="side-add-to-bag-imag">

                </div>
            </div>-->
        </div>
    </div>
    <!--End Product Box-->
@endsection
@section('site-js')
    <script src="{{ url('') }}/site_themes/js/first.js"></script>
    <script src="{{ url('') }}/site_themes/js/javascript.js"></script>

    {{-- Start Of Count Down --}}
    <script>
        function winnerAuction() {
            var auctionId = "{{ $product->auction->id }}";
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "{{route('site.winner.auction')}}",
                data: {
                    auctionId: auctionId,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                    console.log(data);
                    if (data.status == 200) {
                        Swal.fire({
                            title: "برنده مشخص شد",
                            text: "مزایده پایان یافت و برنده مشخص شد :)",
                            icon: "success",
                            button: "بستن",
                        });
                    }
                }
            });
        }

        @if($latestSuggestion > \Carbon\Carbon::now()->timestamp)
        $('#timer').css("display", "block");
        $("#clockdiv").css("display", "none");

        var x;
        var deadline = new Date("{{ \Hekmatinasser\Verta\Facades\Verta::createTimestamp((int)$latestSuggestion)->formatGregorian('M d, Y H:i:s') }}").getTime();
        x = setInterval(function () {
            var now = new Date().getTime();
            var t = deadline - now;
            var days = Math.floor(t / (1000 * 60 * 60 * 24));
            var hours = Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((t % (1000 * 60)) / 1000);
            document.getElementById("second-final").innerHTML = seconds;
            if (t < 0) {
                clearInterval(x);
                document.getElementById("the-final-moments").innerHTML = "<div class='run-auction'><span>برنده مشخص شد</span></div>";
                $('#timer').css("display", "none");
                $('.product-box-buy').remove();
                $('.run-auction').css("display", "none");
                $('.locked').css("display", "none");
                winnerAuction();
            }
        }, 1000);
        @else
        $('#clockdiv').css("display", "block");
        $('#timer').css("display", "none");

        var deadline = new Date("{{ \Hekmatinasser\Verta\Facades\Verta::createTimestamp((int)$product->auction->start_date)->formatGregorian('M d, Y H:i:s') }}").getTime();
        var x = setInterval(function () {
            var now = new Date().getTime();
            var t = deadline - now;
            var days = Math.floor(t / (1000 * 60 * 60 * 24));
            var hours = Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((t % (1000 * 60)) / 1000);
            document.getElementById("day").innerHTML = days;
            document.getElementById("hour").innerHTML = hours;
            document.getElementById("minute").innerHTML = minutes;
            document.getElementById("second").innerHTML = seconds;
            if (t < 0) {
                clearInterval(x);
                document.getElementById("date").innerHTML = "<div class='run-auction'><span>مزایده در حال برگزاری میباشد</span></div>";
                $('#clockdiv').css("display", "none");
            }
        }, 1000);
        @endif
    </script>
    {{-- End Of Count Down --}}


    {{--Count Down Function--}}
    <script>
        var x;

        function AuctionCountDown(time = null, status = 1) {
            if (time) {
                clearInterval(x);
                var deadline = new Date(time).getTime();
                x = setInterval(function () {
                    var now = new Date().getTime();
                    var t = deadline - now;

                    var days = Math.floor(t / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((t % (1000 * 60)) / 1000);
                    document.getElementById("second-final").innerHTML = seconds;
                    if (t < 0) {
                        clearInterval(x);
                        document.getElementById("the-final-moments").innerHTML = "<div class='run-auction'><span>برنده مشخص شد</span></div>";
                        $('#timer').css("display", "none");
                        $('.product-box-buy').remove();
                        $('.run-auction').css("display", "none");
                        $('.locked').css("display", "none");
                        if (status == 1) {
                            winnerAuction();
                        }
                    }
                }, 1000);
            }

        }
    </script>



    {{--   /******Start Of Favorites******/--}}
    <script>
        $(".det-like").click(function () {
            var id = $(this).attr('data-url');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            if (id != "") {
                $.ajax({
                    type: "post",
                    url: "{{ route('add.favorites') }}",
                    data: {
                        id: id,
                        _token: CSRF_TOKEN
                    },
                    success: function (data) {
                        if (data.status == 100) {
                            Swal.fire({
                                title: "@lang('cms.alert')",
                                text: data.msg,
                                icon: "error",
                                button: "@lang('cms.accept-2')",
                            });
                        }
                        if (data.status == 200) {
                            Swal.fire({
                                title: "@lang('cms.success')",
                                text: data.msg,
                                icon: "success",
                                button: "@lang('cms.accept-2')",
                            });
                        }
                        if (data.status == 101) {
                            Swal.fire({
                                title: "@lang('cms.alert')",
                                text: data.msg,
                                icon: "warning",
                                button: "@lang('cms.accept-2')",
                            });
                        }


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

            }
        });
    </script>
    {{--   /******End Of Favorites******/--}}

    {{-- change --}}
    <script>

        var i = 0;
        var j = 0;
        /* when color initials and change color to show new price and new size */
        $(document).on('change', '#color', function () {
            i = 0;
            var selected = $('#color option:selected').val();
            var userActive = $('.sellers.active').attr('data-id');

            if (selected != null && selected != undefined && selected != 0) {

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "post",
                    url: "{{route('site.product.ajaxVariationColor')}}",
                    data: {
                        productId: <?= isset($product) ? $product->id : null  ?> ,
                        variationColor: selected,
                        user_id: userActive,
                        _token: CSRF_TOKEN
                    },

                    success: function (data) {
                        // alert(data);
                        if (data instanceof Object) {
                            $("#resultColor").html(data.html);
                        } else if (data != "") {
                            $('#ajax-price').html(data);
                            $('#resultColor').html("");
                        }
                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });

            } else {
                $('#resultColor').html("");
                var oldPrice = $('#old-price').val();
                var ajax_price = $('#ajax-price').html(oldPrice);

                $('#result-description').html("");
                $('#div-description').css('display', 'none');
            }

        });

        /* when on change size select option after ajax */
        $(document).on('change', '#sizeAjax', function () {
            var selected = $('#sizeAjax option:selected').val();
            var selectedColor = $('#color option:selected').val();
            if (selected != null && selected != undefined && selected != 0) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "post",
                    url: "{{route('site.product.ajaxVariationSize')}}",
                    data: {
                        productId: <?= isset($product) ? $product->id : null  ?> ,
                        variationSize: selected,
                        variationColors: selectedColor,
                        _token: CSRF_TOKEN
                    },

                    success: function (data) {
                        var price = data['price'];
                        var priceDiscount = data['priceDiscount'];
                        var description = data['description'];
                        console.log(description);
                        if (price != "") {


                            if (priceDiscount != "") {
                                $('.discount-price').css('display', 'block');
                                $('#ajax-priceDiscount').html(priceDiscount);
                                $('#ajax-price').addClass('product-price-orgin');
                            } else {
                                $('#ajax-price').removeClass('offed');
                                $('#ajax-priceDiscount').html("");
                                $('.discount-price').css('display', 'none');
                            }
                            $('#ajax-price').html(price);


                            if (description != "") {
                                $('#div-description').css('display', 'block');
                                $('#result-description').html(description);
                            } else {
                                $('#div-description').css('display', 'none');
                            }
                        }
                        //alert(data);

                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });
            } else {
                $('#result-description').html("");
                $('#div-description').css('display', 'none');
            }
        });

        /* when change size */
        $(document).on('change', '#size', function () {
            var userActive = $('.sellers.active').attr('data-id');
            var selected = $('#size option:selected').val();
            if (selected != null && selected != undefined && selected != 0) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: "post",
                    url: "{{route('site.product.ajaxVariationSize')}}",
                    data: {
                        productId: <?= isset($product) ? $product->id : null  ?> ,
                        variationSize: selected,
                        user_id: userActive,
                        _token: CSRF_TOKEN
                    },

                    success: function (data) {
                        var description = data['description'];
                        var price = data['price'];
                        var priceDiscount = data['priceDiscount'];
                        if (price != "") {

                            if (priceDiscount != "") {
                                $('.discount-price').css('display', 'block');
                                $('#ajax-priceDiscount').html(priceDiscount);
                                $('#ajax-price').addClass('product-price-orgin');
                            } else {
                                $('#ajax-price').removeClass('offed');
                                $('#ajax-priceDiscount').html("");
                                $('.discount-price').css('display', 'none');
                            }
                            $('#ajax-price').html(price);

                            if (description != "") {
                                $('#div-description').css('display', 'block');
                                $('#result-description').html(description);
                            } else {
                                $('#div-description').css('display', 'none');
                            }
                        }
                        //alert(data);

                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });

            } else {
                var oldPrice = $('#old-price').val();
                var ajax_price = $('#ajax-price').html(oldPrice);
                $('#div-description').css('display', 'none');
                $('#result-description').html("");
            }
        });

    </script>

    {{-- when color active --}}
    <script>
        jQuery(function ($) {
            /*  var i = 0;*/
            $(document).ajaxStop(function () {

                if (i == 0) {
                    var selectedColor = $('#color option:selected').val();
                    var userActive = $('.sellers.active').attr('data-id');
                    if (selectedColor != null && selectedColor != undefined && selectedColor != 0) {

                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            type: "post",
                            url: "{{route('site.product.ajaxVariationColor')}}",
                            data: {
                                productId: <?= isset($product) ? $product->id : null  ?> ,
                                variationColor: selectedColor,
                                user_id: userActive,
                                _token: CSRF_TOKEN
                            },

                            success: function (data) {
                                // alert(data);
                                console.log(data);
                                if (data instanceof Object) {
                                    var price = data['price'];
                                    var priceDiscount = data['priceDiscount'];
                                    $("#resultColor").html(data.html);

                                    if (price != "") {
                                        //  alert(priceDiscount);
                                        if (priceDiscount != "") {
                                            $('.discount-price').css('display', 'block');
                                            $('#ajax-priceDiscount').html(priceDiscount);
                                            $('#ajax-price').addClass('product-price-orgin');
                                        } else {
                                            $('#ajax-price').removeClass('offed');
                                            $('#ajax-priceDiscount').html("");
                                            $('.discount-price').css('display', 'none');
                                        }
                                        $('#ajax-price').html(price);

                                    }

                                    if (data.description != "") {
                                        $('#div-description').css('display', 'block');
                                        $('#result-description').html(data.description);
                                    } else {
                                        $('#result-description').html("");
                                        $('#div-description').css('display', 'none');
                                    }

                                } else if (data != "") {
                                    $('#ajax-price').html(data);
                                    $('#resultColor').html("");
                                }
                            },
                            error: function (error) {
                                //alert(error);
                                alert("لطفا چند لحظه دیگر وارد شوید.");
                            }
                        });

                    } else {
                        $('#resultColor').html("");
                        var oldPrice = $('#old-price').val();
                        var ajax_price = $('#ajax-price').html(oldPrice);

                        //$('#result-description').html("");
                        // $('#div-description').css('display', 'none');

                    }
                }
                i = 1;
            });
        });
    </script>

    {{-- when size active --}}
    <script>
        jQuery(function ($) {
            $(document).ajaxStop(function () {
                if (j == 0) {
                    var selected = $('#size option:selected').val();
                    var userActive = $('.sellers.active').attr('data-id');
                    if (selected != null && selected != undefined && selected != 0) {
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            type: "post",
                            url: "{{route('site.product.ajaxVariationSize')}}",
                            data: {
                                productId: <?= isset($product) ? $product->id : null  ?> ,
                                variationSize: selected,
                                user_id: userActive,
                                _token: CSRF_TOKEN
                            },

                            success: function (data) {

                                var description = data['description'];
                                var price = data['price'];
                                var priceDiscount = data['priceDiscount'];
                                /*alert(priceDiscount);*/
                                if (price != "") {

                                    if (priceDiscount != "") {
                                        $('.discount-price').css('display', 'block');
                                        $('#ajax-priceDiscount').html(priceDiscount);
                                        $('#ajax-price').addClass('product-price-orgin');
                                    } else {
                                        $('#ajax-price').removeClass('offed');
                                        $('#ajax-priceDiscount').html("");
                                        $('.discount-price').css('display', 'none');
                                    }
                                    $('#ajax-price').html(price);


                                    if (description != "") {
                                        $('#div-description').css('display', 'block');
                                        $('#result-description').html(description);
                                    } else {
                                        $('#div-description').css('display', 'none');
                                    }
                                }
                                //alert(data);

                            },
                            error: function (error) {
                                //alert(error);
                                alert("لطفا چند لحظه دیگر وارد شوید.");
                            }
                        });

                    } else {
                        var oldPrice = $('#old-price').val();
                        var ajax_price = $('#ajax-price').html(oldPrice);
                        // $('#div-description').css('display', 'none');
                        /* $('#result-description').html("");*/
                    }
                }
                j = 1;
            });
        });
    </script>

    {{-- seller set active --}}
    <script>
        jQuery(function ($) {
            /* seller => change price when clicked on seller */
            $('.sellers').click(function (e) {

                i = 0;
                j = 0;
                e.preventDefault();
                $('.sellers').removeClass('active');
                var thiss = $(this).addClass('active');
                var user_id = thiss.attr('data-id');

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "post",
                    url: "{{route('site.product.ajaxSeller')}}",
                    data: {
                        productId: <?= isset($product) ? $product->id : null  ?> ,
                        user_id: user_id,
                        _token: CSRF_TOKEN
                    },

                    success: function (data) {
                        var description = data['description'];
                        var price = data['price'];
                        var priceDiscount = data['priceDiscount'];

                        var variety = data['variety'];
                        if (variety == 0) {
                            $('#variety').html("");
                            if (price != "") {

                                if (priceDiscount != "") {
                                    $('.discount-price').css('display', 'block');
                                    $('#ajax-priceDiscount').html(priceDiscount);
                                    $('#ajax-price').addClass('product-price-orgin');
                                } else {
                                    $('#ajax-price').removeClass('offed');
                                    $('#ajax-priceDiscount').html("");
                                    $('.discount-price').css('display', 'none');
                                }
                                $('#ajax-price').html(price);


                                if (description != "") {
                                    $('#div-description').css('display', 'block');
                                    $('#result-description').html(description);
                                } else {
                                    $('#div-description').css('display', 'none');
                                }
                            }
                        } else {
                            $('#ajax-price').html(price);
                            $('#variety').html(data.html);
                            // $('#div-description').css('display', 'none');
                        }
                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });
            });

            /* when seller is active  */
            var user_id = $('.sellers.active').attr('data-id');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "{{route('site.product.ajaxSeller')}}",
                data: {
                    productId: <?= isset($product) ? $product->id : null  ?> ,
                    user_id: user_id,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                    if (data instanceof Object) {
                        var description = data['description'];
                        var price = data['price'];
                        var priceDiscount = data['priceDiscount'];

                        var variety = data['variety'];
                        if (variety == 0) {
                            $('#variety').html("");
                            if (price != "") {

                                if (priceDiscount != "") {
                                    $('.discount-price').css('display', 'block');
                                    $('#ajax-priceDiscount').html(priceDiscount);
                                    $('#ajax-price').addClass('product-price-orgin');
                                } else {
                                    $('#ajax-price').removeClass('offed');
                                    $('#ajax-priceDiscount').html("");
                                    $('.discount-price').css('display', 'none');
                                }
                                $('#ajax-price').html(price);


                                if (description != "") {
                                    $('#div-description').css('display', 'block');
                                    $('#result-description').html(description);
                                } else {
                                    $('#div-description').css('display', 'none');
                                }
                            }
                        } else {
                            $('#variety').html(data.html);
                        }
                    } else {
                    }
                },
                error: function (error) {
                    //alert(error);
                    alert("لطفا چند لحظه دیگر وارد شوید.");
                }
            });
        });
    </script>

    {{-- Start Of add Suggestion --}}
    <script>
        $('.add-suggestion').on('click', function (e) {
            e.preventDefault();
            var auctionId = "{{ $product->auction->id }}";
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "{{route('site.store.suggestion')}}",
                data: {
                    auctionId: auctionId,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                    console.log(data);
                    if (data.status == 403) {
                        Swal.fire({
                            title: "متاسفیم!",
                            text: data.message,
                            icon: "error",
                            button: "تایید",
                        });
                    }

                    if (data.status == 200) {
                        @if(isset($product->auction->suggestion) && !empty($product->auction->suggestion) && count($product->auction->suggestion) >= 1)
                        Swal.fire({
                            title: "موفقیت آمیز!",
                            text: data.message,
                            icon: "success",
                            showCancelButton: false,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: false,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "بستن",
                        });
                        @else
                        Swal.fire({
                            title: "موفقیت آمیز!",
                            text: data.message,
                            icon: "success",
                            showCancelButton: false,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: false,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "بستن",
                        }).then(function () {
                            location.reload();
                        });
                        @endif
                        $('#timer').css("display", "block");
                        AuctionCountDown(data.latestSuggestion);

                    } else {
                        Swal.fire({
                            title: "متاسفیم!",
                            text: data.message,
                            icon: "error",
                            button: "تایید",
                        });
                    }

                },
                error: function (error) {
                    Swal.fire({
                        title: "متاسفیم!",
                        text: "لطفا بعدا تلاش فرمایید.",
                        icon: "error",
                        button: "تایید",
                    });

                }
            });
        });
    </script>
    {{-- End Of add Suggestion --}}

    {{-- Start Of  Read Real Suggestion Data--}}
    @if($product->auction->status == 1)
        <script>
            var interval;
            $(document).ready(function () {
                clearInterval(interval);
                interval = setInterval(function () {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: "post",
                        url: "{{route('site.update.suggestions')}}",
                        data: {
                            auctionId: "{{ $product->auction->id }}",
                            _token: CSRF_TOKEN
                        },
                        success: function (data) {
                            $('.latest-suggestion').html(data.html);
                            AuctionCountDown(data.latestSuggestion, data.status);
                        }
                    });
                }, 1000);
            });
        </script>
    @endif
    {{-- End Of  Read Real Suggestion Data--}}

@endsection
