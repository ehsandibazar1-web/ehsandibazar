<aside class="profile-nav col-lg-3">
    <section class="panel">
        <div class="user-heading round">
            <a href="#">
                @if(isset(auth()->user()->image[0]) && !empty(auth()->user()->image[0]))
                    <img alt="" src="{{ Url(auth()->user()->image[0]->url) }}" height="30" width="30">
                @else
                    <img width="32" src="{{ url('admin_theme/img/noCustomer.svg')  }}" alt="">
                @endif
            </a>
            <h1>{{ $profile->name }} {{ $profile->family }}</h1>
            <p>{{ $profile->email }}</p>
{{--            <p>موجودی کیف پول : {{ $profile->wallet  > 0 ? number_format( $profile->wallet)." تومان " : "خالی میباشد" }}</p>--}}

        </div>

        <ul class="nav nav-pills nav-stacked">
            <li class="active"><a href="{{ route('profile.index') }}"><i class="icon-user"></i>@lang('cms.profile')</a></li>
            <li><a href="{{ route('profile.edit',[ 'id' => $profile->id ]) }}"><i class="icon-edit"></i>@lang('cms.edit-profile')</a></li>
            <li><a href="{{ route('profile.show',['id' => $profile->id ]) }}"><i class="icon-location-arrow"></i>@lang('cms.address')</a></li>
{{--            <li><a href="{{ route('profile.changePw' , ['id' => $profile->id ]) }}"><i class="icon-key"></i>@lang('cms.change-password')</a>--}}
        </ul>

    </section>
</aside>
