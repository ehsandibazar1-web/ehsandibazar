@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
@endsection
@section('content')
    <main class="profile-user-page default">
        <div class="container wrapper default">
            <div class="row">
                <div class="profile-page col-xl-9 col-lg-8 col-md-12 order-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="col-12">
                                <h1 class="title-tab-content">Addresses</h1>
                            </div>
                            <div class="content-section default">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 class="title-tab-content">Manage addresses</h1>
                                    </div>
                                </div>
                                @if(isset($address) && !empty($address))
                                    <div class="shopping-page">
                                        <main class="cart-page default">
                                            <div class="cart-page-content col-xl-12 col-lg-12 col-md-12 order-1">
                                                <div class="page-content default">
                                                    @foreach($address as $key => $itemAddress)
                                                        <div class="address-section">
                                                            <label class="checkout-contact" style="width: 100%">
                                                                <div class="checkout-contact-content">
                                                                    <ul class="checkout-contact-items">
                                                                        <li class="checkout-contact-item">
                                                                            Receiver:
                                                                            <span class="full-name">{{ $itemAddress->name }}</span>
                                                                            {{--                                                            @if(\Illuminate\Support\Facades\Auth::user()->isCustomer())--}}
                                                                            {{--                                                                <a class="checkout-contact-btn-edit">اصلاح این آدرس</a>--}}
                                                                            {{--                                                            @endif--}}
                                                                        </li>
                                                                        <li class="checkout-contact-item">
                                                                            <div class="checkout-contact-item checkout-contact-item-mobile">
                                                                                Phone number:
                                                                                <span class="mobile-phone">{{ $itemAddress->mobile  }}</span>
                                                                            </div>
                                                                            <div class="checkout-contact-item-message">
                                                                                Postal code:
                                                                                <span class="post-code"> {{ $itemAddress->postal_code  }}</span>
                                                                            </div>
                                                                            <br>
                                                                            State
                                                                            <span class="state">{{ $itemAddress->province->name }}</span>
                                                                            ، city
                                                                            <span class="city">{{ $itemAddress->city->name }}</span>
                                                                            ،
                                                                            <span class="address-part">{{ $itemAddress->fullAddress }}</span>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="checkout-contact-badge">
                                                                        <i class="now-ui-icons ui-1_check"></i>
                                                                    </div>
                                                                </div>
                                                                <a href="{{ route('users.delete.address',$itemAddress->id) }}" class="checkout-contact-location remove-address">delete</a>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </main>
                                    </div>
                                @endif
                                <hr class="mb-5">
                                @include('generals.allErrors')
                                <form class="form-account" action="{{ route('users.panel.storeAddress') }}"
                                      method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">State</div>
                                            <div class="form-account-row">
                                                <select name="province_id" class="input-field text-right province"
                                                        id="form-stacked-select">
                                                    <option>select State...</option>
                                                    @foreach($provinces as $province)
                                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6" id="result-ajax">
                                            <div class="form-account-title">city</div>
                                            <div class="form-account-row">
                                                <select name="city_id" class="input-field text-right"
                                                        id="form-stacked-select city">
                                                    <option value="">select city</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">Supplementary address</div>
                                            <div class="form-account-row">
                                                <input class="input-field text-right" type="text" placeholder="address"
                                                       name="fullAddress" onkeypress="text(this)" onchange="text(this)"
                                                       keyup="text(this)" keydown="text(this)" value=""></div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">name</div>
                                            <div class="form-account-row">
                                                <input class="input-field text-right" type="text" name="name" value=""
                                                       onkeypress="text(this)" onchange="text(this)" keyup="text(this)"
                                                       keydown="text(this)" placeholder="name">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title"> mobile</div>
                                            <div class="form-account-row">
                                                <input class="input-field text-right" type="number" name="mobile"
                                                       value="" placeholder="تلفن همراه یا موبایل"></div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">tell</div>
                                            <div class="form-account-row">
                                                <input class="input-field text-right" type="number" name="tell" value=""
                                                       placeholder="tell">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-account-title">postal code</div>
                                            <div class="form-account-row">
                                                <input class="input-field text-right" type="text" name="postal_code"
                                                       value="" placeholder="Postal code of home or work"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button class="btn btn-default btn-lg">save</button>
                                        <a href="{{ route('users.dashboard.index') }}" class="btn btn-default btn-lg">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @include('users.layouts.partials.aside-menu')
            </div>
        </div>
    </main>
@endsection
@section('site-js')
    <script>
        $('.province').change(function (e) {
            var province_id = $(this).val();
            e.preventDefault();
            /* start ajax */
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: "post",
                url: "{{route('users.panel.profile.ajaxCity')}}",

                data: {isRequestIDChange: province_id, _token: CSRF_TOKEN},
                success: function (data) {
                    $('#result-ajax').html(data.html);
                },
                error: function (error) {
                    //alert(error);
                    alert("Please try again in a few moments")
                }
            });
            /* end ajax */
        });
    </script>
@endsection
