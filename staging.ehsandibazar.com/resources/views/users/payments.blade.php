@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
@endsection
@section('content')
    <section class="page-section account-page">
        <div class="uk-container uk-containcer-center uk-margin-large-top uk-margin-large-bottom">
            <div class="uk-grid" uk-grid>
                @include('site.users.partials.menu')
                <div class="uk-width-3-4@m">
                    <h5>Latest transactions</h5>
                    @if(isset($payment) && count($payment) > 0)
                        <table class="cart-page-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('cms.payment-type')</th>
                                <th>@lang('cms.price')</th>
                                <th>@lang('cms.order-tracking-code')</th>
                                <th>@lang('cms.status')</th>
                                <th>@lang('cms.date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payment as $itemOrder)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td class="order-status">
                                        {{ \App\Utility\PaymentStatus::getPaymentStatus($itemOrder->payment_type,2) }}
                                    </td>
                                    <td>
                                        @if($itemOrder->payment_type == \App\Utility\PaymentStatus::PRODUCTORDER)
                                            {{ number_format($itemOrder->paymentable->total_amount)." ".\Illuminate\Support\Facades\Lang::get('cms.tooman') }}
                                        @else
                                            {{ number_format($itemOrder->price)." ".\Illuminate\Support\Facades\Lang::get('cms.tooman') }}
                                        @endif
                                    </td>
                                    <td class="order-total">
                                    <span class="currencySymbol">
                                        @if($itemOrder->payment_type == \App\Utility\PaymentStatus::PRODUCTORDER)
                                            {{ $itemOrder->paymentable->tracking_code }}
                                        @else
                                            {{ $itemOrder->tracking_code }}
                                        @endif
                                    </span>
                                    </td>
                                    <td>{{ \App\Utility\PaymentStatus::getStatus($itemOrder->payment,2) }}</td>
                                    <td>{{ $itemOrder->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="uk-alert uk-alert-warning">No transaction found...</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
