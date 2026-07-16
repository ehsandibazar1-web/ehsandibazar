<ul class="breadcrumb breadcrumb-style ">
    <li class="breadcrumb-item">
        <h4 class="page-title"> {{ isset($title) ? $title : "" }} </h4>
    </li>
    @if (isset($route) && !empty($route))
        <li class="breadcrumb-item bcrumb-1">
            <a href="{{ route('panel.dashboard.index')  }}">
                <i class="fas fa-home"></i> داشبورد </a>
        </li>
        @foreach($route as $itemRoute)
            <li class="breadcrumb-item"> {{ $itemRoute  }} </li>
            @if ($loop->last)
                <li class="breadcrumb-item active"> {{ $itemRoute  }} </li>
            @endif
        @endforeach

        @else
        <li class="breadcrumb-item bcrumb-1">
            <a href="{{ route('panel.dashboard.index')  }}">
                <i class="fas fa-home"></i> داشبورد </a>
        </li>
    @endif

</ul>