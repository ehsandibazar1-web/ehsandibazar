<div class="sidebar-toggle-box">
    <div data-original-title="Toggle Navigation" data-placement="right" class="icon-reorder tooltips"></div>
</div>
<!--logo start-->
<a href="#" class="logo" style="font-size: 19px"><span>@lang('cms.panel')</span>&nbsp;@lang('cms.you')</a>
<!--logo end-->
<div class="nav notify-row" id="top_menu">
    <!--  notification start -->
    <ul class="nav top-menu">
    @can('notification-request')
        <!-- settings start -->
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <i class="icon-ok-circle"></i>
                    <span class="badge bg-dark">{{ $CountRequest }}</span>
                </a>
                <ul class="dropdown-menu extended tasks-bar">
                    <div class="notify-arrow notify-arrow-green"></div>
                    <li>
                        <p class="green">@lang('cms.last-request')</p>
                    </li>
                    @foreach($requests as $request)
                        <li>
                            <a href="#">
                <span class="subject">
                <span class="from">{{ $request->name." ".$request->family }}</span>
                <span class="time"></span>
                </span>
                                <span class="message"></span>
                            </a>
                        </li>
                    @endforeach
                    <li class="external">
                        <a href="{{ route('panel.request.index') }}"> @lang('cms.more')</a>
                    </li>
                </ul>
            </li>
            <!-- settings end -->
    @endcan
    @can('notification-contact')
        <!-- inbox dropdown start-->
            <li id="header_inbox_bar" class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <i class="icon-envelope-alt"></i>

                    <span class="badge bg-important">{{ $CountContact }}</span>

                </a>

                <ul class="dropdown-menu extended inbox">
                    <div class="notify-arrow notify-arrow-red"></div>
                    <li>
                        <p class="red"> @lang('cms.count-of-new-message') {{ $CountContact }} </p>
                    </li>

                    @foreach($Contact as $value)
                        <li>
                            <a href="#">
                                <span class="photo"></span>
                                <span class="subject">
                                    <span class="from">{{ $value->name }}</span>
                                    <span class="time"></span>
                                    </span>
                                <span class="message">{{ \Illuminate\Support\Str::limit($value->msg,20) }}</span>
                            </a>
                        </li>
                    @endforeach


                    <li>
                        <a href="{{ route('contact.index') }}">@lang('cms.show-all-messages')</a>
                    </li>
                </ul>
            </li>
            <!-- inbox dropdown end -->
    @endcan

    @can('notification-comment')
        <!-- notification dropdown start-->
            <li id="header_notification_bar" class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                    <i class="icon-comment"></i>
                    <span class="badge bg-warning">{{ $CountComment }}</span>
                </a>
                <ul class="dropdown-menu extended notification">
                    <div class="notify-arrow notify-arrow-yellow"></div>
                    <li>
                        <p class="yellow">@lang('cms.count-of-new-comments') {{ $CountComment }} </p>
                    </li>

                    @foreach($comments as $comment)
                        <li>
                            <a href="#">
                                <span class="label label-danger"><i class="icon-bolt"></i></span>
                                {{ $comment->user_id }} <span class="small italic"> {{ \Illuminate\Support\Str::limit($comment->comment,30) }} </span>
                            </a>
                        </li>
                    @endforeach


                    <li>
                        <a href="{{route('comments.index')}}">@lang('cms.show-all-comments') </a>
                    </li>
                </ul>
            </li>
            <!-- notification dropdown end -->
        @endcan
    </ul>
    <!--  notification end -->
</div>
<div class="top-nav ">
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
        <li>
            {{-- <input type="text" class="form-control search" placeholder="Search">--}}
        </li>
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                @if(isset(auth()->user()->image[0]) && !empty(auth()->user()->image[0]))
                    <img alt="" src="{{ Url(auth()->user()->image[0]->url) }}" height="30" width="30">
                @else
                    <img width="32" src="{{ url('admin_theme/img/noCustomer.svg')  }}" alt="">
                @endif
                <span class="username">{{ auth()->user()->name . " " . auth()->user()->family }}</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
                <div class="log-arrow-up"></div>
                <li><a href="{{route('profile.index')}}"><i class=" icon-suitcase"></i>@lang('cms.profile')</a></li>
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                    <li><a href="{{ route('panel.setting')  }}"><i class="icon-cog"></i> @lang('cms.setting')</a></li>
                @endif
                <li><a target="_blank" href="{{ route('site.index') }}"><i class="icon-home"></i> @lang('cms.show-site') </a></li>
                <li><a href="{{ route('logout') }}" >
                        <i class="icon-key"></i>@lang('cms.exit')</a>
                </li>
            </ul>
        </li>
        <!-- user login dropdown end -->
    </ul>
    <!--search & user info end-->

@can('notification-discount')
    <!--Discount dropdown start -->
        <ul class="nav pull-left top-menu">
            <!-- inbox dropdown start-->
            <li id="header_inbox_bar" class="dropdown" style="display: block;width: 70px;">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <i class="icon-chevron-down" style="font-size: 11px;margin-left: 5px;"></i>
                    <img src="{{ url('admin_theme/img/discount.png') }}" height="30" width="30">
                </a>
                <ul class="dropdown-menu extended inbox">
                    <li>
                        <p class="red">تخفیف های شما</p>
                    </li>
                    @if(isset($discounts) && count($discounts) > 0)
                        @foreach($discounts as $discount)
                            @if(count($discount->disable) > 0)
                                <li>
                                    <a href="#">
                                        <span class="photo"></span>
                                        <span class="subject">
                                        <span class="from">{{ $discount->title }}</span>
                                        <span class="time">
                                           {!! App\Utility\DiscountType::DiscountTypeShowDateAndCode($discount) !!}
                                        </span>
                                </span>
                                        <span class="message">{{ App\Utility\DiscountType::DiscountType($discount->type) }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>

            </li>
        </ul>
        <!--Discount dropdown end -->
    @endcan
</div>
