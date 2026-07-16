<div class="uk-width-1-4@m ">
    <div class="dashboard-nav ">
        <ul class="uk-nav uk-nav-default uk-align-right">
            <li class="{{ \App\Utility\ActiveMenu::ActiveMenuUserArea(['user']) }}"><a
                    href="{{ route('users.dashboard.index') }}"><span uk-icon="icon: home"></span> داشبورد</a></li>
            <li class="{{ \App\Utility\ActiveMenu::ActiveMenuUserArea(['favorites']) }}"><a
                    href="{{ route('users.panel.favorite.index') }}"><span uk-icon="icon: heart"></span> علاقه مندی ها
                </a></li>
            <li class="{{ \App\Utility\ActiveMenu::ActiveMenuUserArea(['orders']) }}"><a
                    href="{{ route('users.panel.order.index') }}" class="uk-active"><span uk-icon="icon: cart"></span>
                    سفارشات </a></li>
            <li><a href="{{ route('users.panel.book') }}" class="uk-active"><span
                        uk-icon="icon:list"></span>&nbsp; محصولات دیجیتال</a></li>
            <li class="{{ \App\Utility\ActiveMenu::ActiveMenuUserArea(['profile']) }}"><a
                    href="{{ route('user-profile.index') }}"><span uk-icon="icon: user"></span> پروفایل </a></li>
            <li class="{{ \App\Utility\ActiveMenu::ActiveMenuUserArea(['address']) }}"><a
                    href="{{ route('users.panel.address') }}"><span
                        uk-icon="icon: location"></span> آدرس ها </a></li>
            <li><a href="{{ route('logout') }}"><span uk-icon="icon: close"></span> خروج</a></li>
        </ul>
    </div>
</div>
