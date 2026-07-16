<!--منو حالت گوشی-->
<nav class="nav panel-menu" role="navigation" id="panel-menu">
    <span class="closePanel close-menu"><i class="times"></i></span>
    <div class="row header-menu">
        <div class="col-12 p-0">
            <div class="btn-menu">
                @if(isset($logo))
                    <div class="logo">
                        <a href="{{ route('site.index') }}">
                            <img src="{{$logo->code5}}" width="70" height="64" class="img-fluid" alt="{{$logo->name}}">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 p-0">
            <ul>
                <li class="main-menu"><a href="{{ route('site.index') }}">صفحه اصلی</a></li>
                <li class="main-menu"><a href="{{ route('site.aboutUs') }}">درباره ما</a></li>
                <li class="main-menu"><a href="https://ehsandibazar.com/page/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D8%AF%D9%81%D8%A7%D8%B9-%D8%B4%D8%AE%D8%B5%DB%8C">آموزش دفاع شخصی</a></li>
                @if(isset($categories) && !empty($categories))
                    @foreach($categories as $item)
                        <li class="main-menu">
							<span class="openSubPanel"> {{ $item->title }}
								<span class="arow-menu"><i class=""></i></span>
							</span>
                            <ul class="subPanel">
                                <li class="close-li">
									<span class="closeSubPanel">
										<i class=""></i> بازگشت </span>
                                </li>
                                <li class="close-li"><a
                                                        href="{{ $item->path() }}">مشاهده {{ $item->title }}</a>
                                            </li>
                                @if(isset($item->categories) && !empty($item->categories))
                                    @foreach($item->categories as $category)
                                        @if(isset($category->categories) && !empty($category->categories) && count($category->categories) > 0)
                                            <li class="main-menu">
									<span class="openSubPanel">{{ $category->title }}
										<span class="arow-menu"><i class=""></i></span>
									</span>
                                                <ul class="subPanel">
                                                    <li class="close-li">
                                                        <span class="closeSubPanel"> <i class="t"></i> بازگشت </span>
                                                    </li>
                                                      <li class="close-li"><a
                                                        href="{{ $category->path() }}">مشاهده {{ $category->title }}</a>
                                                        </li>
                                                    @foreach($category->categories as $item)
                                                        <li class="main-menu"><a
                                                                    href="{{ $item->path() }}">{{ $item->title }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @else
                                            <li class="main-menu">
                                               
                                         @if($category->title=="آموزش حضوری")
                                          <a href="https://ehsandibazar.com/page/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D8%AE%D8%B5%D9%88%D8%B5%DB%8C-%D9%88%D8%B1%D8%B2%D8%B4-%D9%87%D8%A7%DB%8C-%D8%B1%D8%B2%D9%85%DB%8C-%D9%88-%D8%AF%D9%81%D8%A7%D8%B9-%D8%B4%D8%AE%D8%B5%DB%8C">{{ $category->title }}</a>
                                         @else
                                          <a href="{{ $category->path() }}">{{ $category->title }}</a>
                                         @endif 
                                             
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </li>
                    @endforeach
                @endif
                @if(isset($menus) && !empty($menus))
                    @foreach($menus as $menu)
                        <li class="main-menu"><a href="{{$menu->src}}">{{$menu->title}}</a></li>
                    @endforeach
                @endif
                <li class="main-menu"><a href="{{ route('site.article') }}"> مطالب آموزشی</a></li>
                <li class="main-menu"><a href="{{ route('site.contactUs') }}">تماس با ما</a></li>
               {{-- <li class="main-menu"><a href="{{ route('site.exam') }}">آزمون</a></li> --}}
            </ul>
        </div>
    </div>
</nav>
<!--هدر-->
<header class="c-header js-header" {{ \Illuminate\Support\Facades\Route::currentRouteName() != "site.index" ? 'style=background-color:#1d1d1d;' : null }}>
    <div class="row top-header">
        <div class="col-xl-9 col-lg-8 col-md-12 p-0">
            <div class="row align-items-center row-top-header">
                <div class="col-8 col-md-8 d-xl-none d-lg-none  p-0">
                    <ul class="toolbar-mob">
                        <li class="menuTrigger">
                            <span><i class="fal fa-bars"></i></span>
                        </li>
                        @if(isset($logo))
                            <li class="logo">
                                <a href="{{route('site.index')}}" class="d-block">
                                    <img src="{{$logo->code5}}" width="70" height="64" class="img-fluid" alt="{{$logo->name}}">
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="col-4 col-md-4 d-xl-none d-lg-none p-0 text-end">
                    {{--
                     <span class="w-icon me-4">
                            <a href="https://api.whatsapp.com/send/?phone=989128936406&text&app_absent=0">
                        <img src="{{ asset('site_themes/images/wh.svg') }}"width="31" height="31" class="img-fluid" alt="وب سایت دفاع شخصی">
                    </a>
                        </span>
                    --}}
                    <span>
								<a href="{{ route('site.basket.checkout')  }}" class="shop" aria-label="سبد خرید">
                                   @if(isset($sessionBasket) && !empty($sessionBasket) && $sessionBasket->totalQty > 0)
                                        <span class="count-shop">{{ $sessionBasket->totalQty }}</span>
                                    @endif
								 
								    <i class="fal fa-shopping-cart"></i>
								</a>
							</span>
                </div>
                <div class="col-12 d-xl-none d-lg-none p-0 col-mob">
                    <ul class="list-link">
                        <li class="lng  align-items-center">
                             <a href="https://trainwithehsan.com" aria-label="English">
                                <span>
                                    <img src="{{asset('site_themes/images/en.svg')}}" width="24" height="24" alt="English">
                                </span>
                           </a>
                        </li>

                        @guest()
                            <li>
                                <a href="{{ route('login') }}" aria-label="ورود به حساب کاربری">
                                    <i class="fal fa-user" aria-hidden="true"></i>
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ auth()->user()->isCustomer() || auth()->user()->isColleague() ? route('users.dashboard.index') : route('panel.dashboard.index')  }}">
                                    <i class="fal fa-user"></i>
                                </a>
                            </li>
                        @endguest
                        <li class="search-ico">

                            <form class="frm-search" action="{{ route('site.search') }}" method="get">
                                <input type="text" name="title" class="form-control" placeholder="جستجو  ..." aria-label="جستجو">
                                <button type="submit" class="search-icon img-search" aria-label="جستجو"><i class="fal fa-search" aria-hidden="true"></i>
                                </button>
                            </form>

                        </li>
                    </ul>
                </div>
                <div class="col-lg-12 col-md-2 d-none d-lg-block  p-0">
                    <div class="cssmenu" id="cssmenu1">
                        <ul class="">
                            <li class="lng  align-items-center">
                                 <a href="https://trainwithehsan.com" aria-label="English">
                                <span class="iconen"></span>
                                <span>
                                    <img alt="زبان انگلیسی آموزش دفاع شخصی دیبازر " src="{{asset('site_themes/images/en.svg')}}" width="24" height="24">
                                </span>
                           </a>
                            </li>
                            <li class=""><a href="{{ route('site.index') }}">صفحه اصلی</a></li>
                            <li class=""><a href="{{ route('site.aboutUs') }}">درباره ما </a></li>
                               <li class="main-menu"><a href="https://ehsandibazar.com/page/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D8%AF%D9%81%D8%A7%D8%B9-%D8%B4%D8%AE%D8%B5%DB%8C">آموزش دفاع شخصی</a></li>
              
                            @if(isset($categories) && !empty($categories))
                                @foreach($categories as $item)
                                    <li class="has-sub">
                                        <a href="{{ $item->path() }}">{{ $item->title }}
                                            <i class="fal fa-chevron-down"></i>
                                        </a>
                                        @if(isset($item->categories) && !empty($item->categories))
                                            <ul>
                                                @foreach($item->categories as $category)
                                                    <li>
                                         @if($category->title=="آموزش حضوری")
                                          <a href="https://ehsandibazar.com/page/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D8%AE%D8%B5%D9%88%D8%B5%DB%8C-%D9%88%D8%B1%D8%B2%D8%B4-%D9%87%D8%A7%DB%8C-%D8%B1%D8%B2%D9%85%DB%8C-%D9%88-%D8%AF%D9%81%D8%A7%D8%B9-%D8%B4%D8%AE%D8%B5%DB%8C">{{ $category->title }}</a>
                                         @else
                                          <a href="{{ $category->path() }}">{{ $category->title }}</a>
                                         @endif
                                                       
                                                        @if(isset($category->categories) && !empty($category->categories) && count($category->categories) > 0)
                                                            <ul>
                                                                @foreach($category->categories as $itemTwo)
                                                                    <li>
                                                                        <a href="{{ $itemTwo->path() }}">{{ $itemTwo->title }}</a>
                                                                @endforeach
                                                            </ul>
                                                        @endif

                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            @endif
                            @if(isset($menus) && !empty($menus))
                                @foreach($menus as $menu)
                                    <li class="main-menu"><a href="{{$menu->src}}">{{$menu->title}}</a></li>
                                @endforeach
                            @endif
                            <li class=""><a href="{{ route('site.article') }}">مطالب آموزشی </a></li>
                            <li class=""><a href="{{ route('site.contactUs') }}">تماس باما </a></li>
                           {{-- <li class=""><a href="{{ route('site.exam') }}">آزمون </a></li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-12  text-end d-none d-lg-block ps-md-0">
            <ul class="p-0 link-left">
                {{--
                <li>
                    <span class="fllow"> WhatsApp </span>
                    <span class="w-icon"><a href="https://api.whatsapp.com/send/?phone=989128936406&text&app_absent=0">
                         <img src="{{ asset('site_themes/images/wh.svg') }}" class="img-fluid" alt="آیکون واتس اپ آکادمی دفاع شخصی دیبازر">
                    </a></span>
                </li>
                --}}
                <li class="search-ico">
                    <form class="frm-search" action="{{ route('site.search') }}" method="get">
                        <input type="text" name="title" class="form-control" placeholder="جستجو  ..." aria-label="جستجو">
                        <button type="submit" class="search-icon img-search" aria-label="جستجو"><i class="fal fa-search" aria-hidden="true"></i></button>
                    </form>
                </li>
                @guest()
                    <li>
                        <a href="{{ route('login') }}" aria-label="ورود به حساب کاربری"><i class="fal fa-user" aria-hidden="true"></i></a>
                    </li>
                @else
                    <li>
                        <a href="{{ auth()->user()->isCustomer() || auth()->user()->isColleague() ? route('users.dashboard.index') : route('panel.dashboard.index')  }}"><i
                                    class="fal fa-user"></i></span></a>
                    </li>
                @endguest
                <li>
                    <a href="{{ route('site.basket.checkout') }}" class="shop" aria-label="سبد خرید">
                        @if(isset($sessionBasket) && !empty($sessionBasket) && $sessionBasket->totalQty > 0)
                            <span class="count-shop">{{ $sessionBasket->totalQty }}</span>
                        @endif

                        <i class="fal fa-shopping-cart"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>