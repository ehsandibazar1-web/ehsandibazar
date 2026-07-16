@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" href="{{ Url('site_theme') }}/css/checkout.css">
@endsection
@section('title',$title)
@section('content')

    {{-- header cart --}}
    <div class="row">
        @php
            $addressCheckout = "active";
            $number = 48;
        @endphp
        @include('site.checkout.partials.direction-cart',[ $addressCheckout , $number ])
    </div>
    <div class="content-page address_page">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h1>انتخاب آدرس</h1>
                </div>
                <div class="card-body">
                    <a title="@lang('cms.add-address')"
                       href="{{ route('users.panel.address') }}"
                       class="btn btn-edit-profile"><i class="fa fa-edit"></i></a>

                    @if(isset($user) && isset($user->address) && !empty($user->address))
                        @foreach($user->address as $key => $itemAddress)
                            @if($loop->first)
                                <div class="address">
                                    <label>
                                        @if(isset($sessionAddress) && !empty($sessionAddress))
                                            <input type='radio'
                                                   {{ $sessionAddress->id == $itemAddress->id ? "checked" : null  }} name="address"
                                                   data-attr="{{$itemAddress->id}}" id='checkbox-{{$itemAddress->id}}'>
                                        @endif

                                        {{--<input type="radio" name="address1" value="address1" checked>--}}
                                        <div class="buy-address">
                                            <div class="buy-name">{{ $itemAddress->name }}</div>
                                            <div class="buy-add"> {{ $itemAddress->province->name." ".$itemAddress->city->name." ".$itemAddress->fullAddress }}</div>
                                            <hr/>
                                            <div class="buy-phone"><i
                                                    class="fas fa-phone"></i><span>@lang('cms.mobile') : {{ $itemAddress->mobile  }}</span>
                                            </div>
                                            <div class="buy-zone"><i
                                                    class="far fa-envelope"></i><span> @lang('cms.postal-code') : {{ $itemAddress->postal_code  }}</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @else
                                <div class="address">
                                    <label>
                                        @if(isset($sessionAddress) && !empty($sessionAddress))
                                            <input type='radio'
                                                   {{ $sessionAddress->id == $itemAddress->id ? "checked" : null  }} name="address"
                                                   data-attr="{{$itemAddress->id}}" id='checkbox-{{$itemAddress->id}}'>
                                        @else
                                            <input type='radio' checked name="address"
                                                   data-attr="{{$itemAddress->id}}" id='checkbox-{{$itemAddress->id}}'>
                                        @endif

                                        {{--<input type="radio" name="address1" value="address1" checked>--}}
                                        <div class="buy-address">
                                            <div class="buy-name">{{ $itemAddress->name }}</div>
                                            <div class="buy-add"> {{ $itemAddress->province->name." ".$itemAddress->city->name." ".$itemAddress->fullAddress }}</div>
                                            <hr/>
                                            <div class="buy-phone"><i
                                                    class="fas fa-phone"></i><span>@lang('cms.mobile') : {{ $itemAddress->mobile  }}</span>
                                            </div>
                                            <div class="buy-zone"><i
                                                    class="far fa-envelope"></i><span> @lang('cms.postal-code') : {{ $itemAddress->postal_code  }}</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    @endif

                </div><!-- card body -->
                <div class="card-footer text-left">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12 text-right">
                            <a href="{{route('site.basket.checkout')}}" class="btn btn-ctm">@lang('cms.previous')</a>
                        </div>
                        <div class="col-sm-6 col-xs-12 text-left">
                            <a href="{{route('site.basket.checkout.review')}}" class="btn btn-ctm">@lang('cms.next')</a>
                        </div>
                    </div>
                </div>
            </div><!--/card-->
        </div>
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
                                showCancelButton: true,
                                closeOnConfirm: false,
                                showLoaderOnConfirm: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Yes, delete it!",
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

