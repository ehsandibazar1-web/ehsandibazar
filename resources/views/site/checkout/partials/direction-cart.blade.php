<div class="ct-header">
    <div class="light-gray"></div>
    <div class="container">
        <div class="col-sm-12">
            <div class="f1-steps">
                <div class="f1-progress">
                    <div class="f1-progress-line" data-now-value="{{ isset($number)  ? $number : 15  }}" data-number-of-steps="3"
                         style="width: {{ isset($number)  ? $number : 15  }}%;"></div>
                </div>
                <div class="f1-step {{ isset($indexCheckout) ? $indexCheckout : "activated"  }}">
                    <div class="f1-step-icon"><i class="fa fa-shopping-cart"></i></div>
                    <p>سبدخرید</p>
                </div>
                <div class="f1-step {{ isset($addressCheckout) ? $addressCheckout : "activated"  }}">
                    <div class="f1-step-icon"><i class="fa fa-map-marker"></i></div>
                    <p>انتخاب آدرس</p>
                </div>
                <div class="f1-step {{ isset($reviewCheckout) ? $reviewCheckout : "activated" }} ">
                    <div class="f1-step-icon"><i class="fa fa-check"></i></div>
                    <p>تاییدیه</p>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>



