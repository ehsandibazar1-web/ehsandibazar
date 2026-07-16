@extends('site.layout.master')
@section('site.css')
    @include('site.users.partials.user-style-area')
@endsection

@section('content')
    <section class="page-section account-page ">
        <div class="uk-container uk-containcer-center uk-margin-large-top uk-margin-large-bottom">

            <div class="uk-grid " uk-grid>
                @include('site.users.partials.menu')
                <div class="uk-width-3-4@m">
                    <div class="account-orders">
                        <div class="uk-alert-success" uk-alert>
                            <a class="uk-alert-close" uk-close></a>
                            <p>{{ auth()->user()->name." ".auth()->user()->family }} عزیز خوش آمدی </p>
                        </div>
                    </div>
                    <div class="uk-child-width-1-2@s uk-grid-match" uk-grid>
                        <div>
                            <div class="uk-card uk-card-default uk-card-hover uk-card-body">
                                <h3 class="uk-card-title">تعداد سفارشات شما</h3>
                                <span class="uk-badge">{{ isset($countOrder) ? $countOrder : 0 }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="uk-card uk-card-default uk-card-hover uk-card-body">
                                <h3 class="uk-card-title">محصولات مورد علاقه شما </h3>
                                <span class="uk-badge">{{ isset($countFavorite) ? $countFavorite : 0 }}</span>
                            </div>
                        </div>
                    </div>
                    @if(isset($products) && !empty($products) && count($products) > 0)
                        <div class="uk-padding" uk-slider>

                            <div class="uk-position-relative">
                                <h4>جدید ترین محصولات</h4>
                                <div class="uk-slider-container uk-light">
                                    <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m">
                                        @foreach($products as $product)
                                            <li>
                                                @if(isset($product->image[0]))
                                                    <img src="{{ $product->image[0]->url }}"
                                                         alt="{{ $product->title }}">
                                                @endif
                                                <div class="uk-position-center uk-panel">
                                                    <h6 class="bg-dark">
                                                        <a href="{{ $product->path() }}" target="_blank">
                                                            {{ $product->title }}
                                                        </a>
                                                    </h6>
                                                </div>
                                            </li>

                                        @endforeach
                                    </ul>

                                </div>

                                <div class="uk-hidden@s uk-light">
                                    <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous
                                       uk-slider-item="previous"></a>
                                    <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next
                                       uk-slider-item="next"></a>
                                </div>

                                <div class="uk-visible@s">
                                    <a class="uk-position-center-left-out uk-position-small" href="#"
                                       uk-slidenav-previous uk-slider-item="previous"></a>
                                    <a class="uk-position-center-right-out uk-position-small" href="#" uk-slidenav-next
                                       uk-slider-item="next"></a>
                                </div>

                            </div>

                            <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>

                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>
@endsection
