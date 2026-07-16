@include('panel.layout.partials.header')

<div class="page-loader-wrapper">
    @include('panel.layout.partials.loading')
</div>

{{-- Overlay For Sidebars --}}
<div class="overlay"></div>
{{-- #Overlay For Sidebars --}}

{{-- Top Bar --}}
@yield('top-menu')
{{-- #Top Bar --}}

<div>
    {{-- Right Sidebar --}}
    @yield('right-menu')
    {{-- #Right Sidebar --}}
</div>

<section class="content">
    <div class="container-fluid">
       @yield('content')
    </div>
</section>

@yield('login')

@include('panel.layout.partials.footer')
