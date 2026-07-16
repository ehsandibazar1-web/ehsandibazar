@extends('site.layout.master')
@section('site.css')
    @include('site.users.partials.user-style-area')
@endsection
@section('content')
    <section class="page-section account-page">
        <div class="uk-container uk-containcer-center uk-margin-large-top uk-margin-large-bottom">
            <div class="uk-grid" uk-grid>
                @include('site.users.partials.menu')
                <div class="uk-width-3-4@m">
                    <div class="account-orders">
                        @if(isset($favorites) && count($favorites) > 0)
                            <div class="uk-grid-small uk-child-width-1-3@m" uk-grid>
                                @foreach($favorites as $product)
                                    <div class="home-product-box">
                                        @if(isset($product->favoriteable->image[0]) && !empty($product->favoriteable->image[0]))
                                            <a href="{{ $product->favoriteable->path() }}" target="_blank">
                                                <img src="{{ $product->favoriteable->image[0]->url }}"
                                                     alt="{{ $product->favoriteable->title }}">
                                            </a>
                                        @endif
                                        <h2 class="title"><a target="_blank"
                                                             href="{{ $product->favoriteable->path() }}">{{ $product->favoriteable->title }}</a>
                                        </h2>
                                        <p class="price-box-inner">
                                            @php
                                                $allPrice =   \App\Utility\sortPrice::sortPrice($product->favoriteable);
                                                echo \App\Utility\sortPrice::totalPrice($allPrice);
                                            @endphp
                                        </p>
                                        <div class="meta-bot"><a class="addtocart" target="_blank"
                                                                 href="{{ $product->favoriteable->path() }}"
                                                                 uk-icon="icon: cart">buy</a>
                                            <a class="pr-like"
                                               uk-toggle="target: #modal-id{{ $product->id }}"
                                               uk-icon="icon: trash" title="delete"></a></div>
                                    </div>

                                    {{--modal delete From favorite--}}
                                    <div id="modal-id{{ $product->id }}" uk-modal>
                                        <div class="uk-modal-dialog uk-modal-body">
                                            <button class="uk-modal-close-default" type="button" uk-close></button>
                                            <div class="last-activity">
                                                <p>Do you want to remove this product from your interest?</p>
                                            </div>
                                            <form action="{{ route('users.panel.favorite.delete',$product->id) }}" method="POST">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <br>
                                                <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                                       class="uk-button-danger uk-button">
                                            </form>
                                        </div>
                                    </div>


                                @endforeach
                            </div>
                            {{ $favorites->render() }}
                        @else
                            <div class="uk-alert uk-alert-warning">Your wishlist is empty ...</div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
