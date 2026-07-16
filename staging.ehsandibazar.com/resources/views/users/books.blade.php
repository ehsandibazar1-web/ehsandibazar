@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
@endsection

@section('site.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf_viewer.min.css"
          integrity="sha512-srhhMuiYWWC5y1i9GDsrZwGM/+rZn0fsyBW/jYzbmSiwGs8I2iAX9ivxctNznU+WndPgbqtbYECLD8KYgEB3fg=="
          crossorigin="anonymous"/>
    <style>
        #the-canvas {
            border: 1px solid black;
            direction: ltr;
        }
    </style>
    @include('users.layouts.partials.styles')
@endsection
@section('site-js')
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
    </script>
@endsection
@section('content')
     <main class="profile-user-page default">
        <div class="container wrapper default">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-8 col-lg-9 order-2">
                    <div class="listing default">
                        <div class="listing-counter">{{ count($books) > 0 ? count($books).' محصول دیجیتال ' : ''  }}</div>
                        <div class="listing-header default">
                            <ul class="listing-sort nav nav-tabs justify-content-center" role="tablist" data-label="">
                                <li>
                                    <a class="active" data-toggle="tab" href="#related" role="tab"
                                       aria-expanded="false">محصولات من</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content default text-center">
                            <div class="tab-pane active" id="related" role="tabpanel" aria-expanded="true">
                                <div class="container no-padding-right">
                                    @if(isset($books) && count($books) > 0)
                                        <div class="col-12 text-center">
                                            <a href="#" class="form-account-link text-danger mb-4">
                                                برای مشاهده پی دی اف ، ویدیو یا ویس هر محصول ، روی مشاهده کلیک نمایید تا
                                                محتوایات آن
                                                نمایش داده شود
                                            </a>
                                        </div>
                                        <ul class="row listing-items">
                                            @foreach($books as $product)
                                                <li class="col-xl-3 col-lg-4 col-md-6 col-12 no-padding">
                                                    <div class="product-box">
                                                        <div class="product-seller-details product-seller-details-item-grid">
                                                        <span class="product-main-seller">
                                                            <span class="product-seller-details-label">
                                                            </span>
                                                        </span>
                                                            <span class="product-seller-details-badge-container"></span>
                                                        </div>
                                                        <a class="product-box-img" href="{{ route('users.panel.showBook',$product) }}">
                                                            @if(isset($product->image[0]) && !empty($product->image[0]))
                                                                <img src="{{ $product->image[0]->url }}" alt="{{ $product->title }}">                                                            @endif
                                                        </a>
                                                        <div class="product-box-content">
                                                            <div class="product-box-content-row">
                                                                <div class="product-box-title">
                                                                    <a href="{{ $product->path() }}">
                                                                        {{ $product->title }}                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="product-box-row product-box-row-price">
                                                                <div class="price">
                                                                    <div class="price-value">
                                                                        <div class="price-value-wrapper">
                                                                            @if($product->type=App\Utility\ProductType::PDF)
                                                                                    @if($product->variations && $product->variations[0]->price)
                                                                                        {{number_format($product->variations[0]->price,0)}} تومان
                                                                                     @else
                                                                                         رایگان
                                                                                     @endif
                                                                            @elseif($product->type=App\Utility\ProductType::SIMPLE)
                                                                                    @if($product->variations && $product->variations[0]->count)
                                                                                        @if($product->variations && $product->variations[0]->price)
                                                                                            {{number_format($product->variations[0]->price,0)}} تومان
                                                                                        @lese
                                                                                            رایگان
                                                                                        @endif
                                                                                         
                                                                                    @else
                                                                                        ناموجود
                                                                                    @endif
                                                                             @endif
                                                                            <span class="price-currency"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="col-12 text-center">
                                            <a href="#" class="form-account-link">
                                                شما تا بحال محصول دیجیتالی خریداری نکرده اید
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="pager default text-center">
                        </div>
                    </div>
                </div>
                @include('users.layouts.partials.aside-menu')
            </div>
        </div>
    </main>
@endsection