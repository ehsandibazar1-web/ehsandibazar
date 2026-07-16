@extends('panel-old.layout.master')
{{--@section('title')
    پنل | ویرایش پروفایل
@endsection--}}
@section('content')
    <div class="row">
        @include('panel-old.profile.partials.sidebar-profile')
        <aside class="profile-info col-lg-9">
            <section class="panel">
                <div class="bio-graph-heading">
                    @lang('cms.update-information')
                </div>
                <div class="panel-body bio-graph-info">
                    <h1>@lang('cms.profile-info')</h1>
                    @include('generals.allErrors')
                    <form class="form-horizontal" role="form" method="post"
                          action="{{ route('profile.update', $profile->id) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PATCH">


                        <div class="form-group">
                            <label class="col-lg-2 control-label">@lang('cms.name')</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="f-name"
                                       value="{{ !empty($profile->name) ? $profile->name : null  }}" name="name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">@lang('cms.family')</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="f-name"
                                       value="{{ !empty($profile->family) ? $profile->family : null }}" name="family">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-2 control-label">@lang('cms.tell')</label>
                            <div class="col-lg-6">
                                <input type="number" class="form-control" id="email"
                                       value="{{ !empty($profile->tell) ? $profile->tell : null }}" name="tell">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">@lang('cms.email')</label>
                            <div class="col-lg-6">
                                <input type="email" class="form-control" id="email"
                                       value="{{ !empty($profile->email) ? $profile->email : null }}" name="email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">@lang('cms.mobile')</label>
                            <div class="col-lg-6">
                                <input type="number" class="form-control" id="email"
                                       value="{{ !empty($profile->mobile) ? $profile->mobile : null }}" name="mobile" disabled="disabled">
                            </div>
                        </div>

                        @if(auth()->user()->level == "seller")

                            {{-- store name --}}
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.name-of-store')</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="email"
                                           value="{{ isset($details) && !empty($details->store_name) ? $details->store_name : null }}"
                                           name="store_name">
                                </div>
                            </div>


                            {{-- sheba number --}}
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.sheba-number')</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="email"
                                           value="{{ isset($details) && !empty($details->sheba_number) ? $details->sheba_number : null }}"
                                           name="sheba_number">
                                </div>
                            </div>


                            {{-- 	account_number  --}}
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.number-account')</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="email"
                                           value="{{ isset($details) && !empty($details->account_number) ? $details->account_number : null }}"
                                           name="account_number">
                                </div>
                            </div>


                            {{-- 	cart_number  --}}
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('number-cart')</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="email"
                                           value="{{ isset($details) && !empty($details->cart_number) ? $details->cart_number : null }}"
                                           name="cart_number">
                                </div>
                            </div>


                            {{-- national_code --}}
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.code-melli')</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="email"
                                           value="{{ isset($details) && !empty($details->national_code) ? $details->national_code : null }}"
                                           name="national_code">
                                </div>
                            </div>

                            {{-- postal_code --}}
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.postal-cod')</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="email"
                                           value="{{ isset($details) && !empty($details->postal_code) ? $details->postal_code : null }}"
                                           name="postal_code">
                                </div>
                            </div>

                        @endif



                        {{-- image --}}
                        <div class="form-group ">
                            <label for="images" class="control-label col-lg-2">@lang('cms.profile-pic')
                            </label>
                            <div class="col-md-6">
                                <div class="input-group">
                                                  <span class="input-group-btn">
                                                    <a id="" data-input="thumbnail{{$profile->id}}" data-preview="holder{{$profile->id}}"
                                                       class="btn btn-primary lfm1">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>
                                    <input id="thumbnail{{$profile->id}}" class="form-control" type="text"
                                           value="{{ isset($profile->image[0]) && !empty($profile->image[0]->url) && file_exists(base_path().$profile->image[0]->url) ? $profile->image[0]->url : null}}"
                                           name="filepath">
                                </div>

                                @if (isset($profile) && !empty($profile))
                                    @if(isset($profile->image[0]) && !empty($profile->image[0]->url))
                                        @if(file_exists(base_path().$profile->image[0]->url))
                                <img id="holder{{$profile->id}}"
                                     src="{{isset($profile) && !empty($profile->image) ? url($profile->image[0]->url) : null}}"
                                     style="margin-top:15px;max-height:100px;">
                                <br>
                                        @endif
                                    @endif
                                @endif

                                <img id="holder{{$profile->id}}" style="margin-top:15px;max-height:100px;">

                                <br>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-success">@lang('cms.save')</button>
                                <button type="button" class="btn btn-default">@lang('cms.cancel')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            <section>
                <div class="panel panel-primary">
                    <div class="panel-heading">تغییر گذارواژه</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="post" action="{{ route('panel.change.password') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="col-lg-2 control-label">گذرواژه فعلی</label>
                                <div class="col-lg-6">
                                    <input type="password" class="form-control" id="c-pwd" name="current-password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">گذرواژه جدید</label>
                                <div class="col-lg-6">
                                    <input type="password" class="form-control" id="n-pwd" name="new-password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">تکرار گذرواژه جدید</label>
                                <div class="col-lg-6">
                                    <input type="password" class="form-control" id="rt-pwd" name="new-password_confirmation"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button type="submit" class="btn btn-info">ارسال</button>
                                    <button type="button" class="btn btn-default">انصراف</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </aside>
    </div>

@endsection

@section('admin-js')

    <script>


        $('#province').change(function (e) {
            var province_id = $(this).val();
            //  alert(province_id);
            e.preventDefault();
            /* start ajax */
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: "post",
                url: "{{route('panel.profile.ajaxCity')}}",

                data: {isRequestIDChange: province_id, _token: CSRF_TOKEN},
                success: function (data) {
                    $('#result-ajax').html(data.html);
                },
                error: function (error) {
                    //alert(error);
                    alert("لطفا چند لحظه دیگر امتحان نمایید")
                }
            });
            /* end ajax */
        });

    </script>
@endsection

