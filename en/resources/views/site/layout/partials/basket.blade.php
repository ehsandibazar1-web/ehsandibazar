<div class="hidden-cart">
    <div class="hidden-cart-close">
        <span>Cart</span>
        <span class="label label-danger">{{ isset($sessionBasket) && !empty($sessionBasket) ? $sessionBasket->totalQty : 0  }}</span>
        <a href="javascript:void(0)" class="float-left"><i class="fas fa-times"></i></a>
    </div>
    @if(empty($showDetails))
        <div class="hidden-cart-body">
            @if(isset($sessionBasket) && isset($sessionBasket->items) && !empty($sessionBasket))
                @foreach($sessionBasket->items as $items)
                    <?php
                    $findVariation = \App\Model\Variation::where('id', $items['item']->variation_id)->first();
                    if (is_null($findVariation)) {
                        \App\Utility\forgetSession::forgetSession();
                        \App\Utility\forgetSession::forgetSession(1);
                    }
                    ?>
                    @if(isset($findVariation) && !empty($findVariation))
                        <div class="hidden-cart-item">
                            <div class="row">
                                <div class="col-2">
                                    <div class="cart-item-delete">
                                        <a href="javascript:void(0)" class="cart-remove-span"  attr-id="{{$items['item']->variation_id}}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="cart-item-details">
                                        <h4>{{ str_limit($items['item']->title , 30) }}</h4>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="cart-item-pic">
                                        <img src="{{$items['item']->image}}" alt="{{$items['item']->title}}">
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="row bt1">
                                    <div class="col-12">
                                        <span class="float-left">تعداد:{{ $items['qty'] }}</span>
                                        <span class="float-right">قیمت : @if(is_null($items['item']->discountPrice) || empty($items['item']->discountPrice) || $items['item']->discountPrice == null)
                                                {{ \App\Utility\unit::unit($items['item']->price)  }}
                                            @else
                                                {{ \App\Utility\unit::unit($items['item']->discountPrice)  }}
                                            @endif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <?php
                        \App\Utility\forgetSession::forgetSession();
                        \App\Utility\forgetSession::forgetSession(1);
                        alert()->error(\Illuminate\Support\Facades\Lang::get('cms.finish-count-product'), \Illuminate\Support\Facades\Lang::get('cms.error2'))->persistent('بستن');
                        ?>
                    @endif
                @endforeach
                <div class="hidden-cart-payment-details">
                    <h5>Payment details</h5>
                    <div class="hidden-cart-payment-details-body">
                        <ul>
                            <li>
                                <span class="float-right">Total Amount</span>
                                <span class="float-left"> {{  isset($sessionBasket) && !empty($sessionBasket) ? \App\Utility\unit::unit($sessionBasket->totalPrice) : 0 }}</span>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="hidden-cart-footer">
                <span class="w1 float-right">
                    <span>Amount of money That had to be paid</span>
                    <span> {{  isset($sessionBasket) && !empty($sessionBasket) ? \App\Utility\unit::unit($sessionBasket->totalPrice) : 0 }}</span>
                </span>
                    <a href="{{route('site.basket.checkout')}}" class="w2 float-left">
                        the payment
                    </a>
                </div>
            @else
                <div class="cart-main-cart-submit cart-empty">
					<span class="text-info">Your cart is empty
					<img class="img-empty-basket" src="{{url('site_theme/assets/img/cart-empty.png')}}" alt="cart-empty">
					</span>
                </div>
            @endif
        </div>
    @endif
</div>
