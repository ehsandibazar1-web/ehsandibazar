@extends('site.layout.master')
@section('site.css')
    @include('site.users.partials.user-style-area')
@endsection
@section('content')
    <section class="page-section account-page">
        <div class="uk-container uk-containcer-center uk-margin-large-top uk-margin-large-bottom">

            <div class="uk-grid" uk-grid>
                @include('site.users.partials.menu')
                <div class="uk-width-3-4@m uk-background-muted">
                    @include('generals.allErrors')
                    <form id="addressform" class="inputform" method="post"
                          action="{{ route('user-profile.update', $profile->id) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <h5>User information</h5>
                        <div class="uk-margin">
                            <input class="uk-input" type="text"
                                   value="{{ !empty($profile->name) ? $profile->name : null  }}" name="name"
                                   placeholder="name" >
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="text"
                                   value="{{ !empty($profile->family) ? $profile->family : null }}" name="family" placeholder="family">
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="number"
                                   value="{{ !empty($profile->tell) ? $profile->tell : null }}" name="tell"
                                   placeholder="tell">
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="number"
                                   value="{{ !empty($profile->mobile) ? $profile->mobile : null }}" name="mobile"
                                   placeholder="mobile" disabled="disabled">
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="email"
                                   value="{{ !empty($profile->email) ? $profile->email : null }}" name="email"
                                   placeholder="email">
                        </div>
                        @if(auth()->user()->isColleague())

                            <div class="uk-margin">
                                <input class="uk-input" type="number"
                                       value="{{ !empty($profile->national_code) ? $profile->national_code : null }}" name="national_code"
                                       placeholder="national code" disabled="disabled">
                            </div>

                            <div class="uk-margin">
                                <input class="uk-input" type="text"
                                       value="{{ !empty($profile->economic_code) ? $profile->economic_code : null }}" name="economic_code"
                                       placeholder="" disabled="disabled">
                            </div>

                            <div class="uk-margin">
                                <input class="uk-input" type="text"
                                       value="{{ !empty($profile->full_address) ? $profile->full_address : null }}" name="full_address"
                                       placeholder="address ...">
                            </div>
                        @endif

                        <button type="submit" class="uk-button uk-button-danger">Save</button>
                    </form>
                    <hr>

                    <form id="addressform" class="inputform" method="post"
                          action="{{ route('users.change.password') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="uk-margin">
                            <h5>change Password</h5>
                            <input class="uk-input" type="password" name="current-password"
                                   placeholder="Enter the current password" required>
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="password" name="new-password"
                                   placeholder="Enter the new password" required>
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="password" name="new-password_confirmation"
                                   placeholder="Repeat the new password" required>
                        </div>

                        <button type="submit" class="uk-button uk-button-danger">Send</button>
                    </form>
                </div>
            </div>

        </div>
    </section>
@endsection
