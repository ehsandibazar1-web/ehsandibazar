{{-- فازِ فریزِ legacy: این پنل در حالِ بازنشستگی است؛ قابلیتِ جدیدی به آن اضافه نمی‌شود. --}}
<div style="background:#b45309;color:#fff;text-align:center;padding:6px 12px;font-size:13px;">
    ⚠️ این پنل در حالِ انتقال به پنلِ جدید است — لطفاً کارهای روزمره را از
    <a href="{{ url('/adminpanel') }}" style="color:#fde68a;text-decoration:underline;">پنلِ جدید</a>
    انجام دهید. این پنل فقط برای موارِد باقی‌مانده نگه داشته شده.
</div>
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="#" onClick="return false;" class="navbar-toggle collapsed" data-toggle="collapse"
               data-target="#navbar-collapse"
               aria-expanded="false"></a>
            <a href="#" onClick="return false;" class="bars"></a>
            <a class="navbar-brand" href="{{ route('site.index')  }}">
                <!--@if(isset($logo) && !empty($logo))-->
                <!--    <img src="{{$logo->code5}}" alt="{{ env('SITE_NAME') }}" width="75"/>-->
                <!--@endif-->
                   <span class="logo-name" style="font-size: 18px;">{{ env('SITE_NAME_FA') }}</span>
            </a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="pull-right">
                <li>
                    <a href="#" onClick="return false;" class="sidemenu-collapse">
                        <i class="material-icons">reorder</i>
                    </a>
                </li>
            </ul>


            <ul class="nav navbar-nav navbar-right">
                <!-- Full Screen Button -->
                <li class="fullscreen">
                    <a href="javascript:;" class="fullscreen-btn">
                        <i class="fas fa-expand"></i>
                    </a>
                </li>
                <!-- #END# Full Screen Button -->
                <!-- #START# Notifications-->
            {{--                <li class="dropdown">--}}
            {{--                    <a href="#" onClick="return false;" class="dropdown-toggle" data-toggle="dropdown" role="button">--}}
            {{--                        <i class="fa fa-wallet"></i>--}}
            {{--                        <span class="label-count bg-orange"></span>--}}
            {{--                    </a>--}}
            {{--                    <ul class="dropdown-menu pullDown">--}}
            {{--                        <li class="header">کیف پول</li>--}}
            {{--                        <li class="body">--}}
            {{--                            <div class="row">--}}
            {{--                                <div class="col-md-12 text-center">--}}
            {{--                                    @if(auth()->user()->wallet == 0)--}}
            {{--                                        <p class="text-center"> کیف پول شما خالی می باشد</p>--}}
            {{--                                    @else--}}
            {{--                                        <p class="text-center">موجودی کیف پول--}}
            {{--                                            شما {{ number_format(auth()->user()->wallet)." تومان  " }} میباشد </p>--}}
            {{--                                    @endif--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </li>--}}
            {{--                    </ul>--}}
            {{--                </li>--}}
            <!-- #END# Notifications-->

                <!-- #END# Tasks -->

{{--                <li class="dropdown">--}}
{{--                    <a href="#" onclick="return false;" class="dropdown-toggle" data-toggle="dropdown" role="button">--}}
{{--                        <i class="far fa-bell"></i>--}}
{{--                        <span class="label-count bg-orange"></span>--}}
{{--                    </a>--}}
{{--                    <ul class="dropdown-menu pullDown">--}}
{{--                        <li class="header">پروفایل های در انتظار تایید</li>--}}
{{--                        <li class="body">--}}
{{--                            <div class="slimScrollDiv"--}}
{{--                                 style="position: relative; overflow: hidden; width: auto; height: 254px;">--}}
{{--                                <ul class="menu" style="overflow: scroll; width: auto; height: 254px;">--}}
{{--                                    @if(isset($userWating) && count($userWating) > 0)--}}
{{--                                        @foreach($userWating as $detail)--}}
{{--                                            <li>--}}
{{--                                                <a href="{{ route('panel.users.showDetail',$detail->user) }}">--}}
{{--                                            <span class="table-img msg-user">--}}
{{--                                                <img--}}
{{--                                                        src="{{ isset($detail->user->image[0]) ? $detail->user->image[0]->url : url('admin//assets/images/default/noCustomer.svg')  }}"--}}
{{--                                                        alt="">--}}
{{--                                            </span>--}}
{{--                                                    <span class="menu-info">--}}
{{--                                                <span class="menu-title">{{ $detail->user->fullName }}</span>--}}
{{--                                                <span class="menu-desc">--}}
{{--                                                    <i class="material-icons">access_time</i>{{Verta($detail->updated_at)->formatDifference(Verta())}}--}}
{{--                                                </span>--}}
{{--                                                <span class="menu-desc">--}}
{{--                                                    @if(isset($detail->changes) && count($detail->changes) > 0)--}}
{{--                                                        @foreach($detail->changes as $column => $value)--}}
{{--                                                            {{\Illuminate\Support\Facades\Lang::get('cms.'.$column)}},--}}
{{--                                                        @endforeach--}}
{{--                                                    @endif--}}
{{--                                                </span>--}}
{{--                                            </span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}
{{--                                </ul>--}}
{{--                                <div class="slimScrollBar"--}}
{{--                                     style="background: rgba(0, 0, 0, 0.5); width: 4px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 0px; z-index: 99; right: 1px;"></div>--}}
{{--                                <div class="slimScrollRail"--}}
{{--                                     style="width: 4px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="footer">--}}
{{--                            <a href="{{ route('panel.users.index') }}">نمایش بیشتر</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
                <li class="dropdown user_profile">
                    <a href="#" onClick="return false;" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <img
                                src="{{ isset(auth()->user()->image[0]) ? auth()->user()->image[0]->url : url('admin//assets/images/default/noCustomer.svg')  }}"
                                width="32" height="32" alt="User">
                    </a>
                    <ul class="dropdown-menu pullDown">
                        <li class="body">
                            <ul class="user_dw_menu">
                                <li>
                                    <a href="{{ route('panel.profile.index')  }}">
                                        <i class="material-icons">person</i>پروفایل کاربری
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="{{ route('site.index') }}">
                                        <i class="material-icons">language</i>نمایش سایت
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout')  }}">
                                        <i class="material-icons">power_settings_new</i>خروج
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="pull-right">
                    <a href="#" onClick="return false;" class="js-right-sidebar" data-close="true">
                        {{--<i class="fas fa-cog"></i>--}}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
