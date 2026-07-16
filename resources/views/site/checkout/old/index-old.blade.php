@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" href="{{ Url('site_theme') }}/css/checkout.css">
@endsection
@section('title',$title)

@section('content')

    {{-- header cart --}}
    <div class="row">
        @php
            $indexCheckout = "active";
            $number = 15;
        @endphp
        @include('site.checkout.partials.direction-cart',[ $indexCheckout , $number ])
    </div>


    <div class="page">
        <div class="container">
            <div class="content">
                <div class="shopping-content">
                    <div class="container">
                        @if(isset($basket) && !empty($basket) && isset($basket->items))
                            <div class="card shopping-cart">
                                <table class="table table-hover shopping-cart-wrap">
                                    <thead class="text-muted">
                                    <tr>
                                        <th class="text-center">محصول</th>
                                        <th class="text-center">قیمت</th>
                                        <th class="text-center">تعداد</th>
                                        <th class="text-center">عملیات</th>
                                        {{-- <th class="text-right"></th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($basket->items as $items)
                                        <?php
                                        $findVariation = \App\Utility\Variation::findVariation($items['item']->variation_id);
                                        /*   \App\Model\Variation::where('id', $items['item']->variation_id)->first();*/
                                        ?>

                                        <tr>
                                            <th class="th-mob">محصول</th>
                                            <td>
                                                <figure class="media">
                                                    <div class="img-wrap"><img src="{{$items['item']->image}}"
                                                                               alt="{{$items['item']->title}}"
                                                                               class="img-thumbnail img-sm"></div>
                                                    <figcaption class="media-body">
                                                        <h3 class="title text-truncate">
                                                            {{$items['item']->title}}
                                                            @if($items['item']->shipping_cost == 0)
                                                                <span class="text-danger">(ارسال رایگان)</span>
                                                            @endif
                                                        </h3>
                                                        <dl class="param param-inline small">
                                                            @if($findVariation->attributeTypeValue->attribute_type_id == \App\Utility\Variation::COLOR)
                                                                <div class="pull-right">
                                                                    رنگ :‌
                                                                    {{ $findVariation->attributeTypeValue->value }}
                                                                </div>
                                                            @endif

                                                            @if(isset($items['item']->relatedVariation) && !empty($items['item']->relatedVariation) )
                                                                <br>
                                                                <div class="pull-right">
                                                                    سایز :
                                                                    {{ isset($findVariation->relatedVariations) && !empty($findVariation->relatedVariations[0]->attribute_type_value_id) ? $findVariation->relatedVariations[0]->attributeTypeValue->value : null }}
                                                                </div>
                                                            @endif

                                                            @if($findVariation->attributeTypeValue->attribute_type_id == \App\Utility\Variation::SIZE)
                                                                <div class="pull-right">
                                                                    سایز :‌
                                                                    {{ $findVariation->attributeTypeValue->value }}
                                                                </div>
                                                            @endif
                                                        </dl>
                                                    </figcaption>
                                                </figure>
                                            </td>


                                            <th class="th-mob text-center">قیمت</th>
                                            <td>
                                                <div class="">
                                                    <var class="price text-center">
                                                        @if(is_null($items['item']->discountPrice) || empty($items['item']->discountPrice) || $items['item']->discountPrice == null || auth()->user()->isColleague())
                                                            {{ \App\Utility\unit::unit($items['item']->price)  }}
                                                        @else
                                                            {{ \App\Utility\unit::unit($items['item']->discountPrice)  }}
                                                        @endif
                                                    </var>
                                                    {{-- <var class="price">20000 تومان</var>
                                                     <small class="text-muted">20000 قیمت واحد</small>--}}
                                                </div> <!-- price-wrap .// -->
                                            </td>
                                            <th class="th-mob">تعداد</th>
                                            <td class="text-center">
                                                {{-- <input step="1" type="number" class="pr_num ml-3" value="1"
                                                        data-decimals="0" min="1"/>--}}
                                                {{$items['qty']}}
                                            </td>
                                            <th class="th-mob">وضعیت</th>
                                            <td class="text-center">
                                                <a href="" data-attr-add="{{$items['item']->variation_id}}"
                                                   class="btn btn-outline-info addProduct">+</a>
                                                <a href="" data-attr-min="{{$items['item']->variation_id}}"
                                                   class="btn btn-outline-danger minProduct">-</a>
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="cart-footer">
                                    <div class="row">
                                        <!--<div class="col-sm-6 col-xs-12">-->
                                        <!--    <input type="text" class="form-control coupon_input val-coupon"-->
                                        <!--           placeholder="کدتخفیف">-->
                                        <!--    <input type="submit" value="اعمال کد تخفیف"-->
                                        <!--           class="btn pull-left coupon btn-ctm ">-->
                                        <!--</div>-->


                                    </div>
                                </div>
                            </div> <!-- card.// -->
                            <div class="table-100 cart-fix">
                                @include('site.checkout.partials.total-basket' , ['finish' => 0])
                            </div>
                            <br>
                        @else
                            <p> @lang('cms.empty-basket') </p>
                        @endif
                    </div><!--container end.//-->
                </div>
            </div>
        </div>
    </div>

@endsection

@section('site-js')
    {{-- start add to basket--}}
    <script>
        $('.addProduct').on('click', function (e) {
            e.preventDefault();
            var addProduct = $(this).attr('data-attr-add');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "{{route('site.insertFromBasket')}}",
                data: {
                    variationID: addProduct,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                    console.log(data);
                    if (data.status == 403) {
                        Swal.fire({
                            title: "خطا!",
                            text: "لطفا ابتدا وارد حساب کاربری خود شوید.",
                            icon: "error",
                            button: "تایید",
                        });
                    }

                    if (data.status == 404) {
                        Swal.fire({
                            title: "خطا!",
                            text: "محصولی با این مشخصه یافت نشد.",
                            icon: "error",
                            button: "تایید",
                        });
                    }

                    if (data.status == 102) {
                        Swal.fire({
                            title: "خطا!",
                            text: "موجودی محصول برای تعداد انتخابی شما کافی نمی باشد.",
                            icon: "error",
                            button: "تایید",
                        });
                    }

                    if (data.status == 200) {

                        Swal.fire({
                            title: "موفقیت آمیز!",
                            text: "محصول مورد نظر به سبد خرید شما اضافه شد.",
                            icon: "success",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Yes, delete it!",
                        }).then(function () {
                            location.reload();
                        });
                    }
                    // $(".cart").load(" .cart > *");

                },
                error: function (error) {
                    //alert(error);
                    alert("لطفا چند لحظه دیگر وارد شوید.");
                }
            });
        })
    </script>
    {{-- end add to basket--}}

    {{-- start delete from basket --}}
    <script>

        $('.minProduct').on('click', function (e) {
            e.preventDefault();
            /* id product in session */
            var variationID = $(this).attr('data-attr-min');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: "post",
                url: "{{route('site.deleteFromBasket')}}",
                data: {
                    variation_id: variationID,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                    console.log(data);

                    if (data.status == 100) {
                        Swal.fire({
                            title: "خطا!",
                            text: "محصولی با این مشخصه یافت نشد.",
                            icon: "error",
                            button: "تایید",
                        });
                    }

                    if (data.status == 200) {

                        Swal.fire({
                            title: "موفقیت آمیز!",
                            text: "محصول مورد نظر از سبد خرید شما حذف شد",
                            icon: "success",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Yes, delete it!",
                        }).then(function () {
                            location.reload();
                        });
                    }

                    // location.reload();

                    // $(".cart").load(" .cart > *");

                },
                error: function (error) {
                    //alert(error);
                    alert("لطفا چند لحظه دیگر وارد شوید.");
                }
            });
        })

    </script>
    {{-- end delete from basket --}}

    {{-- start coupon --}}
    <script>
        $('.coupon').on('click', function (e) {
            e.preventDefault();
            let valCoupon = $('.val-coupon').val();
            if (!valCoupon) {
                Swal.fire({
                    title: "ناموفق!",
                    text: "کوپن خود را وارد نمایید",
                    icon: "error",
                    button: "تایید",
                });
            } else {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: "post",
                    url: "{{route('site.check.coupon')}}",
                    data: {
                        coupon: valCoupon,
                        userId: {{ auth()->user()->id }} ,
                        _token: CSRF_TOKEN
                    },

                    success: function (data) {
                        console.log(data);

                        if (data.status == 100) {
                            Swal.fire({
                                title: "ناموفق!",
                                text: data.message,
                                icon: "error",
                                button: "تایید",
                            });
                            $('.val-coupon').val('');
                        }

                        if (data.status == 200) {

                            Swal.fire({
                                title: "موفق!",
                                text: data.message,
                                icon: "success",
                                button: "تایید",
                            }).then(function () {
                                // location.reload();
                                $(".cart_totals").load(" .cart_totals > *");
                                $('.val-coupon').val('');
                            });

                        }


                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });

            }

        });
    </script>
    {{-- end coupon --}}
@endsection
