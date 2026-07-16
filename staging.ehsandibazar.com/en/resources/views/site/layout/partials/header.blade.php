<!--منو حالت گوشی-->
<nav class="nav panel-menu" role="navigation" id="panel-menu">
    <span class="closePanel close-menu"><i class="times"></i></span>
    <div class="row header-menu">
        <div class="col-12 p-0">
            <div class="btn-menu">
                @if(isset($logo))
                    <li class="logo">
                        <a href="{{ route('site.index') }}">
                            <img src="{{$logo->code5}}" class="img-fluid" alt="{{$logo->name}}">
                        </a>
                    </li>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 p-0">
            <ul>
                <li class="main-menu"><a href="{{ route('site.index') }}">home</a></li>
                <li class="main-menu"><a href="{{ route('site.aboutUs') }}">about us</a></li>
                @if(isset($categories) && !empty($categories))
                    @foreach($categories as $item)
                        <li class="main-menu">
							<span class="openSubPanel"> {{ $item->title }}
								<span class="arow-menu"><i class=""></i></span>
							</span>
                            <ul class="subPanel">
                                <li class="close-li">
									<span class="closeSubPanel">
										<i class=""></i> back </span>
                                </li>
                                <li class="close-li"><a
                                                        href="{{ $item->path() }}">show {{ $item->title }}</a>
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
                                                        <span class="closeSubPanel"> <i class="t"></i> back </span>
                                                    </li>
                                                      <li class="close-li"><a
                                                        href="{{ $category->path() }}">show {{ $category->title }}</a>
                                                        </li>
                                                    @foreach($category->categories as $item)
                                                        <li class="main-menu"><a
                                                                    href="{{ $item->path() }}">{{ $item->title }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @else
                                            <li class="main-menu"><a
                                                        href="{{ $category->path() }}">{{ $category->title }}</a>
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
                <li class="main-menu"><a href="{{ route('site.article') }}"> blog</a></li>
                <li class="main-menu"><a href="{{ route('site.contactUs') }}">contact us</a></li>
                <li class="main-menu"><a href="{{ route('site.exam') }}">exam</a></li>
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
                                    <img src="{{$logo->code5}}" class="img-fluid" alt="{{$logo->name}}">
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="col-4 col-md-4 d-xl-none d-lg-none p-0 text-end">
                     <span class="w-icon me-4">
                            <a href="https://api.whatsapp.com/send/?phone=989128936406&text&app_absent=0">
                        <img src="{{ asset('site_theme/images/wh.svg') }}" class="img-fluid" alt="">
                    </a>
                        </span>
                    <span>
								<a href="{{ route('site.basket.checkout')  }}" class="shop">
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
                           <a href="/">
                                <span>
                                    <img src="{{asset('site_theme/images/ir.svg')}}" width="20">
                                </span>
                           </a>
                        </li>

                        @guest()
                            <li>
                                <a href="{{ route('login') }}">
                                    <i class="fal fa-user"></i>
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
                                <input type="text" class="form-control" placeholder="saerch  ...">
                                <button type="submit" class="search-icon img-search"><i class="fal fa-search"></i>
                                </button>
                            </form>

                        </li>
                    </ul>
                </div>
                <div class="col-lg-12 col-md-2 d-none d-lg-block  p-0">
                    <div class="cssmenu" id="cssmenu1">
                        <ul class="">
                            <li class="lng  align-items-center">
                                <a href="/">
                                <span>FA</span>
                                <span>
                                    <img src="{{asset('site_theme/images/ir.svg')}}" width="20">
                                </span>
                           </a>
                            </li>
                            <li class=""><a href="{{ route('site.index') }}">home</a></li>
                            <li class=""><a href="{{ route('site.aboutUs') }}">about us</a></li>
                            @if(isset($categories) && !empty($categories))
                                @foreach($categories as $item)
                                    <li class="has-sub">
                                        <a href="{{ $item->path() }}">{{ $item->title }}
                                            <i class="fal fa-chevron-down"></i>
                                        </a>
                                        @if(isset($item->categories) && !empty($item->categories))
                                            <ul>
                                                @foreach($item->categories as $category)
                                                    <li><a href="{{ $category->path() }}">{{ $category->title }}</a>
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
                            <li class=""><a href="{{ route('site.article') }}">blog </a></li>
                            <li class=""><a href="{{ route('site.contactUs') }}">contact us </a></li>
                            <li class=""><a href="{{ route('site.exam') }}">exam</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-12  text-end d-none d-lg-block">
            <ul class="p-0 link-left">
                <li>
                    <span class="fllow"> WhatsApp </span>
                    <span class="w-icon"><a href="https://api.whatsapp.com/send/?phone=989128936406&text&app_absent=0">
                         <img src="{{ asset('site_theme/images/wh.svg') }}" class="img-fluid" alt="">
                    </a></span>
                </li>
                <li class="search-ico">
                    <form class="frm-search" action="{{ route('site.search') }}" method="get">
                        <input type="text" class="form-control" placeholder="saerch  ...">
                        <button type="submit" class="search-icon img-search"><i class="fal fa-search"></i></button>
                    </form>
                </li>
                @guest()
                    <li>
                        <a href="{{ route('login') }}"><i class="fal fa-user"></i></a>
                    </li>
                @else
                    <li>
                        <a href="{{ auth()->user()->isCustomer() || auth()->user()->isColleague() ? route('users.dashboard.index') : route('panel.dashboard.index')  }}"><i
                                    class="fal fa-user"></i></span></a>
                    </li>
                @endguest
                <li>
                    <a href="{{ route('site.basket.checkout') }}" class="shop">
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