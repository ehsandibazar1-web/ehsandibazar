<div class="card cart_totals all-basket text-center">
    <h2>@lang('cms.all-cart-price')</h2>
    <table class="shop_table shop_table_responsive" cellspacing="0">
        <tbody>
        @if(isset(reset($basket->items)['type']) && reset($basket->items)['type'] == \App\Utility\ProductType::SIMPLE)
            <p class="text-center shipping-section">
                @if($shippingCost)
                    <span>
                    <img class="delivery" src="{{ url('site_theme/assets/img/delivery.png') }}" alt="delivery">
                </span>
                    <label for="shipping-cost">هزینه ارسال :</label>
                    @if(isset($freeShippingTwo) && !empty($freeShippingTwo))
                        <span class="shipping-value">{!!  (isset($shippingCost) && $shippingCost > 0) || ($basket->totalPrice >= $freeShippingTwo->code) ? number_format($shippingCost) : " <span class='text-danger'><b>رایگان</b></span> (خرید بیش از  ".number_format($freeShippingTwo->code)." تومان ،هزینه ارسال رایگان خواهد بود) "  !!}</span>
                    @else
                        <span class="shipping-value">{!!  (isset($shippingCost) && $shippingCost > 0) ? number_format($shippingCost) : '---'  !!}</span>
                    @endif
                @endif
            </p>
        @endif
        @if(isset($basket) && !empty($basket))
            @php
                $priceDiscount = \Illuminate\Support\Facades\Session::get('finishPrice');
            @endphp
            @if(isset($tax) && isset($tax->code) && !empty($tax->code))
                <div class="col-md-12 text-center"><span
                            class="font-weight-bold">@lang('cms.all-price-with')
                    </span>
                    <span class="font-weight-bold"> {{$tax->code}}%  </span><span
                            class="font-weight-bold"> @lang('cms.tax') :‌</span>


                    @if(isset($priceDiscount) && !empty($priceDiscount))
                        @php
                            $total = $priceDiscount;

                            if($total <= 0){
                                 \App\Utility\forgetSession::forgetSession();
                                 \App\Utility\forgetSession::forgetSession(1);
                                 alert()->error(\Illuminate\Support\Facades\Lang::get('cms.finish-count-product'),\Illuminate\Support\Facades\Lang::get('cms.error'))->showConfirmButton('بستن');
                                 header('location: '.route('site.index'));
                                 dd();
                            }

                            $priceDiscount = \App\Utility\taxCalculate::taxCalculate($total,1);
                        @endphp
                        <span> {{ \App\Utility\unit::unit(\App\Http\Controllers\Site\CheckoutController::shippingCost($priceDiscount))  }} </span>
                        {{--                                                        <span>{{ \App\Utility\taxCalculate::taxCalculate($priceDiscount) }}</span>--}}
                    @else
                        @php
                            $total = $basket->totalPrice;

                            if($total <= 0){
                                \App\Utility\forgetSession::forgetSession();
                                \App\Utility\forgetSession::forgetSession(1);
                                alert()->error(\Illuminate\Support\Facades\Lang::get('cms.finish-count-product'),\Illuminate\Support\Facades\Lang::get('cms.error'))->showConfirmButton('بستن');
                                header('location: '.route('site.index'));
                                dd();
                            }

                               $totalPrice =  \App\Utility\taxCalculate::taxCalculate($total , 1);
                        @endphp
                        <span> {{ \App\Utility\unit::unit(\App\Http\Controllers\Site\CheckoutController::shippingCost($totalPrice))  }} </span>
                        {{--                                                        <span>{{ \App\Utility\taxCalculate::taxCalculate($basket->totalPrice)}}</span>--}}
                    @endif
                    <img class="wallet" width="100px" src="{{ url('site_theme/assets/img/wallet.png')  }}" alt="wallet">
                </div>
            @else
                <div class="col-md-12 text-center  pt-5">
                    <span class="font-weight-bold"> @lang('cms.all-prices')  : </span>
                    @if(isset($priceDiscount) && !empty($priceDiscount))
                        @php
                            $total = $priceDiscount;
                            if($total <= 0){
                                 \App\Utility\forgetSession::forgetSession();
                                 \App\Utility\forgetSession::forgetSession(1);
                                 alert()->error(\Illuminate\Support\Facades\Lang::get('cms.finish-count-product'),\Illuminate\Support\Facades\Lang::get('cms.error'))->showConfirmButton('بستن');
                                 header('location: '.route('site.index'));
                                 dd();
                            }
                        @endphp
                        <span
                                class="total-price">{{ isset($basket) && !empty($basket) ? \App\Utility\unit::unit(\App\Http\Controllers\Site\CheckoutController::shippingCost($total)) : 0 }}</span>
                        <br>
                        <span class="font-weight-bold"> مبلغ پرداختی  : </span>
                        <span
                                class="price-for-pay">{{ isset($basket) && !empty($basket) ? \App\Utility\unit::unit(\App\Http\Controllers\Site\CheckoutController::shippingCost($total)+$shippingCost) : 0 }}</span>


                    @else
                        @php

                            $total = $basket->totalPrice;
                            if($total <= 0){
                                 \App\Utility\forgetSession::forgetSession();
                                 \App\Utility\forgetSession::forgetSession(1);
                                 alert()->error(\Illuminate\Support\Facades\Lang::get('cms.finish-count-product'),\Illuminate\Support\Facades\Lang::get('cms.error'))->showConfirmButton(\Illuminate\Support\Facades\Lang::get('cms.close'));
                                 header('location: '.route('site.index'));
                                 dd();
                            }
                        @endphp
                        <span>{{ isset($basket) && !empty($basket) ? \App\Utility\unit::unit(\App\Http\Controllers\Site\CheckoutController::shippingCost($total)) : 0 }}</span>
                    @endif

                </div>
                <div class="col-12">
                    <img class="img-responsive"
                         src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcSwChUVvIM6ih8_m4ZNYUiF-mkOArMkb38r8A&usqp=CAU">
                </div>
            @endif
        @endif
        </tbody>
    </table>

    @if(isset($finish) && $finish == 0)
        @if(reset($basket->items)['type'] == \App\Utility\ProductType::SIMPLE)
            <a href="{{route('site.basket.checkout.address')}}"
               class="checkout-button button alt wc-forward s">
                <div class="proceed-to-checkout">
                    @lang('cms.next')
                </div>
            </a>
        @else
            @include('generals.allErrors')
            <form action="{{route('site.basket.finish')}}" method="post">
                <input type="hidden" name="postType" value="{{ \App\Model\ShippingCost::TYPES_OF_POST['digital'] }}">
                @csrf
                <input value="{{\App\Utility\paymentMethods::ONLINE}}" type="hidden" name="payment">
                <input value="174" type="hidden" name="shipping-method">

                <button class="btn btn-ctm-finally">پرداخت</button>
            </form>
        @endif
    @else
        <br>
    @endif

</div>
