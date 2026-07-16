@extends('site.layout.master')
@section('site.css')
    @include('site.users.partials.user-style-area')
@endsection
@section('content')

    <section class="page-section account-page">
        <div class="uk-container uk-containcer-center uk-margin-large-top uk-margin-large-bottom">
            <div class="uk-grid" uk-grid>
                @include('site.users.partials.menu')
                <div class="uk-width-3-4@m uk-background-muted uk-padding">
                    <h5>فهرست سفارشات شما</h5>
                    @if(isset($orders) && count($orders) > 0)
                        <div class="uk-overflow-auto">
                            <table class="cart-page-table orders-table uk-table uk-table-hover uk-table-divider">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('cms.order-tracking-code')</th>
                                    <th>@lang('cms.payment')</th>
                                    <th>@lang('cms.status')</th>
                                    <th>@lang('cms.date-order')</th>
                                    <th>کد مرسوله</th>
                                    <th>@lang('cms.details')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $itemOrder)
                                    <tr>
                                        <td>{{ $loop->iteration}}</td>
                                        <td class="order-status">
                                            {{ \Illuminate\Support\Str::limit($itemOrder->tracking_code,10) }}
                                        </td>
                                        <td class="order-total">
                                    <span class="currencySymbol">
                                        {{ \App\Utility\paymentMethods::whichPaymentMethod($itemOrder->payment_method_id)  }}
                                    </span>
                                        </td>
                                        <td>
                                            {{ \App\Utility\Status::getOrderStatus($itemOrder->status) }}
                                        </td>
                                        <td>{{ $itemOrder->created_at }}</td>
                                        <td>{{ isset($itemOrder->shipping_code) && !empty($itemOrder->shipping_code) ? $itemOrder->shipping_code : 'در انتظار کد مرسوله' }}</td>
                                        <td><a uk-toggle="target: #modal-id{{ $itemOrder->id }}"
                                               uk-icon="icon: more"></a>
                                            @if($itemOrder->status == \App\Utility\Status::UNPAID || $itemOrder->status == \App\Utility\Status::PENDING)
                                                <a class="uk-label-danger uk-padding-small"
                                                   uk-toggle="target: #modal-delete{{ $itemOrder->id }}"
                                                   uk-icon="icon: trash"></a>
                                        @endif
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="uk-alert uk-alert-warning">شما تا به حال خریدی انجام نداده اید!</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if(isset($orders) && count($orders) > 0)
        @foreach($orders as $itemOrder)
            <!-- This is the modal -->
            <div id="modal-id{{ $itemOrder->id }}" class="uk-modal-container" uk-modal>
                <div class="uk-modal-dialog uk-modal-body">
                    <button class="uk-modal-close-default" type="button" uk-close></button>

                    {{--order-client--}}
                    @if(isset($itemOrder->user_info))
                        @php
                            $detailsUser = \App\Utility\serializeAndUnSerialize::serializeAndUnSerializeInfoUser(null,null,$itemOrder->id);
                        @endphp

                        <p> @lang('cms.order-client') : </p>
                        <div class="uk-overflow-auto">
                            <table class="cart-page-table showorders-table uk-table">
                                <tr>
                                    <th>@lang('cms.name')</th>
                                    <th>@lang('cms.family')</th>
                                    <th>@lang('cms.email')</th>
                                    <th>@lang('cms.mobile')</th>
                                </tr>

                                <tr>
                                    <td><a target="_blank"
                                           href="{{route('profile.index')}}">{{ isset($detailsUser['userLogin']->name) && !empty($detailsUser['userLogin']->name) ? $detailsUser['userLogin']->name : null  }}</a>
                                    </td>
                                    <td><a target="_blank"
                                           href="{{route('profile.index')}}">{{isset($detailsUser['userLogin']->family) && !empty($detailsUser['userLogin']->family) ? $detailsUser['userLogin']->family : null}}</a>
                                    </td>
                                    <td>{{isset($detailsUser['userLogin']->email) && !empty($detailsUser['userLogin']->email) ? $detailsUser['userLogin']->email : null}}</td>
                                    <td>{{isset($detailsUser['userLogin']->mobile) && !empty($detailsUser['userLogin']->mobile) ? $detailsUser['userLogin']->mobile : null}}</td>
                                </tr>

                            </table>
                        </div>
                        <br><br><br><br>
                    @endif

                    {{--transferee--}}
                    @if(isset($itemOrder->user_info))
                        @php
                            $detailsUser = \App\Utility\serializeAndUnSerialize::serializeAndUnSerializeInfoUser(null,null,$itemOrder->id);
                        //dd($detailsUser['addressSession']);
                        @endphp
                        <p> @lang('cms.transferee') : </p>
                        <table class="cart-page-table">
                            <tr>
                                <th>@lang('cms.name-family')</th>
                                <th>@lang('cms.mobile')</th>
                                <th>@lang('cms.tell')</th>
                            </tr>

                            <tr>
                                <td>{{ isset($detailsUser['addressSession']->name) && !empty($detailsUser['addressSession']->name) ? $detailsUser['addressSession']->name : "--"  }}</td>
                                <td>{{isset($detailsUser['addressSession']->mobile) && !empty($detailsUser['addressSession']->mobile) ? $detailsUser['addressSession']->mobile : "--"}}</td>
                                <td>{{ isset($detailsUser['addressSession']->tell) && !empty($detailsUser['addressSession']->tell) ? $detailsUser['addressSession']->tell  : "--"}}</td>
                            </tr>

                        </table>
                        <hr>
                        <p> @lang('cms.address-1') : </p>
                        <p> {!! \App\Utility\getProvinceAndCity::getProvinceAndCity($detailsUser['addressSession']->province->id , $detailsUser['addressSession']->city_id)  !!} </p>
                        <p> {{$detailsUser['addressSession']->fullAddress ?? ""}} </p>
                    @endif

                    <hr>
                    <div class="uk-overflow-auto">
                        <table class="cart-page-table showorders-table uk-table">
                            <thead>
                            <tr>
                                <th class="product-remove">#</th>
                                <th class="product-thumbnail">@lang('cms.images')</th>
                                <th class="product-name">@lang('cms.product')</th>
                                <th class="product-subtotal">@lang('cms.count')</th>
                                <th class="product-subtotal">@lang('cms.price-per-unit')</th>
                                <th class="product-subtotal">@lang('cms.discount-price')</th>
                                <th class="product-subtotal">@lang('cms.total-price')(@lang('cms.tooman'))</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $totalPrice = 0;
                                $totalDiscount = 0;
                            @endphp

                            @foreach($itemOrder->orderItem as $orderItems)
                                @if(isset($orderItems->details) && !empty($orderItems->details))
                                    @php $unserializeOrderItems = unserialize($orderItems->details); @endphp
                                    <tr>
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center"><img
                                                    src="{{$unserializeOrderItems['item']->image}}"
                                                    width="300" height="300" alt="product"></td>
                                        <td class="text-center">
                                            {{ $unserializeOrderItems['item']->title }}
                                            <br>
                                            {{isset( $unserializeOrderItems['item']->AttributeValue) ?  "color :" . $unserializeOrderItems['item']->AttributeValue : null }}
                                            <br>
                                            {{ isset($unserializeOrderItems['item']->relatedVariationValue) ? "size".$unserializeOrderItems['item']->relatedVariationValue : null  }}
                                        </td>

                                        <td class="text-center"> {{$unserializeOrderItems['qty']}} </td>
                                        <td class="text-center">{{isset($orderItems->product->variations[0]) && !empty($orderItems->product->variations[0]) ? number_format($orderItems->product->variations[0]->price) : 'قیمت در دسترس نیست' }}</td>
                                        <td class="text-center">
                                            @if(!$orderItems->order->user->isColleague())
                                                @if(isset($orderItems->amount_discount) && $orderItems->amount_discount > 0 && !empty($orderItems->amount_discount) && !empty($orderItems->discount))
                                                    {!! number_format($orderItems->amount_discount) !!}
                                                    -( {!!   unserialize($orderItems->discount)->baseon == \App\Utility\DiscountType::cent ? '<b style=color:red>%</b>'.unserialize($orderItems->discount)->cent : number_format(unserialize($orderItems->discount)->cent).' تومان '  !!}
                                                    )

                                                @elseif(isset($orderItems->discount) && !empty($orderItems->discount) )
                                                    {{--                                                                        @dd($orderItems->discount)--}}
                                                    @if($orderItems->itemCount > unserialize($orderItems->discount)->count_buy)
                                                        ( {!!   unserialize($orderItems->discount)->baseon == \App\Utility\DiscountType::cent ? '<b style=color:red>%</b>'.unserialize($orderItems->discount)->cent : number_format(unserialize($orderItems->discount)->cent).' تومان '  !!}
                                                        )
                                                    @else
                                                        <p>بدون تخفیف</p>
                                                    @endif
                                                @else
                                                    <p>بدون تخفیف</p>
                                                @endif
                                            @else
                                                <p>{{ $orderItems->order->user->discount_percent }} <b
                                                            style=color:red>%</b></p>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($orderItems->amount_discount) && !empty($orderItems->amount_discount) && !$orderItems->order->user->isColleague())
                                                {!! number_format($orderItems->amount_discount*$unserializeOrderItems['qty']) !!}
                                            @else
                                                {!! number_format($orderItems->amount*$unserializeOrderItems['qty']) !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endif

                                @php
                                    $totalPrice += $unserializeOrderItems['price'];
                                   $totalDiscount += isset($orderItems->amount_discount) && !empty($orderItems->amount_discount) ? $unserializeOrderItems['item']->price - $orderItems->amount_discount : 0;
                                @endphp


                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(isset($itemOrder) && !empty($itemOrder->total_amount))
                        <?php
                        $userUnserialize = unserialize($itemOrder->user_info);
                        $getLevelForDiscountStoreBrand = $userUnserialize['userLogin']->level;
                        ?>
                        <?php $tax = \App\Utility\taxCalculate::getTax($itemOrder->total_amount, $totalPrice); ?>

                        {{--                                            @if($tax > 0)--}}
                        <div class="row"><br>
                            <div class="col-md-6">
                                @if($itemOrder->user->isColleague())
                                    <div class="last-activity">
                                        <p style="color: red">تخفیف :
                                            {{ unserialize($itemOrder->user_info)['userLogin']->discount_percent }} %
                                        </p>
                                    </div>
                                @endif

                                @if(isset($itemOrder->shippingCost) && $itemOrder->shippingCost > 0  && !empty($itemOrder->shippingCost))
                                    <div class="last-activity">
                                        <p>  @lang('cms.shipping-cost'):
                                            {{  number_format($itemOrder->shippingCost) . " " . \Illuminate\Support\Facades\Lang::get('cms.tooman') }}
                                            ‌
                                        </p>
                                    </div>

                                    <div class="clearfix"></div>
                                @endif

                                @if(!$orderItems->order->user->isColleague() && !empty($totalDiscount))
                                    <div class="last-activity">
                                        <p> تخفیف :
                                            {{ number_format(abs($totalDiscount))."تومان" }}
                                        </p>
                                    </div>

                                    <div class="clearfix"></div>
                                @endif

                                <div class="last-activity">
                                    @php
                                        $tax = \App\Utility\taxCalculate::getTax($itemOrder->total_amount , $totalPrice ,  $itemOrder->discountUserGeneral);
                                    @endphp
                                    <p>
                                        @if($tax == 0)
                                            قیمت بدون احتساب مالیات
                                        @else
                                            قیمت با احتساب
                                            مالیات {{ $tax." درصد"  }}
                                        @endif

                                        :
                                        {{-- shipping cost --}}
                                        @if($itemOrder->shippingCost > 0 && !is_null($itemOrder->shippingCost) )
                                            @if($itemOrder->user->isColleague())
                                                @php
                                                    $totalPrice = $itemOrder->total_discount;
                                                @endphp
                                            @else
                                                @php
                                                    $totalPrice = $itemOrder->total_discount + $itemOrder->shippingCost;
                                                @endphp
                                            @endif

                                            {!! number_format($totalPrice).' تومان' !!}
                                        @else
                                            {!! number_format($itemOrder->total_discount).' تومان' !!}
                                        @endif
                                    </p>


                                </div>

                            </div>
                        </div>

                    @endif

                    @if(isset($itemOrder) && !empty($itemOrder->total_discount) )
                        <div class="row">
                            <div class="col-md-6">
                                <div class="last-activity">
                                    <p>قیمت نهایی :
                                        @if($itemOrder->shippingCost > 0 && !is_null($itemOrder->shippingCost) )
                                            @if($itemOrder->user->isColleague())
                                                @php
                                                    $totalPrice = $itemOrder->total_discount;
                                                @endphp
                                            @else
                                                @php
                                                    $totalPrice = $itemOrder->total_discount + $itemOrder->shippingCost;
                                                @endphp
                                            @endif

                                            {!! number_format($totalPrice).' تومان' !!}
                                        @else
                                            {!! number_format($itemOrder->total_discount).' تومان' !!}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>


            @if($itemOrder->status == \App\Utility\Status::UNPAID || $itemOrder->status == \App\Utility\Status::PENDING)
                <!-- This is the modal -->
                <div id="modal-delete{{ $itemOrder->id }}" uk-modal>
                    <div class="uk-modal-dialog uk-modal-body">
                        <button class="uk-modal-close-default" type="button" uk-close></button>
                        <div class="last-activity">
                            <p>آیا از حذف این سفارش مطمئن هستید ؟</p>
                        </div>
                        <form action="{{ route('users.panel.order.delete',$itemOrder->id) }}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <br>
                            <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                   class="uk-button-danger uk-button">
                        </form>
                    </div>
                </div>
            @endif

        @endforeach
    @endif
@endsection
