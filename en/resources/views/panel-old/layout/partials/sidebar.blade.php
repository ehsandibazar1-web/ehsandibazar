<!-- sidebar menu start-->
<ul class="sidebar-menu">
    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["manager"],1) == true ) class="menu_item_active active"
            @endif >
        <a href="{{ route('panel.dashboard.index') }}">
            <i class="icon-dashboard"></i>
            <span>@lang('cms.main-page')</span>
        </a>
    </li>

    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["profile"],1) == true ) class="menu_item_active active" @endif>
        <a href="{{ route('profile.index')  }}">
            <i class="icon-user"></i>
            <span> پروفایل من </span>
        </a>
    </li>


    {{--    <li class="">--}}
    {{--        <a class=""  href="{{ route('panel.auction.index')  }}">--}}
    {{--            <i class="icon-gift"></i>--}}
    {{--            <span>مزایده های من</span>--}}
    {{--        </a>--}}
    {{--    </li>--}}


    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["favorite"],1) == true ) class="menu_item_active active" @endif>
        <a href="{{ route('panel.favorite.index') }}">
            <i class="icon-heart"></i>
            <span>@lang('cms.favorite-list')</span>
        </a>
    </li>

    {{--    @can('requests')--}}
    {{--        <li class="sub-menu">--}}
    {{--            <a href="javascript:;" class="">--}}
    {{--                <i class="icon-pencil"></i>--}}
    {{--                <span>@lang('cms.requests')</span>--}}
    {{--                <span class="arrow"></span>--}}
    {{--            </a>--}}
    {{--            <ul class="sub" style="display: none;">--}}
    {{--                @can('requests-products')--}}
    {{--                    <li><a class="" href="{{ route('panel.request.product.create') }}">@lang('cms.request-product')</a>--}}
    {{--                    </li>--}}
    {{--                @endcan--}}
    {{--                @can('requests-list')--}}
    {{--                    <li><a class="" href="{{ route('panel.request.product') }}">@lang('cms.list-product-request')</a>--}}
    {{--                    </li>--}}
    {{--                @endcan--}}
    {{--            </ul>--}}
    {{--        </li>--}}
    {{--    @endcan--}}

    @can('articles')
        <li class="sub-menu  {{ \App\Utility\ActiveMenu::ActiveMenu(["category","article"])  }}">
            <a href="javascript:void(0)" class="">
                <i class="icon-tasks"></i>
                <span>@lang('cms.article')</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub" {{ \App\Utility\ActiveMenu::ActiveMenu(["category","article"],1) != true ? 'style="display: none;"' : null  }}>
                @can('article-category')
                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["category"],1) == true ) class="menu_item_active active" @endif >
                        <a class="" href="{{ route('panel.category.index') }}">@lang('cms.category')</a></li>
                @endcan
                @can('article')
                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["article"],1) == true ) class="menu_item_active active" @endif>
                        <a class="" href="{{ route('panel.article.index') }}">@lang('cms.list-article')</a></li>
                @endcan
            </ul>
        </li>
    @endcan

    @can('comments')
        <li @if(\App\Utility\ActiveMenu::ActiveMenu(["comments"],1) == true ) class="menu_item_active active" @endif>
            <a class="" href="{{ route('comments.index') }}">
                <i class="icon-comment"></i>
                <span>@lang('cms.comments')</span>
                <span class="label label-danger pull-left mail-info">{{ $CountComment }}</span>
            </a>
        </li>
    @endcan
    @can('users')
        <li @if(\App\Utility\ActiveMenu::ActiveMenu(["users"],1) == true ) class="menu_item_active active" @endif>
            <a class="" href="{{ route('panel.users.index') }}">
                <i class="icon-user-md"></i>
                <span> @lang('cms.list-users')</span>
            </a>
        </li>
    @endcan

    @can('digital-product')
    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["digital-product"],1) == true ) class="menu_item_active active" @endif>
        <a class="" href="{{ route('panel.digitalProduct.index') }}">
            <i class="icon-circle"></i>
            <span>محصولات دیجیتال </span>
        </a>
    </li>
    @endcan

    @can('attribute')
        <li class="sub-menu {{ \App\Utility\ActiveMenu::ActiveMenu(["attribute-group","attribute","attribute-type","attribute-type-value"])  }}">
            <a href="javascript:void(0)" class="">
                <i class="icon-archive"></i>
                <span>@lang('cms.attribute')</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub" {{ \App\Utility\ActiveMenu::ActiveMenu(["attribute-group","attribute","attribute-type","attribute-type-value"],1) != true ? 'style="display: none;"' : null  }}>
                @can('attribute-group')
                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["attribute-group"],1) == true ) class="menu_item_active active" @endif>
                        <a class="" href="{{route('panel.attributeGroup.index')}}">@lang('cms.attribute-category')</a>
                    </li>@endcan
                @can('attribute')
                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["attribute"],1) == true ) class="menu_item_active active" @endif>
                        <a class="" href="{{ route('panel.attribute.index')  }}">@lang('cms.list-attributes')</a>
                    </li>@endcan
                {{--                @can('attribute-type')--}}
                {{--                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["attribute-type"],1) == true ) class="menu_item_active active" @endif>--}}
                {{--                        <a class="attribute-type-font-size"--}}
                {{--                           href="{{route('panel.attribute-type.index')}}">@lang('cms.category-multi-attribute')</a>--}}
                {{--                    </li>@endcan--}}
                {{--                @can('attribute-type-value')--}}
                {{--                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["attribute-type-value"],1) == true ) class="menu_item_active active" @endif>--}}
                {{--                        <a class=""--}}
                {{--                           href="{{route('panel.attribute-type-value.index')}}">@lang('cms.list-multi-category')</a>--}}
                {{--                    </li>@endcan--}}
            </ul>
        </li>
    @endcan

    @can('products')
        <li class="sub-menu {{ \App\Utility\ActiveMenu::ActiveMenu(["product","brand","category-product"])  }}">
            <a href="javascript:void(0)" class="">
                <i class="icon-archive"></i>
                <span>@lang('cms.products')</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub" {{ \App\Utility\ActiveMenu::ActiveMenu(["product"],1) == true ? 'style="display: none;"' : null  }}>
                @can('category-product')
                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["category-product"],1) == true ) class="menu_item_active active" @endif>
                        <a href="{{route('panel.categoryProduct.index')}}">@lang('cms.category')</a>
                    </li>@endcan
                @can('brand')
                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["brand"],1) == true ) class="menu_item_active active" @endif>
                        <a href="{{route('panel.brand.index')}}">@lang('cms.brand')</a>
                    </li>@endcan
                @can('product')
                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["product"],1) == true ) class="menu_item_active active" @endif>
                        <a href="{{route('panel.product.index')}}">@lang('cms.products')</a>
                    </li>@endcan
            </ul>
        </li>
    @endcan


    @can('order')
        <li class="sub-menu  {{ \App\Utility\ActiveMenu::ActiveMenu(["orders","order-sending"])  }}">
            <a href="javascript:void(0)" class="">
                <i class="icon-euro"></i>
                <span>سفارشات</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub" {{ \App\Utility\ActiveMenu::ActiveMenu(["orders","order-sending"],1) != true ? 'style="display: none;"' : null  }}>

                <li @if(\App\Utility\ActiveMenu::ActiveMenu(["orders"],1) == true ) class="menu_item_active active" @endif>
                    <a class="" href="{{ route('panel.order.index')}}">@lang('cms.list-order')</a></li>

                <li @if(\App\Utility\ActiveMenu::ActiveMenu(["order-sending"],1) == true ) class="menu_item_active active" @endif>
                    <a class="" href="{{ route('panel.order.sending') }}">ارسال شده </a></li>

                <li @if(\App\Utility\ActiveMenu::ActiveMenu(["order-canceled"],1) == true ) class="menu_item_active active" @endif>
                    <a class="" href="{{ route('panel.order.canceled') }}"> لغو شده</a></li>

                <li @if(\App\Utility\ActiveMenu::ActiveMenu(["order-unpaid"],1) == true ) class="menu_item_active active" @endif>
                    <a class="" href="{{ route('panel.order.unpaid') }}"> پرداخت نشده</a></li>

                <li @if(\App\Utility\ActiveMenu::ActiveMenu(["order-pending"],1) == true ) class="menu_item_active active" @endif>
                    <a class="" href="{{ route('panel.order.pending') }}"> درحال پردازش</a></li>

            </ul>
        </li>
    @endcan


    @can('reporting')
        <li @if(\App\Utility\ActiveMenu::ActiveMenu(["reporting"],1) == true ) class="menu_item_active active" @endif>
            <a class="" href="{{ route('panel.reporting.index')}}">
                <i class="icon-repeat"></i>
                <span>@lang('cms.reporting')</span>
            </a>
        </li>
    @endcan


    @can('discount')
        <li @if(\App\Utility\ActiveMenu::ActiveMenu(["discount"],1) == true ) class="menu_item_active active" @endif>
            <a class="" href="{{ route('panel.discount.index') }}">
                <i class="icon-dollar"></i>
                <span>@lang('cms.discounts')</span>
            </a>
        </li>
    @endcan
    @can('tags')
        <li @if(\App\Utility\ActiveMenu::ActiveMenu(["tag"],1) == true ) class="menu_item_active active" @endif>
            <a class="" href="{{route('panel.tag.index')}}">
                <i class="icon-tag"></i>
                <span>مدیریت برچسب</span>
                <span class="label label-danger pull-right mail-info"></span>
            </a>
        </li>
    @endcan

    @can('settings')
        <li class="sub-menu {{ \App\Utility\ActiveMenu::ActiveMenu(["menu","setting","shippingCost"])  }}">
            <a href="javascript:void(0))" class="">
                <i class="icon-cogs"></i>
                <span>@lang('cms.setting')</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub" {{ \App\Utility\ActiveMenu::ActiveMenu(["menu","setting","shippingCost"],1) == true ? 'style="display: none;"' : null  }}>
                @can('menu')
                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["menu"],1) == true ) class="menu_item_active active" @endif>
                        <a class="" href="{{ route('menu.index') }}">@lang('cms.menus')</a></li>@endcan
                @can('setting')
                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["setting"],1) == true ) class="menu_item_active active" @endif>
                        <a class="" href="{{ route('panel.setting') }}">@lang('cms.general-settings')</a></li>@endcan

                @can('shipping-cost')
                    <li @if(\App\Utility\ActiveMenu::ActiveMenu(["shippingCost"],1) == true ) class="menu_item_active active" @endif>
                        <a class="" href="{{ route('panel.shippingCost.index') }}">هزینه های ارسال</a></li>@endcan

            </ul>
        </li>
    @endcan
    @can('newsletters')
        <li class="sub-menu {{ \App\Utility\ActiveMenu::ActiveMenu(["newslatters","sends"])  }}">
            <a href="javascript:void(0)" class="">
                <i class="icon-mail-forward"></i>
                <span>@lang('cms.newsletters')</span>
                <span class="arrow"></span>
                <span class="label label-danger pull-left mail-info">{{ $countAllNewsLatters  }}</span>
            </a>

            <ul class="sub" {{ \App\Utility\ActiveMenu::ActiveMenu(["newslatters","sends"],1) == true ? 'style="display: none;"' : null  }} >
                <li @if(\App\Utility\ActiveMenu::ActiveMenu(["newslatters"],1) == true ) class="menu_item_active active" @endif>
                    <a class="" href="{{ route('newslatters.index') }}">@lang('cms.list-email')</a></li>
                <li @if(\App\Utility\ActiveMenu::ActiveMenu(["sends"],1) == true ) class="menu_item_active active" @endif>
                    <a class="" href="{{ route('panel.newsLatter.sends') }}">@lang('cms.send-email')</a></li>
            </ul>
        </li>
    @endcan

    @can('pages')
        <li @if(\App\Utility\ActiveMenu::ActiveMenu(["page"],1) == true ) class="menu_item_active active" @endif>
            <a class="" href="{{ route('page.index') }}">
                <i class="icon-folder-close"></i>
                <span>@lang('cms.pages')</span>
            </a>
        </li>
    @endcan





    @can('contact')
        <li @if(\App\Utility\ActiveMenu::ActiveMenu(["contact"],1) == true ) class="menu_item_active active" @endif>
            <a class="" href="{{ route('contact.index') }}">
                <i class="icon-envelope"></i>
                <span>@lang('cms.messages')</span>
                <span class="label label-danger pull-left mail-info">{{ $CountContact }}</span>
            </a>
        </li>
    @endcan

</ul>
<!-- sidebar menu end-->
