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
                                <h1 class="title-tab-content">Personal Information</h1>
                            </div>
                            <div class="content-section default">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <p>
                                            <span class="title">first name and last name :</span>
                                            <span>{{ $user->fullName }}</span>
                                        </p>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <p>
                                            <span class="title">email :</span>
                                            <span>{{ $user->email }}</span>
                                        </p>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <p>
                                            <span class="title">mobile:</span>
                                            <span>{{ $user->mobile }}</span>
                                        </p>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <p>
                                            <span class="title">national code :</span>
                                            <span>{{ !empty($user->national_code) ? $user->national_code : '-' }}</span>
                                        </p>
                                    </div>
                                    <div class="col-12 text-center">
                                        <a href="{{ route('users.panel.profile') }}"
                                           class="btn-link-border form-account-link">
                                            Edit personal information
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="col-12">
                                <h1 class="title-tab-content">List of latest favorites</h1>
                            </div>
                            <div class="content-section default">
                                <div class="row">
                                    @if(isset($favorites) && !empty($favorites) && count($favorites) > 0)
                                        <div class="col-12">
                                            @foreach($favorites as $favorite)
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
                                                        <a class="btn-action btn-action-remove" href="{{ route('users.panel.favorite.delete',$favorite->id) }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="col-12 text-center">
                                            <a href="{{ route('users.panel.favorite.index') }}"
                                               class="btn-link-border form-account-link">
                                                View and edit favorites list
                                            </a>
                                        </div>
                                    @else
                                        <div class="col-12 text-center">
                                            <a href="#" class="form-account-link">
                                                There are no products in your wishlist
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