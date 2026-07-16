@include('panel-old.layout.partials.header')

<section id="container" class="">
    <!--header start-->
    <header class="header white-bg">
        @include('panel-old.layout.partials.top-menu')
    </header>
    <!--header end-->
    <!--sidebar start-->
    <aside>
        <div id="sidebar" class="nav-collapse ">
            @include('panel-old.layout.partials.sidebar')
        </div>
    </aside>
    <!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            @yield('content')
        </section>
    </section>
    <!--main content end-->
</section>

@include('panel-old.layout.partials.footer')
