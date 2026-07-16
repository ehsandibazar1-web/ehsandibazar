@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" href="{{ Url('site_theme') }}/css/checkout.css">
@endsection
@section('site-js')
    <script src="{{asset('site_theme/js/ajax.js')}}"></script>
@endsection
@section('title',$title)
@section('content')

    {{-- header cart --}}
    <div class="row">
        @php
            $reviewCheckout = "active";
            $number = 83;
        @endphp
        @include('site.checkout.partials.direction-cart',[ $reviewCheckout , $number ])
    </div>
    <form action="{{route('site.basket.finish')}}" method="post">
        <div class="content-page">
            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h1>@lang('cms.preview')</h1>
                    </div>

                    <input type="hidden" name="postType" value="{{ $postType }}">
                    <div class="card-body">

                        @include('generals.allErrors')
                        <div class="row">

                            <div class="col-sm-6 col-xs-12">
                                <div class="card card-review">
                                    <img class="user-information" src="{{url('site_theme/assets/img/user-info.png')}}"
                                         alt="user-information">
                                    <h2>@lang('cms.info-user')</h2>
                                    <table class="card-review-content" cellspacing="0">

                                        <tbody>
                                        <tr class="">
                                            <th> @lang('cms.name-lastName') :</th>
                                            <td>
                                                <span class="">{{ isset($user) ? $user->name . " " .$user->family : null  }}</span>
                                            </td>
                                        </tr>
                                        @if(isset($user) && isset($user->detail) && !empty($user->detail) && !empty($user->detail->store_name))
                                            <p>@lang('cms.name-of-store')
                                                : {{ isset($user) ? $user->detail->store_name  : null  }}</p>
                                        @endif
                                        <tr class="">
                                            <th>@lang('cms.email') :</th>
                                            <td>
                                                <span class="">{{ isset($user) && isset($user->email) ? $user->email : "--" }}</span>
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <th>@lang('cms.mobile'):</th>
                                            <td><strong><span
                                                            class="">{{ isset($user) && isset($user->mobile) ? $user->mobile : "--" }}</span></strong>
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <th>@lang('cms.tell'):</th>
                                            <td><strong><span
                                                            class="">{{ isset($user) && isset($user->tell) ? $user->tell : "--" }}</span></strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="card card-review">
                                    <img class="address-location"
                                         src="{{url('site_theme/assets/img/address-location.png')}}" alt="address">
                                    <h2>@lang('cms.info-address')</h2>
                                    <table class="card-review-content" cellspacing="0">
                                        <tbody>
                                        <tr class="">
                                            <th> @lang('cms.address') :</th>
                                            @if(isset($sessionAddress) && !empty($sessionAddress))
                                                <td> {{ $sessionAddress->province->name." ".$sessionAddress->city->name." ".$sessionAddress->fullAddress }} </td>

                                            @else
                                                @php
                                                    return route('site.index');
                                                @endphp
                                            @endif
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card shopping-cart">
                            @if(isset($basket) && !empty($basket) && isset($basket->items))
                                <div class="card shopping-cart ">

                                    <table class="table table-hover shopping-cart-wrap">
                                        <thead class="text-muted">
                                        <tr>
                                            <th class="text-center">@lang('cms.product')</th>
                                            <th class="text-center">@lang('cms.price-per-unit')</th>
                                            <th class="text-center">@lang('cms.number')</th>
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
                                                <th class="th-mob">@lang('cms.product')</th>
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
                                                                        @lang('cms.color') :‌
                                                                        {{ $findVariation->attributeTypeValue->value }}
                                                                    </div>
                                                                @endif

                                                                @if(isset($items['item']->relatedVariation) && !empty($items['item']->relatedVariation) )
                                                                    <br>
                                                                    <div class="pull-right">
                                                                        @lang('cms.size') :
                                                                        {{ isset($findVariation->relatedVariations) && !empty($findVariation->relatedVariations[0]->attribute_type_value_id) ? $findVariation->relatedVariations[0]->attributeTypeValue->value : null }}
                                                                    </div>
                                                                @endif

                                                                @if($findVariation->attributeTypeValue->attribute_type_id == \App\Utility\Variation::SIZE)
                                                                    <div class="pull-right">
                                                                        @lang('cms.size') :‌
                                                                        {{ $findVariation->attributeTypeValue->value }}
                                                                    </div>
                                                                @endif
                                                            </dl>
                                                        </figcaption>
                                                    </figure>
                                                </td>


                                                <th class="th-mob text-center">@lang('cms.price-per-unit')</th>
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
                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div> <!-- card.// -->

                                {{-- peyment --}}

                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="table-100">
                                            @include('site.checkout.partials.payment-methods')
                                            @include('site.checkout.partials.shipping-methods')
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="table-100">
                                            @include('site.checkout.partials.total-basket' , ['finish' => 1])
                                        </div>
                                    </div>
                                </div>

                            @else
                                <p> @lang('cms.empty-basket') </p>
                            @endif
                        </div> <!-- shopping cart// -->

                    </div><!--card body -->


                    <div class="card-footer text-left">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12 text-right">
                                <a href="{{route('site.basket.checkout.address')}}"
                                   class="btn btn-ctm-gray">@lang('cms.previous')</a>
                            </div>
                            <div class="col-sm-6 col-xs-12 text-left">


                                @csrf
                                <button class="btn btn-ctm-finally">@lang('cms.finish-step')</button>

                            </div>
                        </div>
                    </div>

                </div><!--card-->
            </div>
        </div>
    </form>

@endsection

