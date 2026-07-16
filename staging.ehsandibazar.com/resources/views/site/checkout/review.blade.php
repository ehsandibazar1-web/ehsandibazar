@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
@endsection

@section('content')
    @php
        $finish = 0;
        $checked=0;
        $nextUrl = route('site.basket.finish');
    @endphp
    <div class="wrapper default shopping-page">
        <main class="cart-page default">
            <div class="container">
                <div class="row pb-5">
                    <div class="col-xl-9 col-lg-8 col-md-12">
                        @if(isset($basket) && !empty($basket) && isset($basket->items))
                            <div class="cart-page-content">
                                @include('generals.allErrors')
                                <div class="cart-page-title">
                                    <h1>کالا های درخواستی</h1>
                                </div>
                                <div class="table-responsive checkout-content default">
                                    <table class="table">
                                        <tbody>
                                        @foreach($basket->items as $items)
                                            @php
                                                $findVariation = \App\Utility\Variation::findVariation($items['item']->variation_id);
                                            @endphp
                                            <tr class="checkout-item">
                                                <td>
                                                    <img src="{{ $items['item']->image }}"
                                                         alt="{{ $items['item']->title }}">
                                                </td>
                                                <td>
                                                    <h3 class="checkout-title">
                                                        {{ $items['item']->title }}
                                                    </h3>
                                                </td>
                                                <td>{{ $items['qty'] }} عدد</td>
                                                <td>
                                                    @if(is_null($items['item']->discountPrice) || empty($items['item']->discountPrice) || $items['item']->discountPrice == null || auth()->user()->isColleague())
                                                        {{ \App\Utility\unit::unit($items['item']->price)  }}
                                                    @else
                                                        {{ \App\Utility\unit::unit($items['item']->discountPrice)  }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        @if(isset($sessionAddress) && !empty($sessionAddress))
                            <div class="cart-page-content">
                                <div class="cart-page-title">
                                    <h1> آدرس تحویل سفارش</h1>
                                </div>
                                <div class="address-section">
                                    <div class="checkout-contact">
                                        <div class="checkout-contact-content">
                                            <ul class="checkout-contact-items">
                                                <li class="checkout-contact-item">
                                                    گیرنده:
                                                    <span class="full-name">{{ $sessionAddress->name }}</span>
                                                </li>
                                                <li class="checkout-contact-item">
                                                    <div class="checkout-contact-item checkout-contact-item-mobile">
                                                        شماره تماس:
                                                        <span class="mobile-phone">{{ $sessionAddress->mobile  }}</span>
                                                    </div>
                                                    <div class="checkout-contact-item-message">
                                                        کد پستی:
                                                        <span class="post-code"> {{ $sessionAddress->postal_code  }}</span>
                                                    </div>
                                                    <br>
                                                    استان
                                                    <span class="state">{{ $sessionAddress->province->name }}</span>
                                                    ، ‌شهر
                                                    <span class="city">{{ $sessionAddress->city->name }}</span>
                                                    ،
                                                    <span class="address-part">{{ $sessionAddress->fullAddress }}</span>
                                                </li>
                                            </ul>
                                            <div class="checkout-contact-badge">
                                                <i class="now-ui-icons ui-1_check"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 p-0 pl-md-3 cart-page-content">
                                <div class="cart-page-title">
                                    <h1>انتخاب شیوه پرداخت</h1>
                                </div>
                                <section class="page-content default">
                                    @include('site.checkout.partials.payment-methods')
                                </section>
                            </div>
                            <div class="col-md-6 p-0 pr-md-3  cart-page-content">
                                @include('site.checkout.partials.shipping-methods')
                            </div>
                        </div>
                    </div>

                    @include('site.checkout.partials.card-aside',compact('checked'))
                </div>
            </div>
        </main>
    </div>
@endsection

@section('site-js')
    <script src="{{asset('site_themes/js/ajax.js')}}"></script>
@endsection
@section('title',$title)