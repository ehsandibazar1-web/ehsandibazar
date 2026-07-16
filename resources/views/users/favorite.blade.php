@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
@endsection
@section('content')
    <main class="profile-user-page default">
        <div class="container wrapper default">
            <div class="row">
                <div class="profile-page col-xl-9 col-lg-8 col-md-12 order-2">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-12">
                                <h1 class="title-tab-content">لیست علاقمندی ها</h1>
                            </div>
                            <div class="content-section default">
                                <div class="row">
                                    @if(isset($favorites) && !empty($favorites) && count($favorites) > 0)
                                        @foreach($favorites as $favorite)
                                            <div class="col-md-6 col-sm-12">
                                                <div class="profile-recent-fav-row">
                                                    <a href="#"
                                                       class="profile-recent-fav-col profile-recent-fav-col-thumb">
                                                        @if(isset($favorite->favoriteable->image[0]) && !empty($favorite->favoriteable->image[0]))
                                                            <img src="{{ $favorite->favoriteable->image[0]->url }}"
                                                                 alt="{{ $favorite->favoriteable->title }}">                                                        @endif
                                                    </a>
                                                    <div class="profile-recent-fav-col profile-recent-fav-col-title">
                                                        <a href="{{ $favorite->favoriteable->path() }}" target="_blank">
                                                            <h4 class="profile-recent-fav-name">
                                                                {{ $favorite->favoriteable->title }}
                                                            </h4>
                                                        </a>
                                                        <div class="profile-recent-fav-price">
                                                            {!! $favorite->favoriteable->prices !!}
                                                        </div>
                                                    </div>
                                                    <div class="profile-recent-fav-col profile-recent-fav-col-actions">
                                                        <a class="btn-action btn-action-remove"
                                                           href="{{ route('users.panel.favorite.delete',$favorite->id) }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12 text-center">
                                            <a href="#" class="form-account-link">
                                                محصولی در لیست علاقه مندی شما وجود ندارد
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('users.layouts.partials.aside-menu')
            </div>
        </div>
    </main>
@endsection
