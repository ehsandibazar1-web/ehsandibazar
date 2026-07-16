@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/internal/style.css"/>
@endsection
@section('content')
   
    <div class="container-fluid white-bg wrapper default">


        @if(isset($contactUs))
            <div class="container contact-us-block">
                <div class="contact-us-details">
                    {!! $contactUs->code4 !!}
                </div>
            </div>
    @endif
    <!-- nykaa access menus  -->

        <!-- help category section -->
        <div class="container help-category">
                    @if(isset($map))
            <div class="help-category-header">
                <h5>Our position</h5>
            </div>
                                @endif

            <div class="help-category-body">
                <div class="row">
                    @if(isset($map))
                        <div class="col-12">
                            {!! $map->code4 !!}
                        </div>
                    @endif
                </div>
{{--                @auth()--}}
                    <form method="post" action="{{ route('site.contactUs.save') }}">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">first name and last name</label>
                            <input type="text" name="name" placeholder="first name and last name ..."
                                   value="{{ old('name') }}" class="form-control">
                            @if($errors->has('name'))
                                <span class="text-danger f-size"> {{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">email</label>
                            <input type="email" name="email"
                                   placeholder="Enter your e-mail ..."
                                   value="{{ old('email') }}" class="form-control">
                            @if($errors->has('email'))
                                <span class="text-danger f-size"> {{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Message</label>
                            <textarea class="form-control" name="body" id="" cols="30" rows="10"
                                      placeholder="Message ...">{{ old('body') }}</textarea>
                            @if($errors->has('body'))
                                <span class="text-danger f-size"> {{ $errors->first('body') }}</span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">send</button>
                    </form>
{{--                @else--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-12">--}}
{{--                            <div class="alert alert-info">--}}
{{--                                 برای ارسال پیام ، انتقاد یا پیشنهاد ابتدا--}}
{{--                                <a href="{{ route('login') }}">وارد شوید</a></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endauth--}}
            </div>
        </div>
        <!-- contact us information -->


    </div>
    <!--Contact Us Page-->
@endsection
