<ul class="checkout-paymethod">
    <li>
        <div class="checkout-paymethod-item checkout-paymethod-item-cc has-options">
            <div class="radio">
                <input value="{{\App\Utility\paymentMethods::ONLINE}}" id="payment-online" checked class="radio-payment" type="radio" name="payment">
                <label for="payment-online" onclick="setPaymentType('{{ \App\Utility\paymentMethods::ONLINE }}')">
                    <div>
                        <h4 class="checkout-paymethod-title">
                            <div>
                                <p class="checkout-paymethod-title-label">
                                    پرداخت اینترنتی ( آنلاین با تمامی کارت‌های بانکی )
                                </p>
                            </div>
                            <span>سرعت بیشتر در ارسال و پردازش سفارش </span>
                        </h4>
                    </div>
                </label>
            </div>
        </div>
    </li>
    <li>
        <div class="checkout-paymethod-item checkout-paymethod-item-cc has-options">
            <div class="radio">
                <input value="{{ \App\Utility\paymentMethods::DELIVARY }}" id="payment-home" class="radio-payment" type="radio" name="payment"/>
                <label for="payment-home" onclick="setPaymentType('{{ \App\Utility\paymentMethods::DELIVARY }}')">
                    <div>
                        <h4 class="checkout-paymethod-title">
                            <div>
                                <p class="checkout-paymethod-title-label">
                                    پرداخت در محل ( تسویه درب منزل )
                                </p>
                            </div>
                            <span></span>
                        </h4>
                    </div>
                </label>
            </div>
        </div>
    </li>
{{--    <li>--}}
{{--        <div class="checkout-paymethod-item checkout-paymethod-item-cc has-options">--}}
{{--            <div class="radio">--}}
{{--                <input value="{{ \App\Utility\paymentMethods::WALLET }}" id="payment-wallet" class="radio-payment" type="radio" name="payment"/>--}}
{{--                <label for="payment-wallet" onclick="setPaymentType('{{ \App\Utility\paymentMethods::WALLET }}')">--}}
{{--                    <div>--}}
{{--                        <h4 class="checkout-paymethod-title">--}}
{{--                            <div>--}}
{{--                                <p class="checkout-paymethod-title-label">--}}
{{--                                    کیف پول  ( پرداخت از طریق کیف پول دیجیتال خود )--}}
{{--                                </p>--}}
{{--                            </div>--}}
{{--                            <span></span>--}}
{{--                        </h4>--}}
{{--                    </div>--}}
{{--                </label>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </li>--}}
</ul>
