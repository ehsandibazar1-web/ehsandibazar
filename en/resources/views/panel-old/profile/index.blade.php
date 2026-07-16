@extends('panel-old.layout.master')
@section('title')
    @lang('cms.profile') | {{ $profile->name }} {{$profile->family}}

@endsection

@section('content')

    <div class="row">

        @include('panel-old.profile.partials.sidebar-profile')

        <aside class="profile-info col-lg-9">
            {{--<section class="panel">--}}
            {{--<form>--}}
            {{--<textarea placeholder="پیام خود را بنویسید..." rows="2" class="form-control input-lg p-text-area"></textarea>--}}
            {{--</form>--}}
            {{--<footer class="panel-footer">--}}
            {{--<input type="submit" class="btn btn-danger pull-right" value="ارسال پیام">--}}
            {{--<ul class="nav nav-pills">--}}
            {{--<li>--}}
            {{--<a href="#"><i class="icon-map-marker"></i></a>--}}
            {{--</li>--}}
            {{--<li>--}}
            {{--<a href="#"><i class="icon-camera"></i></a>--}}
            {{--</li>--}}
            {{--<li>--}}
            {{--<a href="#"><i class=" icon-film"></i></a>--}}
            {{--</li>--}}
            {{--<li>--}}
            {{--<a href="#"><i class="icon-microphone"></i></a>--}}
            {{--</li>--}}
            {{--</ul>--}}
            {{--</footer>--}}
            {{--</section>--}}
            <section class="panel">
                <div class="bio-graph-heading">
                    @lang('cms.your-profile')
                </div>
                <div class="panel-body bio-graph-info">
                    <h1>@lang('cms.biography')</h1>
                    <div class="row">
                        <div class="col-md-6">
                            <p><span> @lang('cms.name') </span>: {{ $profile->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><span> @lang('cms.family') </span>: {{ $profile->family }}</p>
                        </div>

                        <div class="col-md-6">
                            <p><span>@lang('cms.email') </span>: {{ $profile->email }}</p>
                        </div>

                        @if($profile->tell != "")
                            <div class="col-md-6">
                                <p><span>@lang('cms.tell') :</span>: {{ $profile->tell }}</p>
                            </div>
                        @endif

                        @if($profile->mobile != "")
                            <div class="col-md-6">
                                <p><span>@lang('cms.mobile') : </span>: {{ $profile->mobile }}</p>
                            </div>
                        @endif

                        @if($profile->postal_code != "")
                            <div class="col-md-6">
                                <p><span>@lang('cms.postal-code') : </span>: {{ $profile->postal_code }}</p>
                            </div>
                        @endif


                    </div>
                </div>
            </section>
        </aside>
    </div>

@endsection
