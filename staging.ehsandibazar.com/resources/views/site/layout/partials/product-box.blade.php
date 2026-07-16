<div class="item item-pro">
    <div class="item-img">
        <div class="quick-btn">
            <div class="list">
                <ul>
                    <li class="list-mrnu">
                        <button type="button" data-toggle="tooltip"
                                title="افزودن به لیست علاقه مندی"
                                onclick="favorite('{{$product->id}}')" data-original-title="افزودن به لیست علاقه مندی">
                            <i class="heart-icon"></i>
                        </button>
                    </li>
                    <li class="list-mrnu">
                                    <span class="show-rate">
										<span class="count-rate"><i class="fas fa-star"></i>({{(float)$product->averageRating}})</span>
									</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-12 p-0">
                <div class="item-box">
                @if(\App\Utility\DiscountType::hasDiscount($product) == true)
                    <div class="off-pro"><span>{{ \App\Utility\DiscountType::showCentDiscount($product) }}</span></div>
                @endif
                <div class="book">
                    <a href="{{$product->path()}}">
                        <ul>
                            <li class="page page3"></li>
                            <li class="page page2"></li>
                            <li class="page page1"></li>
                            <li class="cover">
                                <div
                                    style="background: #fff url('{{$product->image[0]->url}}');"></div>
                            </li>
                        </ul>
                    </a>
                </div>
 </div>

            </div>
        </div>

    </div>
    <div class="row mt-2">
        <div class="col-12 text-center pro-name p-0">
            <a href="{{$product->path()}}">
                {{$product->title}}
            </a>
        </div>
        <div class="col-12 text-center  p-0">
            <div class="row pro-total-cost">
         
                    {!! $product->prices !!}
               
               

            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <ul class="lst">
                <li class="lnk-sale">
                    <a href="{{$product->path()}}">
                        خرید کنید
                        <span class="icon-basket"></span>
                    </a>
                </li>
                <li>
                    <a href="{{$product->path()}}">جزئیات</a>
                </li>
            </ul>
        </div>
    </div>

</div>
