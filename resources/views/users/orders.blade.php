@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
@endsection
@section('content')
    <main class="profile-user-page default">
        <div class="container wrapper default">
            <div class="row">
                <div class="profile-page col-xl-9 col-lg-8 col-md-12 order-2">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-12">
                                <h1 class="title-tab-content">همه سفارش ها</h1>
                            </div>
                            <div class="content-section default">
                                @if(isset($orders) && count($orders) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-order">
                                            <thead class="thead-light">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">@lang('cms.order-tracking-code')</th>
                                                <th scope="col">@lang('cms.payment')</th>
                                                <th scope="col">@lang('cms.status')</th>
                                                <th scope="col">@lang('cms.date-order')</th>
                                                <th scope="col">کد مرسوله</th>
                                                <th scope="col">@lang('cms.details')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td>{{ $loop->iteration}}</td>
                                                    <td class="order-code">
                                                        {{ \Illuminate\Support\Str::limit($order->tracking_code,10) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Utility\paymentMethods::whichPaymentMethod($order->payment_method_id)  }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Utility\Status::getOrderStatus($order->status) }}
                                                    </td>
                                                    <td>{{ $order->created_at }}</td>
                                                    <td>{{ isset($order->shipping_code) && !empty($order->shipping_code) ? $order->shipping_code : 'در انتظار کد مرسوله' }}</td>
                                                    <td>
                                                        {{--                                                        <button type="button" class="btn btn-primary"--}}
                                                        {{--                                                                data-toggle="modal" data-target="#detail{{$order->id}}">--}}
                                                        {{--                                                            Open modal--}}
                                                        {{--                                                        </button>--}}
                                                        <i class="now-ui-icons arrows-1_minimal-left"
                                                           data-toggle="modal" data-target="#detail{{$order->id}}"></i>
                                                    </td>
                                                </tr>

                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @foreach($orders as $order)
                                        <div class="modal" id="detail{{ $order->id }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">جزییات سفارش</h4>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        {{--order-client--}}
                                                        @if(isset($order->user_info))
                                                            @php
                                                                $detailsUser = \App\Utility\serializeAndUnSerialize::serializeAndUnSerializeInfoUser(null,null,$order->id);
                                                            @endphp

                                                            <p> @lang('cms.order-client') : </p>
                                                            <div class="col-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-order">
                                                                        <tr>
                                                                            <th>@lang('cms.name')</th>
                                                                            <th>@lang('cms.family')</th>
                                                                            <th>@lang('cms.email')</th>
                                                                            <th>@lang('cms.mobile')</th>
                                                                        </tr>

                                                                        <tr>
                                                                            <td><a target="_blank"
                                                                                   href="#">{{ isset($detailsUser['userLogin']->name) && !empty($detailsUser['userLogin']->name) ? $detailsUser['userLogin']->name : null  }}</a>
                                                                            </td>
                                                                            <td><a target="_blank"
                                                                                   href="#">{{isset($detailsUser['userLogin']->family) && !empty($detailsUser['userLogin']->family) ? $detailsUser['userLogin']->family : null}}</a>
                                                                            </td>
                                                                            <td>{{isset($detailsUser['userLogin']->email) && !empty($detailsUser['userLogin']->email) ? $detailsUser['userLogin']->email : null}}</td>
                                                                            <td>{{isset($detailsUser['userLogin']->mobile) && !empty($detailsUser['userLogin']->mobile) ? $detailsUser['userLogin']->mobile : null}}</td>
                                                                        </tr>

                                                                    </table>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        {{--transferee--}}
                                                        @if(isset($order->user_info))
                                                            @php
                                                                $detailsUser = \App\Utility\serializeAndUnSerialize::serializeAndUnSerializeInfoUser(null,null,$order->id);
                                                            //dd($detailsUser['addressSession']);
                                                            @endphp
                                                            <p> @lang('cms.transferee') : </p>
                                                            <div class="col-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-order">
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
                                                                    {{-- گاردِ ایمنی: روی دیتای ناقص، $detailsUser می‌تواند false شود؛ isset از کرشِ array-offset-on-false جلوگیری می‌کند. --}}
                                                                    @if(isset($detailsUser['addressSession']->province->id))
                                                                        <p> @lang('cms.address-1') : </p>
                                                                        <p> {!! \App\Utility\getProvinceAndCity::getProvinceAndCity($detailsUser['addressSession']->province->id , $detailsUser['addressSession']->city_id)  !!} </p>
                                                                        <p> {{$detailsUser['addressSession']->fullAddress ?? ""}} </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <hr>
                                                        <div class="col-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-order">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="product-remove">#</th>
                                                                        <th class="product-thumbnail">@lang('cms.images')</th>
                                                                        <th class="product-name">@lang('cms.product')</th>
                                                                        <th class="product-subtotal">@lang('cms.count')</th>
                                                                        <th class="product-subtotal">@lang('cms.price-per-unit')</th>
                                                                        <th class="product-subtotal">@lang('cms.discount-price')</th>
                                                                        <th class="product-subtotal">@lang('cms.total-price')
                                                                            (@lang('cms.tooman'))
                                                                        </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @php
                                                                        $totalPrice = 0;
                                                                        $totalDiscount = 0;
                                                                    @endphp

                                                                    @foreach($order->orderItem as $orderItems)
                                                                        @if(isset($orderItems->details) && !empty($orderItems->details))
                                                                            @php $unserializeOrderItems = @unserialize($orderItems->details); @endphp
                                                                            @if(is_array($unserializeOrderItems) && isset($unserializeOrderItems['item']))
                                                                            <tr>
                                                                                <td class="text-center">{{$loop->iteration}}</td>
                                                                                <td class="text-center"><img
                                                                                            src="{{$unserializeOrderItems['item']->image}}"
                                                                                            width="300" height="300"
                                                                                            alt="product"></td>
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
                                                                                        @if(isset($orderItems->amount_discount) && $orderItems->amount_discount > 0 && !empty($orderItems->amount_discount) && !empty($orderItems->discount) && is_object(@unserialize($orderItems->discount)))
                                                                                            {!! number_format($orderItems->amount_discount) !!}
                                                                                            -( {!!   unserialize($orderItems->discount)->baseon == \App\Utility\DiscountType::cent ? '<b style=color:red>%</b>'.unserialize($orderItems->discount)->cent : number_format(unserialize($orderItems->discount)->cent).' تومان '  !!}
                                                                                            )

                                                                                        @elseif(isset($orderItems->discount) && !empty($orderItems->discount) && is_object(@unserialize($orderItems->discount)) )
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
                                                                                        <p>{{ $orderItems->order->user->discount_percent }}
                                                                                            <b
                                                                                                    style=color:red>%</b>
                                                                                        </p>
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
                                                                        @endif

                                                                        @php
                                                                            $totalPrice += (isset($unserializeOrderItems['price']) ? $unserializeOrderItems['price'] : 0);
                                                                           $totalDiscount += isset($orderItems->amount_discount) && !empty($orderItems->amount_discount) && isset($unserializeOrderItems['item']) ? $unserializeOrderItems['item']->price - $orderItems->amount_discount : 0;
                                                                        @endphp


                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        @if(isset($order) && !empty($order->total_amount))
                                                            <?php
                                                            $userUnserialize = @unserialize($order->user_info);
                                                            $getLevelForDiscountStoreBrand = (is_array($userUnserialize) && isset($userUnserialize['userLogin'])) ?  $userUnserialize['userLogin']->level : '';
                                                            ?>
                                                            <?php $tax = \App\Utility\taxCalculate::getTax($order->total_amount, $totalPrice); ?>

                                                            {{--                                            @if($tax > 0)--}}
                                                            <div class="row"><br>
                                                                <div class="col-md-6">
                                                                    @if($order->user->isColleague())
                                                                        <div class="last-activity">
                                                                            <p style="color: red">تخفیف :
                                                                                {{ (is_array($userUnserialize) && isset($userUnserialize['userLogin'])) ? $userUnserialize['userLogin']->discount_percent : 0 }}
                                                                                %
                                                                            </p>
                                                                        </div>
                                                                    @endif

                                                                    @if(isset($order->shippingCost) && $order->shippingCost > 0  && !empty($order->shippingCost))
                                                                        <div class="last-activity">
                                                                            <p>  @lang('cms.shipping-cost'):
                                                                                {{  number_format($order->shippingCost) . " " . \Illuminate\Support\Facades\Lang::get('cms.tooman') }}
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
                                                                            $tax = \App\Utility\taxCalculate::getTax($order->total_amount , $totalPrice ,  $order->discountUserGeneral);
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
                                                                            @if($order->shippingCost > 0 && !is_null($order->shippingCost) )
                                                                                @if($order->user->isColleague())
                                                                                    @php
                                                                                        $totalPrice = $order->total_discount;
                                                                                    @endphp
                                                                                @else
                                                                                    @php
                                                                                        $totalPrice = $order->total_discount + $order->shippingCost;
                                                                                    @endphp
                                                                                @endif

                                                                                {!! number_format($totalPrice).' تومان' !!}
                                                                            @else
                                                                                {!! number_format($order->total_discount).' تومان' !!}
                                                                            @endif
                                                                        </p>


                                                                    </div>

                                                                </div>
                                                            </div>

                                                        @endif

                                                        @if(isset($order) && !empty($order->total_discount) )
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="last-activity">
                                                                        <p>قیمت نهایی :
                                                                            @if($order->shippingCost > 0 && !is_null($order->shippingCost) )
                                                                                @if($order->user->isColleague())
                                                                                    @php
                                                                                        $totalPrice = $order->total_discount;
                                                                                    @endphp
                                                                                @else
                                                                                    @php
                                                                                        $totalPrice = $order->total_discount + $order->shippingCost;
                                                                                    @endphp
                                                                                @endif

                                                                                {!! number_format($totalPrice).' تومان' !!}
                                                                            @else
                                                                                {!! number_format($order->total_discount).' تومان' !!}
                                                                            @endif
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">بستن
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center">شما تا به حال خریدی انجام نداده اید!</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @include('users.layouts.partials.aside-menu')
            </div>
        </div>
    </main>
@endsection
