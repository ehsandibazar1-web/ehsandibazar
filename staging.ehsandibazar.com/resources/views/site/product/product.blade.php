@extends('site.layout.master')

@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_themes/css/internal/style.css"/>
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_themes/css/custom-site.css"/>
    <link href="{{ url('') }}/site_themes/css/jquery.fancybox.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf_viewer.min.css"
          integrity="sha512-srhhMuiYWWC5y1i9GDsrZwGM/+rZn0fsyBW/jYzbmSiwGs8I2iAX9ivxctNznU+WndPgbqtbYECLD8KYgEB3fg=="
          crossorigin="anonymous"/>
    <style>


        #the-canvas {
            border: 1px solid black;
            direction: ltr;
        }

        input[type=number] {
            height: 30px;
        }

        input[type=number]:hover::-webkit-inner-spin-button {
            width: 14px;
            height: 30px;
        }
    </style>
    
  
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());

        function showImage(image) {
            var img = $('<img />', {
                src: image,
                'class': 'fullImage'
            });
            $('.show-full-image').html(img).show();
        }
    </script>
    <script type="application/ld+json">
{
"@context": "http://schema.org/",
  "@type": "Product",
  "name": "{{ $product->title }}",
  "image": "{{ Url($product->image[0]->url) }}",
  "description": "{{ strip_tags(\Illuminate\Support\Str::limit($product->description,240)) }}",
"brand": {
  "@type": "Thing",
  "name": "{{ $product->brand->title }}"
  }
}

</script>

@endsection
@section('site-js-header')



@endsection

@section('content')

<div class="container-fluid body-inner">
    <!-- Path lists -->
    <div class="container">
        <div class="row">
            <div class="col-12 path-list p-0">
                <ul class="p-0">
                    <li><a href="{{ route('site.index') }}"> خانه </a></li>
                    <li><a href="{{ $product->categories[0]->path("category") }}">{{ $product->categories[0]->title }}</a>
                    </li>
                    <li><a href="{{ $product->path() }}">{{ $product->title }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--Path lists End-->

    <!--Product Box-->
    <div class="container product-top">
        <div class="row c-product">
            <div class="col-md-5 col-12">
               
                <div class="row ms-0 me-0">
                   <div class="col-md-1 col-12 ps-0 textcenter">
                       <div class="btn-groups">
                   <li>
                        <button id="SocialShare" class="icon-share"  data-bs-toggle="modal" data-bs-target="#shareModal">
                            <i class="fal fa-share-alt"></i>
                    </button>
                   </li>
                   <li>
                       <a href="javascript:void(0)" onclick="favorite('{{$product->id}}')"
                           class="det-favo">
                            <i class="fal fa-heart"></i>
                                
                        </a>
                   </li>
                </div>
                   </div>
                   <div class="col-md-11 col-12 pe-0">
                        @if((auth()->check() && !auth()->user()->isColleague()) || !auth()->check())
                    @if(\App\Utility\DiscountType::hasDiscount($product) == true)
                        <div class="off-pro"><span>{{ \App\Utility\DiscountType::showCentDiscount($product) }}</span>
                        </div>
                    @endif
                @endif
                           @if(isset($product->image) && !empty($product->image))

                    <div id="big" class="owl-carousel owl-theme">
                        @foreach($product->image as $image)
                            <div class="item" title="">
                                <a data-fancybox="mygallery" class="thumbnail" big_image="{{ $image->url }}"
                                   href="{{ $image->url }}" title="{{ $product->title }}">

                                    <img src="{{ $image->url }}" title="{{ $product->title }}" alt="{{$product->title}}"
                                         width="450" height="350"/>
                                </a>

                            </div>
                        @endforeach
                    </div>
                    <div id="thumbs" class="owl-carousel owl-theme mt-4">
                        @foreach($product->image as $image)
                        
                            <div class="item" title="">
                                <a data-fancybox="mygallery" class="thumbnail" big_image="{{ $image->url }}"
                                   href="{{ $image->url }}" title="{{ $product->title }}">
                                    <img src="{{ $image->url }}" title="{{ $product->title }}" alt="{{$product->title}}"
                                         width="60" height="60"/>
                                </a>

                            </div>
                        @endforeach

                    </div>



                @endif
                   </div>
                  </div>     
                
            
            </div>
            <div class="col-md-7 col-12">
                <div class="row row-pro-name">
                    <div class="col-12 p-0">
                        <h1 class="c-product__title">
                            {{ $product->title }}
                        </h1>

                    </div>
                    <div class="col-12 p-0 mt-2">
                        <ul class="link-view">
{{--                            <li class="rate-pro">--}}
{{--                                @component('components.show-rate',compact('product'))--}}
{{--                                @endcomponent--}}
{{--                            </li>--}}
                            <li><span class="v-link">{{ $product->viewCount }}</span> مشاهده</li>
                            <li><span class="gap"> / </span><span class="v-link">{{ $product->commentCount }}</span>
                                دیدگاه
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-7 col-12 ps-md-0">
                     <div class="c-product__directory d-block">
                           
                                <!--<div class="row brand">-->
                                <!--    <div class="col-2 p-0">نام برند</div>-->
                                <!--    <div class="col-10">-->
                                <!--        <a href="{{ $product->brand->path() }}">{{ $product->brand->title }}</a>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <div class="d-block cat-brand">
                                    <span class="key">دسته بندی:</span>
                                    <span class="value">
                                        <a class="btn-link-spoiler"
                                           href="{{ $product->categories[0]->path("category") }}">

                                            {{ $product->categories[0]->title }}</a>
                                    </span>
                                </div>

                        </div>
                      <div class="row">
                    <div class="col-12 p-0">
                        <div class="product-select-size">
                            <p>
                            <span class="size-choose">
                            <div id="variety"></div>
                            </span>
                            <a href="" class="float-right"></a>
                            </p>

                            <div class="clear"></div>

                        </div>
                    </div>
                </div>
                      <div class="row">
                    <div class="col-12 p-">
                        <div class="product-offer" id="div-description">
                            <div class="p-description" id="result-description"></div>
                        </div>
                    </div>
                </div>   
                    </div>
                    <div class="col-md-5 col-12   text-center">
                        <div class="box-price">
                            <div class="product-price">
                            <li>
                                <?php $count = 0 ?>
                                @foreach($product->variations->toArray() as $itemVariationCount)
                                    <?php $count += $itemVariationCount['count']  ?>
                                @endforeach

                                <span class="cost-pro"
                                      id="ajax-price">{{ count($product->variations->toArray())  <= 0 || $count <= 0  ? 'ناموجود' : null}}</span>
                       
                                <span class=""
                                      id="ajax-priceDiscount"> {{ count($product->variations->toArray())  <= 0 || $count <= 0  ? '' : null}}</span>
                                      
                            </li>

                            @if((auth()->check() && !auth()->user()->isColleague()) || !auth()->check())
                                @if(\App\Utility\DiscountType::hasDiscount($product) == true)
                                    <div class="offer-product">
                                        <b>{{ \App\Utility\DiscountType::showCentDiscount($product) }} تخفیف</b></div>
                                @endif
                            @endif

                        </div>
                             <div class="row mt-3">
                    <div class="col-12 p-0">

                        <div class="product-box-buy">


                            <div class="row mt-3 row-align">
                                @if($count > 0)
                                    @if(isset($deactiveBasket) && !empty($deactiveBasket) && $deactiveBasket->status == 1)
                                        <div class="col-12 col-md-12 pl-0">
                                            <div class="product-add-to-cart">
                                                <a class="btn-site add-basket btn btn-success">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    {{ $deactiveBasket->name }}
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-12 col-md-12 pl-0 mt-3">
                                            <div class="product-add-to-cart">
                                                <a class="btn-site  btn btn-danger">
                                                    {{ $deactiveBasket->code }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                        </div>
                    </div>
                   
                </div>
               
              
               


                <div class="product-features">
                    @if(isset($product->tags))
                        <div class="site-blog-post__box__body__cat">
                            <ul>
                                @foreach($product->tags as $tag)
                                    <li class="badge badge-info">
                                        <a href="{{ $tag->path() }}">{{ $tag->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="product-powered">
                        <p>

                            {{-- if is admin / admin first --}}
                            @php
                                $sortVariation = $product->variations;
                                $sortVariation = collect($sortVariation)->sortBy('price');
                                //$sortVariation  = $sortVariation->groupBy('user_id');

                                $array = [];
                                $arrayUniqUser = [];
                            @endphp

                            @foreach($sortVariation as  $itemVariation)
                                @if(in_array($itemVariation->user->level,\App\Utility\Level::levelAdmins()) && $itemVariation->count > 0)
                                    <?php $array [0] = 0;?>
                                @endif
                            @endforeach


                            <?php  $i = 1 ?>
                            @foreach($sortVariation as $key => $itemVariation)
                                @if($itemVariation->count > 0)

                                    @if(!in_array($itemVariation->user_id,$arrayUniqUser))
                                        @if(isset($array) && !empty($array) && count($array) >= 0)
                                            @if($itemVariation->status == \App\Utility\Status::active && $itemVariation->count > 0)
                                                {{-- when was admin --}}
                                                {{-- todo store name --}}
                                                @if(in_array($itemVariation->user->level,\App\Utility\Level::levelAdmins()) && $itemVariation->count > 0)
                                                    <span class="sellers active"
                                                          data-id="{{$itemVariation->user_id}}"></span>
                                                @else
                                                    <span class="sellers"
                                                          data-id="{{$itemVariation->user_id}}"></span>
                                                @endif

                                            @endif
                                        @else

                                            {{-- sort price and select price --}}
                                            {{-- todo store name --}}
                                            @if($i == 1 )
                                                <span class="sellers active"
                                                      data-id="{{$itemVariation->user_id}}">{{$itemVariation->user->name}}</span>
                                            @else
                                                <span class="sellers"
                                                      data-id="{{$itemVariation->user_id}}">{{$itemVariation->user->name}}</span>
                                            @endif
                                            <?php $i++ ?>
                                        @endif
                                        {{--@endif--}}
                                    @endif
                                    <?php $arrayUniqUser [] = $itemVariation->user_id ?>
                                @endif
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        <!--<div class="col-md-4 col-12">
                        <div class="detail">
                            <?php
        $collect = collect($product->categories[0]->attributes);
        $attributeGroup = $collect->groupBy('attribute_group_id');
        $arrayItemAttributeValue = [];
        $i = 0;
        ?>
        @foreach($attributeGroup as $idAttributeGroup => $valueAttributeGroup)
            <ul class="product-detail">
@foreach($valueAttributeGroup as $keyAttr => $attribute)
                @if(in_array($attribute->id , $productAttribute) && $keyAttr <= 8)
                    <li>
                        <span>{{$attribute->name}} :</span>
                                            <span> @foreach($product->attributevalues as  $itemAttributeValue)

                        @if(in_array($itemAttributeValue->attribute_id,$arrayItemAttributeValue))
                            @if($attribute->id == $itemAttributeValue->attribute_id )
                                <?=   $itemAttributeValue->value . "</br>" ?>
                            @endif
                        @else
                            @if($attribute->id == $itemAttributeValue->attribute_id )
                                {{$itemAttributeValue->value}}
                            @endif
                        @endif

                        <?php  $arrayItemAttributeValue[] = $itemAttributeValue->attribute_id ?>
                    @endforeach</span>
                                        </li>

                                    @endif

            @endforeach

                    </ul>
@endforeach


                </div>
            </div>-->
        </div>


    </div>

    <div class="container product-des-qa p-0">
        <div class="row mt-4">
            <div class="col-12 col-md-12 p-0">

                <div class="product-desc-header">
                    <ul class="p-0">
                        <li id="prd" class="active">
                            <a href="javascript:void(0)">
                                <i class="fas fa-list-alt"></i>
                                توضیحات محصول
                                
                                </a>
                        </li>
                        <li id="prh"><a href="javascript:void(0)">
                             <i class="fas fa-clipboard-list"></i>
                            جزییات
                       
                        </a></li>
                        <li id="prr"><a href="javascript:void(0)">
                             <i class="fas fa-comment-alt"></i>
                            نظرات
                       
                        </a></li>
                        <li id="prt"><a href="javascript:void(0)">پیش نمایش محصول</a></li>

                    </ul>
                </div>
                <div class="product-desc">
                    <div id="prd-t" class="product-desc-de">
                        <div class="product-long-description">
                            <div class="product-desc-de__sizeable">
                                {!! $product->description !!}
                            </div>
                        </div>

                    </div>
                    <div id="prh-t" class="product-desc-de">
                        <div class="reviews-qa-tab">
                            @include('site.product.partials.details',compact('product'))
                        </div>

                    </div>
                    <div id="prr-t" class="product-desc-de">
                        <div class="reviews-qa-tab">
                            <div class="qa-review-header">
                                <ul>

                                    <li>
                                        <a href="javascript:void(0)" class="active" id="question-btn">دیدگاه</a>
                                    </li>
                                </ul>
                            </div>
                            <div id="question-tabBody" class=" tabss-body q-a-tab-body">
                                @include('site.product.partials.comment',compact('product'))
                            </div>
                        </div>
                    </div>

                    <div id="prt-t" class="product-desc-de">
                        <div class="product-long-description">
                            <div class="product-desc-de__sizeable">
                                @if(isset($product->catalog[0]) && !empty($product->catalog[0]) && isset($product->type) && $product->type == \App\Utility\ProductType::PDF)
                                    <div class="row">
                                        @if(isset($product->catalog[0]) && !empty($product->catalog[0]))
                                           <!--@if($product->catalog[0]->url && substr($product->catalog[0]->url,0,4) == 'http')-->
        
                                           <!--     <object width="400px" height="400px" data="{{$product->catalog[0]->url}}" style="with:100%"></object>-->
                                           <!-- @else-->
                                           <!--     <object width="400px" height="400px" data="{{asset('public'. $product->catalog[0]->url)}}" style="with:100%"></object>       -->
                                           <!-- @endif-->
                                            <div>
                                                <button id="prev" class="btn btn-secondary">قبلی</button>
                                                <button id="next" class="btn btn-secondary">بعدی</button>
                                                <button id="enlarge" class="btn btn-secondary">+</button>
                                                <button id="letting" class="btn btn-secondary">-</button>
                                                <input type="number" id="desiredPage"
                                                       style="border: 1px solid;width: 10%;text-align: center"
                                                       onchange="renderPage($(this).val())">

                                                &nbsp; &nbsp;
                                                <span>صفحه : <span id="page_num"></span> / <span id="page_count"></span></span>
                                            </div>

                                            <canvas id="the-canvas"></canvas>
                                        @else
                                            <div class="uk-alert uk-alert-warning">متاسفانه پیش نمایش در دسترس
                                                نمیباشد
                                            </div>
                                        @endif
                                    </div>
                                @elseif(isset($product->video[0]) && !empty($product->video[0]))
{{--                                    <img src="{{ asset('site_themes/loading.gif') }}" alt="loading" id="loading-image">--}}
                                    <div>
                                              
                                        @if($product->video[0]->url && substr($product->video[0]->url,0,8) == '/storage')
                                
                                            <video width="100%" height="240" controls>
                                              <source src="{{asset('public' . $product->video[0]->url)}}" type="video/mp4" width="100%">
                                            </video>
                                        @else
                                            {!! $product->video[0]->url !!}
                                        @endif
                                    </div>
                                @else
                                    @if(isset($product->type) && $product->type == \App\Utility\ProductType::VIDEO)
                                
                                        <div class="row">
                                            <div class="col-12">
                                                @if(isset($product->video[0]) && !empty($product->video[0]))
                                                    {!! $product->video[0]->url !!}
                                                @else
                                                    <div class="uk-alert uk-alert-warning">متاسفانه پیش نمایش در دسترس
                                                        نمیباشد
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif(isset($product->type) && $product->type == \App\Utility\ProductType::VOICE)
                                        <div class="row">
                                            <div class="col-12">
                                                @if(isset($product->video[0]) && !empty($product->video[0]))
                                                   {!! $product->video[0]->url !!}
                                                @else
                                                    <div class="uk-alert uk-alert-warning">متاسفانه پیش نمایش در دسترس
                                                        نمیباشد
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif(isset($product->type) && $product->type == \App\Utility\ProductType::PDF)
                                        <div class="row">
                                            @if(isset($product->catalog[0]) && !empty($product->catalog[0]))
                                                <div>
                                                    <button id="prev" class="btn btn-secondary">قبلی</button>
                                                    <button id="next" class="btn btn-secondary">بعدی</button>
                                                    &nbsp; &nbsp;
                                                    <span>صفحه : <span id="page_num"></span> / <span
                                                                id="page_count"></span></span>
                                                </div>

                                                <canvas id="the-canvas"></canvas>
                                            @else
                                                <div class="uk-alert uk-alert-warning">متاسفانه پیش نمایش در دسترس
                                                    نمیباشد
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="uk-alert uk-alert-warning">متاسفانه پیش نمایش در دسترس نمیباشد!
                                        </div>
                                    @endif

                                @endif


                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($similarProducts) && count($similarProducts) > 0)
                    <h4>محصولات مرتبط :</h4>
                    <div class="product-similar">
                        <div class="product-similar-slider">
                            <div class="MultiCarousel gcarousel owl-carousel owl-theme">


                                @foreach($similarProducts as $item)
                                    <div class="item product-thumb">
                                        <div class="result-item">

                                            <div class="result-offered-image">
                                                <a href="{{ $item->product->path() }}" target="_blank">
                                                    <img
                                                            src="{{ isset($item->product->image[0]) ? url($item->product->image[0]->url) : null }}"
                                                            width="300" height="300">
                                                </a>
                                            </div>
                                            <div class="result-offered-title">
                                                <a href="{{ $item->product->path() }}" target="_blank">
                                                    <h5>{{ $item->product->title }}</h5>
                                                </a>
                                            </div>
                                            <div class="result-offered-price row">

                                                @php
                                                    $allPrice =   \App\Utility\sortPrice::sortPrice($item->product);
                                                    echo \App\Utility\sortPrice::totalPrice($allPrice);
                                                @endphp

                                            </div>

                                        </div>
                                        <div class="button-group">
                                            <button type="button" onclick="">
                                                    <span><svg aria-hidden="true" focusable="false" data-prefix="fas"
                                                               data-icon="shopping-cart"
                                                               class="svg-inline--fa fa-shopping-cart fa-w-18"
                                                               role="img" xmlns="http://www.w3.org/2000/svg"
                                                               viewBox="0 0 576 512"><path fill="currentColor"
                                                                                           d="M528.12 301.319l47.273-208C578.806 78.301 567.391 64 551.99 64H159.208l-9.166-44.81C147.758 8.021 137.93 0 126.529 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24h69.883l70.248 343.435C147.325 417.1 136 435.222 136 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-15.674-6.447-29.835-16.824-40h209.647C430.447 426.165 424 440.326 424 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-22.172-12.888-41.332-31.579-50.405l5.517-24.276c3.413-15.018-8.002-29.319-23.403-29.319H218.117l-6.545-32h293.145c11.206 0 20.92-7.754 23.403-18.681z"></path></svg></span>
                                                <span>اضافه سبد خرید</span></button>
                                            <button type="button" onclick="favorite('{{$item->product->id}}')"
                                                    title="اضافه به لیست علاقه مندی"><i
                                                        class="fa fa-heart"></i></button>
                                            <button type="button" title="مقایسه کن"
                                                    onclick="compare('{{$item->product->id}}')"><i
                                                        class="fas fa-exchange-alt"></i></button>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <!--<div class="col-12 col-md-3">
                <div class="side-add-to-bag-imag">

                </div>
            </div>-->
        </div>
    </div>
    <!--End Product Box-->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h3 class="modal-title" id="lineModalLabel">اشتراک گذاری</h3>
                </div>
                <div class="modal-body">
                    
                
                                        <div id="frmSocialShare" class="sharing-panel ">

                            <div class="sharing-socials clearfix">
                                <p class="sharing-socials-label">اشتراک گذاری</p>
                                <div class="d-block mt-2">
                                    <ul class="item-share p-0 text-center w-100">
                                        <li>
                                            <a target="_blank"
                                               href="tg://msg_url?url={{ urldecode(url()->current()) }}">
                                                <img alt="telegram"
                                                     src="{{asset('public/storage/files/1/icon/telegram_plane_icon.svg')}}">
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank"
                                               href="https://www.facebook.com/sharer/sharer.php?u={{ urldecode(url()->current()) }}">
                                                <img alt="telegram" width="30"
                                                     src="https://image.similarpng.com/very-thumbnail/2020/11/Blue-facebook-icon-on-transparent-background-PNG.png">
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank"
                                               href="http://twitter.com/home?status={{ urldecode(url()->current()) }}">
                                                <img alt="telegram" width="30"
                                                     src="https://toppng.com/uploads/preview/twitter-icon-transparent-11549680383mmzgiol88v.png">
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank"
                                               href="whatsapp://send?text={{ urldecode(url()->current()) }}"
                                               data-action="share/whatsapp/share">
                                                <img alt="Aparat"
                                                     src="{{asset('public/storage/files/1/icon/whatsapp_icon.svg')}}">
                                            </a>
                                        </li>


                                    </ul>
                                </div>
                            </div>

                            <div class="sharing-shortlink clearfix">
                                <label for="shortlink">آدرس صفحه</label>
                                <input name="ShareUrl" value="{{ urldecode(url()->current()) }}" readonly="readonly"
                                       style="direction: ltr; text-align: left;"
                                       type="text">
                            </div>

                        </div>
    
                    
                </div>

            </div>
        </div>
    </div>
</div>
@endsection


@section('site-js')

    <script src="{{ url('') }}/site_themes/js/first.js"></script>
    <script src="{{ url('') }}/site_themes/js/javascript.js"></script>
    <script src="{{ url('') }}/site_themes/js/jquery.lazyload.js"></script>
    <script src="{{ url('') }}/site_themes/js/jquery.fancybox.min.js"></script>
    <script src="{{ url('') }}/site_themes/js/zoom.js"></script>
    <script>
        function setRate(rate) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            if (rate != "" && rate <= 5 || rate >= 1) {
                $.ajax({
                    type: "post",
                    url: "{{ route('site.rate.store') }}",
                    data: {
                        rate: rate,
                        id: "{{ $product->id }}",
                        _token: CSRF_TOKEN
                    },
                    success: function (data) {
                        if (data.status == 200) {
                            Swal.fire({
                                title: "@lang('cms.success')",
                                text: data.message,
                                icon: "success",
                                button: "بستن",
                            }).then(function () {
                                location.reload();
                            });
                        }
                        if (data.status == 100) {
                            Swal.fire({
                                title: "@lang('cms.alert')",
                                text: data.message,
                                icon: "warning",
                                button: "بستن",
                            });
                        }
                    },
                    error: function (error) {
                        Swal.fire({
                            title: "@lang('cms.error')",
                            text: "@lang('cms.try-again-few-moments')",
                            icon: "error",
                            button: "@lang('cms.accept-2')",
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: "هشدار",
                    text: "مقدار امتیاز نامعتبر!",
                    icon: "error",
                    button: "بستن",
                });
            }
        }
    </script>

    <script>

        var heroSlider = $('.MultiCarousel');
        var owlCarouselTimeout = 3500;
        $('.MultiCarousel').owlCarousel({
            autoplay: true,
            loop: false,
            autoplayHoverPause: true,
            smartSpeed: 450,
            rtl: true,
            margin: 20,
            dots: false,
            nav: false,
            lazyLoad: true,
            responsive: {
                0: {
                    items: 2
                },
                500: {
                    items: 3
                },
                768: {
                    items: 3

                },
                1200: {
                    items: 5

                }

            }
        });
        heroSlider.on('mouseleave', function () {
            heroSlider.trigger('stop.owl.autoplay');
            heroSlider.trigger('play.owl.autoplay', [owlCarouselTimeout]);
        })
    </script>


    {{-- change --}}
    <script>

        var i = 0;
        var j = 0;
        /* when color initials and change color to show new price and new size */
        $(document).on('change', '#color', function () {
            i = 0;
            var selected = $('#color option:selected').val();
            var userActive = $('.sellers.active').attr('data-id');

            if (selected != null && selected != undefined && selected != 0) {

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "post",
                    url: "{{route('site.product.ajaxVariationColor')}}",
                    data: {
                        productId: <?= isset($product) ? $product->id : null  ?>,
                        variationColor: selected,
                        user_id: userActive,
                        _token: CSRF_TOKEN
                    },

                    success: function (data) {
                        // alert(data);
                        if (data instanceof Object) {
                            $("#resultColor").html(data.html);
                        } else if (data != "") {
                            $('#ajax-price').html(data);
                            $('#resultColor').html("");
                        }
                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });

            } else {
                $('#resultColor').html("");
                var oldPrice = $('#old-price').val();
                var ajax_price = $('#ajax-price').html(oldPrice);

                $('#result-description').html("");
                $('#div-description').css('display', 'none');
            }

        });

        /* when on change size select option after ajax */
        $(document).on('change', '#sizeAjax', function () {
            var selected = $('#sizeAjax option:selected').val();
            var selectedColor = $('#color option:selected').val();
            if (selected != null && selected != undefined && selected != 0) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "post",
                    url: "{{route('site.product.ajaxVariationSize')}}",
                    data: {
                        productId: <?= isset($product) ? $product->id : null  ?>,
                        variationSize: selected,
                        variationColors: selectedColor,
                        _token: CSRF_TOKEN
                    },

                    success: function (data) {
                        var price = data['price'];
                        var priceDiscount = data['priceDiscount'];
                        var description = data['description'];
                        if (price != "") {


                            if (priceDiscount != "") {
                                $('.discount-price').css('display', 'block');
                                $('#ajax-priceDiscount').html(priceDiscount);
                                $('#ajax-price').addClass('product-price-orgin');
                            } else {
                                $('#ajax-price').removeClass('offed');
                                $('#ajax-priceDiscount').html("");
                                $('.discount-price').css('display', 'none');
                            }
                            $('#ajax-price').html(price);


                            if (description != "") {
                                $('#div-description').css('display', 'block');
                                $('#result-description').html(description);
                            } else {
                                $('#div-description').css('display', 'none');
                            }
                        }
                        //alert(data);

                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });
            } else {
                $('#result-description').html("");
                $('#div-description').css('display', 'none');
            }
        });

        /* when change size */
        $(document).on('change', '#size', function () {
            var userActive = $('.sellers.active').attr('data-id');
            var selected = $('#size option:selected').val();
            if (selected != null && selected != undefined && selected != 0) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: "post",
                    url: "{{route('site.product.ajaxVariationSize')}}",
                    data: {
                        productId: <?= isset($product) ? $product->id : null  ?>,
                        variationSize: selected,
                        user_id: userActive,
                        _token: CSRF_TOKEN
                    },

                    success: function (data) {
                        var description = data['description'];
                        var price = data['price'];
                        var priceDiscount = data['priceDiscount'];
                        if (price != "") {

                            if (priceDiscount != "") {
                                $('.discount-price').css('display', 'block');
                                $('#ajax-priceDiscount').html(priceDiscount);
                                $('#ajax-price').addClass('product-price-orgin');
                            } else {
                                $('#ajax-price').removeClass('offed');
                                $('#ajax-priceDiscount').html("");
                                $('.discount-price').css('display', 'none');
                            }
                            $('#ajax-price').html(price);

                            if (description != "") {
                                $('#div-description').css('display', 'block');
                                $('#result-description').html(description);
                            } else {
                                $('#div-description').css('display', 'none');
                            }
                        }
                        //alert(data);

                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });

            } else {
                var oldPrice = $('#old-price').val();
                var ajax_price = $('#ajax-price').html(oldPrice);
                $('#div-description').css('display', 'none');
                $('#result-description').html("");
            }
        });

    </script>

    {{-- when color active --}}
    <script>
        jQuery(function ($) {
            /*  var i = 0;*/
            $(document).ajaxStop(function () {

                if (i == 0) {
                    var selectedColor = $('#color option:selected').val();
                    var userActive = $('.sellers.active').attr('data-id');
                    if (selectedColor != null && selectedColor != undefined && selectedColor != 0) {

                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            type: "post",
                            url: "{{route('site.product.ajaxVariationColor')}}",
                            data: {
                                productId: <?= isset($product) ? $product->id : null  ?>,
                                variationColor: selectedColor,
                                user_id: userActive,
                                _token: CSRF_TOKEN
                            },

                            success: function (data) {
                                // alert(data);
                                console.log(data);
                                if (data instanceof Object) {
                                    var price = data['price'];
                                    var priceDiscount = data['priceDiscount'];
                                    $("#resultColor").html(data.html);

                                    if (price != "") {
                                        //  alert(priceDiscount);
                                        if (priceDiscount != "") {
                                            $('.discount-price').css('display', 'block');
                                            $('#ajax-priceDiscount').html(priceDiscount);
                                            $('#ajax-price').addClass('product-price-orgin');
                                        } else {
                                            $('#ajax-price').removeClass('offed');
                                            $('#ajax-priceDiscount').html("");
                                            $('.discount-price').css('display', 'none');
                                        }
                                        $('#ajax-price').html(price);

                                    }

                                    if (data.description != "") {
                                        $('#div-description').css('display', 'block');
                                        $('#result-description').html(data.description);
                                    } else {
                                        $('#result-description').html("");
                                        $('#div-description').css('display', 'none');
                                    }

                                } else if (data != "") {
                                    $('#ajax-price').html(data);
                                    $('#resultColor').html("");
                                }
                            },
                            error: function (error) {
                                //alert(error);
                                alert("لطفا چند لحظه دیگر وارد شوید.");
                            }
                        });

                    } else {
                        $('#resultColor').html("");
                        var oldPrice = $('#old-price').val();
                        var ajax_price = $('#ajax-price').html(oldPrice);

                        //$('#result-description').html("");
                        // $('#div-description').css('display', 'none');

                    }
                }
                i = 1;
            });
        });
    </script>

    {{-- when size active --}}
    <script>
        jQuery(function ($) {
            $(document).ajaxStop(function () {
                if (j == 0) {
                    var selected = $('#size option:selected').val();
                    var userActive = $('.sellers.active').attr('data-id');
                    if (selected != null && selected != undefined && selected != 0) {
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            type: "post",
                            url: "{{route('site.product.ajaxVariationSize')}}",
                            data: {
                                productId: <?= isset($product) ? $product->id : null  ?>,
                                variationSize: selected,
                                user_id: userActive,
                                _token: CSRF_TOKEN
                            },

                            success: function (data) {

                                var description = data['description'];
                                var price = data['price'];
                                var priceDiscount = data['priceDiscount'];
                                /*alert(priceDiscount);*/
                                if (price != "") {

                                    if (priceDiscount != "") {
                                        $('.discount-price').css('display', 'block');
                                        $('#ajax-priceDiscount').html(priceDiscount);
                                        $('#ajax-price').addClass('product-price-orgin');
                                    } else {
                                        $('#ajax-price').removeClass('offed');
                                        $('#ajax-priceDiscount').html("");
                                        $('.discount-price').css('display', 'none');
                                    }
                                    $('#ajax-price').html(price);


                                    if (description != "") {
                                        $('#div-description').css('display', 'block');
                                        $('#result-description').html(description);
                                    } else {
                                        $('#div-description').css('display', 'none');
                                    }
                                }
                                //alert(data);

                            },
                            error: function (error) {
                                //alert(error);
                                alert("لطفا چند لحظه دیگر وارد شوید.");
                            }
                        });

                    } else {
                        var oldPrice = $('#old-price').val();
                        var ajax_price = $('#ajax-price').html(oldPrice);
                        // $('#div-description').css('display', 'none');
                        /* $('#result-description').html("");*/
                    }
                }
                j = 1;
            });
        });
    </script>

    {{-- seller set active --}}
    <script>
        jQuery(function ($) {
            /* seller => change price when clicked on seller */
            $('.sellers').click(function (e) {

                i = 0;
                j = 0;
                e.preventDefault();
                $('.sellers').removeClass('active');
                var thiss = $(this).addClass('active');
                var user_id = thiss.attr('data-id');

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "post",
                    url: "{{route('site.product.ajaxSeller')}}",
                    data: {
                        productId: <?= isset($product) ? $product->id : null  ?>,
                        user_id: user_id,
                        _token: CSRF_TOKEN
                    },

                    success: function (data) {
                        var description = data['description'];
                        var price = data['price'];
                        var priceDiscount = data['priceDiscount'];

                        var variety = data['variety'];
                        if (variety == 0) {
                            $('#variety').html("");
                            if (price != "") {

                                if (priceDiscount != "") {
                                    $('.discount-price').css('display', 'block');
                                    $('#ajax-priceDiscount').html(priceDiscount);
                                    $('#ajax-price').addClass('product-price-orgin');
                                } else {
                                    $('#ajax-price').removeClass('offed');
                                    $('#ajax-priceDiscount').html("");
                                    $('.discount-price').css('display', 'none');
                                }
                                $('#ajax-price').html(price);


                                if (description != "") {
                                    $('#div-description').css('display', 'block');
                                    $('#result-description').html(description);
                                } else {
                                    $('#div-description').css('display', 'none');
                                }
                            }
                        } else {
                            $('#ajax-price').html(price);
                            $('#variety').html(data.html);
                            // $('#div-description').css('display', 'none');
                        }
                    },
                    error: function (error) {
                        //alert(error);
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }
                });
            });

            /* when seller is active  */
            var user_id = $('.sellers.active').attr('data-id');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "{{route('site.product.ajaxSeller')}}",
                data: {
                    productId: <?= isset($product) ? $product->id : null  ?>,
                    user_id: user_id,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                    if (data instanceof Object) {
                        var description = data['description'];
                        var price = data['price'];
                        var priceDiscount = data['priceDiscount'];

                        var variety = data['variety'];
                        if (variety == 0) {
                            $('#variety').html("");
                            if (price != "") {

                                if (priceDiscount != "") {
                                    $('.discount-price').css('display', 'block');
                                    @if((auth()->check() && !auth()->user()->isColleague()) || !auth()->check())
                                    $('#ajax-priceDiscount').html(priceDiscount);
                                    $('#ajax-price').addClass('product-price-orgin');
                                    @endif
                                } else {
                                    $('#ajax-price').removeClass('offed');
                                    $('#ajax-priceDiscount').html("");
                                    $('.discount-price').css('display', 'none');
                                }
                                $('#ajax-price').html(price);


                                if (description != "") {
                                    $('#div-description').css('display', 'block');
                                    $('#result-description').html(description);
                                } else {
                                    $('#div-description').css('display', 'none');
                                }
                            }
                        } else {
                            $('#variety').html(data.html);
                        }
                    } else {
                    }
                },
                error: function (error) {
                    //alert(error);
                    alert("لطفا چند لحظه دیگر وارد شوید.");
                }
            });
        });
    </script>

    {{-- add - bascket --}}
    <script>
        $('.add-basket').on('click', function (e) {
            e.preventDefault();
            var colorSelected = $('#color option:selected').val();
            var sizeSelected = $('#sizeAjax option:selected').val();
            var sizeSingle = $('#size option:selected').val();
            var userActive = $('.sellers.active').attr('data-id');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "{{route('site.basket')}}",
                data: {
                    productId: <?= isset($product) ? $product->id : null; ?>,
                    color: colorSelected,
                    size: sizeSelected,
                    sizeSingle: sizeSingle,
                    sellerUserId: userActive,
                    single: true,
                    _token: CSRF_TOKEN
                },

                success: function (data) {
                    console.log(data);
                    if (data.status == 403) {
                        Swal.fire({
                            title: "خطا!",
                            text: "لطفا ابتدا وارد حساب کاربری خود شوید.",
                            icon: "error",
                            button: "تایید",
                        });
                    }

                    if (data.status == 404) {
                        Swal.fire({
                            title: "خطا!",
                            text: "محصولی با این مشخصه یافت نشد.",
                            icon: "error",
                            button: "تایید",
                        });
                    }

                    if (data.status == 102) {
                        Swal.fire({
                            title: "متاسفیم!",
                            text: data.message,
                            icon: "error",
                            button: "تایید",
                        });
                    }

                    if (data.status == 200) {

                        Swal.fire({
                            title: "موفقیت آمیز!",
                            text: "محصول مورد نظر به سبد خرید شما اضافه شد",
                            icon: "success",
                            showCancelButton: false,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: false,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "بستن",
                        }).then(function () {
                            location.reload();
                        });
                    }
                    //$(".cart").load(" .cart > *");


                    if (data.status == 110) {
                        window.location = "{{ route('site.basket.checkout')  }}";
                    }

                },
                error: function (error) {
                    //alert(error);
                    if (error.status == 401) {
                        Swal.fire({
                            title: "خطا!",
                            text: "لطفا ابتدا وارد حساب کاربری خود شوید.",
                            icon: "error",
                            button: "تایید",
                        });
                    } else {
                        alert("لطفا چند لحظه دیگر وارد شوید.");
                    }

                }
            });
        });
    </script>
    <script>
        ///اسکریپت نمایش ویژگی محصول///

        $('#showmenu').click(function () {
            $('.menu-item').show();
            $('#showmenu').hide();
            $('.close-box').show();


        });
        $('.close-box').click(function () {
            $('.menu-item').hide();
            $('#showmenu').show();
            $('.close-box').hide();

        });


        <!---->
        ///اسکریپت زوم عکس محصول//
        var viewportWidth = $(window).width();
        if (viewportWidth > 767) {
            $("#zoom_01").elevateZoom({
                scrollZoom: true,
                zoomWindowPosition: 10
            });
        }

        <!---->

        ///اسکریپت تامبنل های محصول//
        $(function () {
            var owl = $('.thumb-product3');
            owl.owlCarousel({
                rtl: true,
                margin: 10,
                loop: false,
                nav: false,
                dots: false,
                autoplay: false,
                items: 4,
                touchDrag: false,
                mouseDrag: false,
                navText: ['<i class="fa fa-angle-left fa-2x fa-fw" aria-hidden="true"></i>', '<i class="fa fa-angle-right fa-2x fa-fw" aria-hidden="true"></i>']

            });
        });

        <!---->

        ///اسکریپت گالری تصاویر محصول///

        $(function () {

            var $modal = $('#myModal3');

            var fotoramaOptions = {
                nav: 'thumbs',
                width: '100%',
                maxheight: '80%',
                //transition: 'crossfade',
                keyboard: true,
                allowfullscreen: true
            }

            $('[data-reveal]').on('click', revealModal)

            $('.close-reveal-modal').on('click', function () {
                $modal.foundation('reveal', 'close');
            })

            function revealModal() {
                $modal.foundation('reveal', 'open');
            }

            $modal.bind('opened', function () {
                $('#fotorama').fotorama(fotoramaOptions);
            })
        });


        $(document).ready(function () {
            var pw = $('.fotorama__nav--thumbs').innerWidth();
            var cw = $('.fotorama__nav__shaft').innerWidth();
            var offset = pw - cw;
            var negOffset = (-1 * offset) / 2;
            var totalOffset = negOffset + 'px';
            if (pw > cw) {
                $('.fotorama__nav__shaft').css('transform', 'translate3d(' + totalOffset + ', 0, 0)');
            }
            $('.fotorama__nav__frame--thumb, .fotorama__arr, .fotorama__stage__frame, .fotorama__img, .fotorama__stage__shaft').click(function () {
                if (pw > cw) {
                    $('.fotorama__nav__shaft').css('transform', 'translate3d(' + totalOffset + ', 0, 0)');
                }
            });
        });

        <!---->

        ///اسکریپت جایگزینی تامبنیل ها با عکس بزرگ با کلیک بر روی آن ها///
        $("body").delegate(".thumbnail", "click", function (event) {
            event.preventDefault();
            var selected = $(this);

            new_html = '<a onclick="return false;" class="thumbnail first_thumbnail" href="' + selected.attr('href') + '" title="آ">';
            new_html += '<img id="zoom_01" class="img-fluid" src="' + selected.attr('href') + '" title=""  alt="" data-zoom-image="' + selected.attr('big_image') + '"/>';
            new_html += '</a>';

            $(".first_thumbnail").parent().html(new_html);
            $(".zoomContainer").remove();


            var viewportWidth = $(window).width();
            if (viewportWidth > 767) {
                $('#zoom_01').elevateZoom({
                    scrollZoom: true,
                    zoomWindowPosition: 10
                });
            } else if (viewportWidth < 768) {
                $('#zoom_01').elevateZoom({
                    zoomType: "inner",
                    cursor: "crosshair"
                });
            }


        });

        <!---->

        ///اسکریپت اسکرول محصولات مرتبط///
        var heroSlider = $('.owl-related');
        var owlCarouselTimeout = 1000;
        heroSlider.on('initialize.owl.carousel initialized.owl.carousel ' +
            'initialize.owl.carousel initialize.owl.carousel ' +
            'resize.owl.carousel resized.owl.carousel ' +
            'refresh.owl.carousel refreshed.owl.carousel ' +
            'update.owl.carousel updated.owl.carousel ' +
            'drag.owl.carousel dragged.owl.carousel ' +
            'translate.owl.carousel translated.owl.carousel ' +
            'to.owl.carousel changed.owl.carousel',
            function (e) {
                $('.' + e.type)
                    .removeClass('secondary')
                    .addClass('success');
                window.setTimeout(function () {
                    $('.' + e.type)
                        .removeClass('success')
                        .addClass('secondary');
                }, 500);
            });
        $('.owl-related').owlCarousel({
            loop: false,
            autoplayHoverPause: true,
            smartSpeed: 450,
            rtl: true,
            margin: 20,
            navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
            lazyLoad: true,
            responsive: {
                0: {
                    items: 1,
                    dots: false,
                    nav: true
                },
                500: {
                    items: 2,
                    dots: true,
                    nav: false
                },
                768: {
                    items: 3,
                    dots: true,
                    nav: false

                },
                1200: {
                    items: 4,
                    dots: true,
                    nav: false

                }

            }
        });
        heroSlider.on('mouseleave', function () {
            heroSlider.trigger('stop.owl.autoplay');
            heroSlider.trigger('play.owl.autoplay', [owlCarouselTimeout]);
        });

        $(document).ready(function () {
            // assign captions from title-attributes:
            $("[data-fancybox]").each(function () {
                $(this).attr("data-caption", $(this).attr("title"));
            });
            // start fancybox on all elements with attribute 'data-fancybox':
            $("[data-fancybox]").fancybox();
        });
        $("img.lazy").lazyload({effect: "slideDown"});

        $(document).ready(function () {
            var bigimage = $("#big");
            var thumbs = $("#thumbs");
            //var totalslides = 10;
            var syncedSecondary = true;
            bigimage
                .owlCarousel({
                    items: 1,
                    slideSpeed: 2000,
                    nav: false,
                    margin: 1,
                    autoplay: false,
                    dots: false,
                    rtl: true,
                    loop: true,
                    responsiveRefreshRate: 200,
                    navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"]
                })
                .on("changed.owl.carousel", syncPosition);
            thumbs
                .on("initialized.owl.carousel", function () {
                    thumbs
                        .find(".owl-item")
                        .eq(0)
                        .addClass("current");
                })
                .owlCarousel({
                    items: 5,
                    dots: true,
                    margin: 10,
                    rtl: true,
                    nav: false,
                    navText: [
                        '<i class="fas fa-chevron-left"></i>',
                        '<i class="fas fa-chevron-right"></i>'
                    ],
                    smartSpeed: 200,
                    slideSpeed: 500,
                    slideBy: 1,
                    responsiveRefreshRate: 100
                })
                .on("changed.owl.carousel", syncPosition2);

            function syncPosition(el) {
                //if loop is set to false, then you have to uncomment the next line
                //var current = el.item.index;
                //to disable loop, comment this block
                var count = el.item.count - 1;
                var current = Math.round(el.item.index - el.item.count / 2 - 0.5);
                if (current < 0) {
                    current = count;
                }
                if (current > count) {
                    current = 0;
                }
                //to this
                thumbs
                    .find(".owl-item")
                    .removeClass("current")
                    .eq(current)
                    .addClass("current");
                var onscreen = thumbs.find(".owl-item.active").length - 1;
                var start = thumbs
                    .find(".owl-item.active")
                    .first()
                    .index();
                var end = thumbs
                    .find(".owl-item.active")
                    .last()
                    .index();
                if (current > end) {
                    thumbs.data("owl.carousel").to(current, 100, true);
                }
                if (current < start) {
                    thumbs.data("owl.carousel").to(current - onscreen, 100, true);
                }
            }

            function syncPosition2(el) {
                if (syncedSecondary) {
                    var number = el.item.index;
                    bigimage.data("owl.carousel").to(number, 100, true);
                }
            }

            thumbs.on("click", ".owl-item", function (e) {
                e.preventDefault();
                var number = $(this).index();
                bigimage.data("owl.carousel").to(number, 300, true);
            });
        });

    </script>

    @if(isset($product->catalog[0]) && !empty($product->catalog[0]))
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf.min.js"
                integrity="sha512-g4FwCPWM/fZB1Eie86ZwKjOP+yBIxSBM/b2gQAiSVqCgkyvZ0XxYPDEcN2qqaKKEvK6a05+IPL1raO96RrhYDQ=="
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf.worker.entry.min.js"
                integrity="sha512-NJEHr6hlBM4MkVxJu+7FBk+pn7r+KD8rh+50DPglV/8T8I9ETqHJH0bO7NRPHaPszzYTxBWQztDfL6iJV6CQTw=="
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf.worker.min.js"
                integrity="sha512-QVzIOQH0mGpLAOwHfVSOGsVjh4UGon7+hQwoqIUHbTMvcyS76Ee3AUDep58mU2TvdkPgzZ4aQqxbZ0v2wsyvpA=="
                crossorigin="anonymous"></script>

        <script>
            var pdfjsLib = window['pdfjs-dist/build/pdf'];

            // The workerSrc property shall be specified.
            pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';

            var pdfDoc = null,
                pageNum = 1,
                pageRendering = false,
                pageNumPending = null,
                scale = 2.5,
                canvas = document.getElementById('the-canvas'),
                ctx = canvas.getContext('2d');

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "{{ route('site.product.load') }}",
                data: {
                    id: "{{ $product->id }}",
                    _token: CSRF_TOKEN
                },
                success: function (data) {
                    if (data.status == 200) {
                        var pdfData = atob(data.url);
                        // Loaded via <script> tag, create shortcut to access PDF.js exports.


                       


                        /**
                         * Get page info from document, resize canvas accordingly, and render page.
                         * @param num Page number.
                         */
                        function renderPage(num) {

                            num = parseInt(num);
                            pageRendering = true;
                            // Using promise to fetch the page
                            pdfDoc.getPage(num).then(function (page) {
                                var viewport = page.getViewport({scale: scale});
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;

                                // Render PDF page into canvas context
                                var renderContext = {
                                    canvasContext: ctx,
                                    viewport: viewport
                                };
                                var renderTask = page.render(renderContext);

                                // Wait for rendering to finish
                                renderTask.promise.then(function () {
                                    pageRendering = false;
                                    if (pageNumPending !== null) {
                                        // New page rendering is pending
                                        renderPage(pageNumPending);
                                        pageNumPending = null;
                                    }
                                });
                            });

                            // Update page counters
                            document.getElementById('page_num').textContent = num;
                        }

                        /**
                         * If another page rendering in progress, waits until the rendering is
                         * finised. Otherwise, executes rendering immediately.
                         */
                        function queueRenderPage(num) {
                            if (pageRendering) {
                                pageNumPending = num;
                            } else {
                                renderPage(num);
                            }
                        }

                        /**
                         * Displays previous page.
                         */
                        function onPrevPage() {
                            if (pageNum <= 1) {
                                return;
                            }
                            pageNum--;
                            queueRenderPage(pageNum);
                        }

                        document.getElementById('prev').addEventListener('click', onPrevPage);


                        //enlarge
                        document.getElementById('enlarge').addEventListener('click', function () {
                            scale += 0.1;
                            queueRenderPage(pageNum);
                        });

                        //Zoom out
                        document.getElementById('letting').addEventListener('click', function () {
                            scale -= 0.1;
                            queueRenderPage(pageNum);
                        });

                        /**
                         * Displays next page.
                         */
                        function onNextPage() {
                            if (pageNum >= pdfDoc.numPages) {
                                return;
                            }
                            pageNum++;
                            queueRenderPage(pageNum);
                        }

                        document.getElementById('next').addEventListener('click', onNextPage);

                        /**
                         * Asynchronously downloads PDF.
                         */
                        pdfjsLib.getDocument({data: pdfData}).promise.then(function (pdfDoc_) {
                            pdfDoc = pdfDoc_;
                            document.getElementById('page_count').textContent = pdfDoc.numPages;

                            // Initial/first page rendering
                            renderPage(pageNum);
                        });
                    }
                },
                error: function (error) {
                    {{--Swal.fire({--}}
                    {{--    title: "@lang('cms.error')",--}}
                    {{--    text: "@lang('cms.try-again-few-moments')",--}}
                    {{--    icon: "error",--}}
                    {{--    button: "@lang('cms.accept-2')",--}}
                    {{--});--}}
                }
            });
            function renderPage(num) {

                num = parseInt(num);
                pageRendering = true;
                // Using promise to fetch the page
                pdfDoc.getPage(num).then(function (page) {
                    var viewport = page.getViewport({scale: scale});
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);

                    // Wait for rendering to finish
                    renderTask.promise.then(function () {
                        pageRendering = false;
                        if (pageNumPending !== null) {
                            // New page rendering is pending
                            renderPage(pageNumPending);
                            pageNumPending = null;
                        }
                    });
                });

                // Update page counters
                document.getElementById('page_num').textContent = num;
            }
        </script>
    @endif

    @if(isset($product->video[0]) && !empty($product->video[0]) || $product->type == \App\Utility\ProductType::VOICE)
{{--    <script>--}}
{{--        $('#loading-image').show();--}}
{{--        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');--}}
{{--        $.ajax({--}}
{{--            type: "post",--}}
{{--            url: "{{ route('site.product.player') }}",--}}
{{--            data: {--}}
{{--                id: "{{ $product->id }}",--}}
{{--                _token: CSRF_TOKEN--}}
{{--            },--}}
{{--            success: function (data) {--}}
{{--                if (data.status == 200) {--}}
{{--                    $('.player').html(data.view);--}}
{{--                }--}}
{{--            },--}}
{{--            complete: function(){--}}
{{--                $('#loading-image').hide();--}}
{{--            },--}}
{{--            error: function (error) {--}}

{{--            }--}}
{{--        });--}}
{{--    </script>--}}
    @endif
@endsection
