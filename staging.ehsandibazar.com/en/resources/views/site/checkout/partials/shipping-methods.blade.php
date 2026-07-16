<div class="cart-page-title">
    <h1>انتخاب شیوه ارسال</h1>
</div>
<ul class="checkout-paymethod">
    @foreach($shippingMethods as $key => $shippingMethod)
        @if(($shippingMethod->id == 172 && $totalWeight > 5000) || ($shippingMethod->id == 174 && $postType == \App\Model\ShippingCost::TYPES_OF_POST['suburban']))
        @else
            <li>
                <div class="checkout-paymethod-item checkout-paymethod-item-ccc has-options">
                    <div class="radio">
                        <input value="{{$shippingMethod->id}}"
                               id="shipping-method{{$key}}"
                               {{ $shippingMethod->id == 173 ? 'checked' : null }}
                               class="radio-payment shipping{{$shippingMethod->id}}"
                               type="radio"
                               name="shipping-methods" {{ ($shippingMethod->id == 172 && $totalWeight > 5000) || ($shippingMethod->id == 174 && $postType == \App\Model\ShippingCost::TYPES_OF_POST['suburban']) ||
                                        (isset($freeShippingTwo) && $basket->totalPrice >= $freeShippingTwo->code &&   $shippingMethod->id != 174)  ? 'disabled' : null }} >

                        <label for="shipping-method{{$key}}"  onclick="setShippingCost('{{route('site.basket.checkout.shippingCost')}}','{{$shippingMethod->id}}','{{$totalWeight}}','{{$shippingMethod->id}}')">
                            <div>
                                <h4 class="checkout-paymethod-title">
                                    <div>
                                        <p class="checkout-paymethod-title-label">
                                            {!! $shippingMethod->name !!}
                                        </p>
                                    </div>
                                    <span></span>
                                </h4>
                            </div>
                            <div class="checkout-paymethod-one-gateway">
                                <div class="checkout-paymethod-one-gateway-img">
                                    <img class="img-fluid img-thumbnail"
                                         src="{{$shippingMethod->code5}}"
                                         alt="online-payment"
                                         id="shipping-method{{$key}}">
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </li>
        @endif
    @endforeach
</ul>