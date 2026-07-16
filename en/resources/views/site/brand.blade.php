@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/internal/style.css"/>
@endsection
@section('site-js')
    <script src="{{ url('') }}/site_theme/js/first.js"></script>
    <script src="{{ url('') }}/site_theme/js/javascript.js"></script>
@endsection
@section('content')
<div class="container-fluid wrapper default">
    <!-- Path lists -->
    <div class="container">
        <div class="row">
            <div class="container path-list">
                <ul>
                    <li><a href="{{ route('site.index') }}"> home </a></li>
                    <li><a href="#"> {{ $brand->title }} </a></li>

                </ul>
            </div>
        </div>
    </div>
    <!--Path lists End-->

    @if(isset($brand) && count($brand->image) > 0)
        <div class=" brand-slider">
            <div id="demo" class="carousel slide" data-ride="carousel">

                <!-- Indicators -->
                <ul class="carousel-indicators">
                    @foreach($brand->image as $key => $image)
                        @if($key != 0)
                            <li data-target="#demo"
                                data-slide-to="{{ $key }}" {{ $key == 1 ? 'class="active"' : null }}></li>
                        @endif
                    @endforeach
                </ul>

                <!-- The slideshow -->
                <div class="carousel-inner">
                    @foreach($brand->image as $key => $image)
                        @if($key != 0)
                            <div class="carousel-item {{ $key == 1 ? 'active' : null }}">
                                <img src="{{ url($image->url) }}" alt="{{ $brand->title }}">
                            </div>
                        @endif
                    @endforeach
                </div>

            @if(count($brand->image) > 2)
                <!-- Left and right controls -->
                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#demo" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                @endif

            </div>
        </div>
    @endif


    <div class="container">
       <div class="description-tag">
           <h2 class="header-description-tag"> brand description {{ $brand->title }} </h2>
           {!! $brand->description !!}
       </div>
       <br>

       <div class="col-md-12">
           <div class="search-product-results">
               <h5><span>product  {{ $brand->title }}</span></h5>
               <div class="row">
                   @if(isset($products))
                       @foreach($products as $product)
                           <div class="col-md-3">
                               <div class="result-item">
                                   <div class="result-offered-image">
                                       <a href="{{ $product->path() }}">
                                           <img src="{{ isset($product->image[0]) ? $product->image[0]->url : null }}">
                                       </a>
                                   </div>
                                   <div class="result-offered-title">
                                       <a href="{{ $product->path() }}">
                                           <h5>{{ $product->title }}</h5>
                                       </a>
                                   </div>
                                   <div class="result-offered-price">
                                       <ul>
                                           <li>
                                               @php
                                                   $allPrice =   \App\Utility\sortPrice::sortPrice($product);
                                                   echo \App\Utility\sortPrice::totalPrice($allPrice);
                                               @endphp
                                           </li>
                                       </ul>
                                   </div>
                               </div>
                           </div>
                       @endforeach
                   @endif

               </div>
               <div class="loadmore-result row">
                   <div>
                       {{ $products->render() }}
                   </div>
               </div>
           </div>
       </div>


       <div class="about-brand">
           <div class="shop-all-brand">
               <a href="{{ route('site.products') }}">See other products in the store</a>
           </div>
       </div>
   </div>
</div>   
@endsection
