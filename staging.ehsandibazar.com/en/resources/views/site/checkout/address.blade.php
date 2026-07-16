@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
@endsection
@section('title',$title)
@section('content')
    @php
        $finish = 0;
        $nextUrl = route('site.basket.checkout.review');
    @endphp
    <div class="wrapper default shopping-page">
        <main class="cart-page default">
            <div class="container">
                <div class="row">
                    <div class="cart-page-content col-xl-9 col-lg-8 col-md-12 order-1">
                        <div class="cart-page-title">
                            <h1>انتخاب آدرس تحویل سفارش</h1>
                        </div>
                        <section class="page-content default">
                            @if(isset($user) && isset($user->address) && !empty($user->address))
                                @foreach($user->address as $key => $itemAddress)
                                    <div class="address-section" >
                                        <label class="checkout-contact" style="width: 100%">
                                            <div class="checkout-contact-content">
                                                <ul class="checkout-contact-items">
                                                    <li class="checkout-contact-item">
                                                        گیرنده:
                                                        <span class="full-name">{{ $itemAddress->name }}</span>
                                                        @if(\Illuminate\Support\Facades\Auth::user()->isCustomer())
                                                            <a class="checkout-contact-btn-edit">اصلاح این آدرس</a>
                                                        @endif
                                                    </li>
                                                    <li class="checkout-contact-item">
                                                        <div class="checkout-contact-item checkout-contact-item-mobile">
                                                            شماره تماس:
                                                            <span class="mobile-phone">{{ $itemAddress->mobile  }}</span>
                                                        </div>
                                                        <div class="checkout-contact-item-message">
                                                            کد پستی:
                                                            <span class="post-code"> {{ $itemAddress->postal_code  }}</span>
                                                        </div>
                                                        <br>
                                                        استان
                                                        <span class="state">{{ $itemAddress->province->name }}</span>
                                                        ، ‌شهر
                                                        <span class="city">{{ $itemAddress->city->name }}</span>
                                                        ،
                                                        <span class="address-part">{{ $itemAddress->fullAddress }}</span>
                                                    </li>
                                                </ul>
                                                <div class="checkout-contact-badge">
                                                    @if(isset($sessionAddress) && !empty($sessionAddress))
                                                        <input type='radio'
                                                               {{ $sessionAddress->id == $itemAddress->id ? "checked" : null  }} name="address"
                                                               data-attr="{{$itemAddress->id}}"
                                                               id='checkbox-{{$itemAddress->id}}'>
                                                    @else
                                                        <input type='radio' checked name="address"
                                                               data-attr="{{$itemAddress->id}}"
                                                               id='checkbox-{{$itemAddress->id}}'>
                                                    @endif
                                                </div>
                                            </div>
                                            {{--                                            <a class="checkout-contact-location">ویرایش</a>--}}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                            @if(isset($basket) && !empty($basket) && isset($basket->items))
                                <form method="post" id="shipping-data-form">
                                    <div class="headline">
                                        <span>{{ $basket->totalQty }} کالا در سبد خرید  </span>
                                    </div>
                                    <div class="checkout-pack">
                                        <section class="products-compact">
                                            <div class="box">
                                                <div class="row">
                                                    @foreach($basket->items as $items)
                                                        @php
                                                            $findVariation = \App\Utility\Variation::findVariation($items['item']->variation_id);
                                                        @endphp
                                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                                            <div class="product-box-container">
                                                                <div class="product-box product-box-compact">
                                                                    <a class="product-box-img" href="{{ $findVariation->product->path() }}">
                                                                        <img src="{{ $items['item']->image }}" alt="{{ $items['item']->title }}">
                                                                    </a>
                                                                    <div class="product-box-title">
                                                                        {{ $items['item']->title }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </form>
                            @endif
                        </section>
                    </div>
                    @include('site.checkout.partials.card-aside')
                </div>
            </div>
        </main>
    </div>
@endsection

@section('site-js')
    {{-- choose address --}}
    <script>
        $('input[type="radio"]').change(function () {
            if ($(this).is(':checked')) {
                var address = $(this).attr('data-attr');
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: "post",
                    url: "{{route('site.basket.address.check')}}",
                    data: {
                        address: address,
                        _token: CSRF_TOKEN
                    },
                    success: function (data) {
                        if (data.status == 200) {
                            var address_get_session = data.message.fullAddress;
                            $('.address-session-get').html(address_get_session);
                        }
                        console.log(data);
                        if (data.status == 100) {

                            Swal.fire({
                                title: "خطا!",
                                text: data.message,
                                icon: "error",
                                showCancelButton: false,
                                closeOnConfirm: false,
                                showLoaderOnConfirm: false,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "بستن",
                            }).then(function () {
                                location.reload();
                            });
                        }
                        //$(".cart").load(" .cart > *");
                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });
            }
        });
    </script>
@endsection

