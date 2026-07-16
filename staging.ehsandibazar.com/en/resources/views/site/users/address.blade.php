@extends('site.layout.master')
@section('site.css')
    @include('site.users.partials.user-style-area')
       <script>
    function text(name){
        var str = $(name).val();
        just_persian(str,name);
        
    }
    
    function just_persian(str,name){
        var p = /^[\u0600-\u06FF\s0-9()،,-+]+$/;
        if(!p.test(str)){
              $(name).val("");
        }
        return true;
    }
</script>
@endsection
@section('content')
    <section class="page-section account-page">
        <div class="uk-container uk-containcer-center uk-margin-large-top uk-margin-large-bottom">
            <div class="uk-grid" uk-grid>
                @include('site.users.partials.menu')
                <div class="uk-width-3-4@m uk-background-muted uk-padding	">
                    <div class="addresslist">
                        @if(isset($address) && count($address) > 0)
                            <h4>آدرس ها</h4>
                            <div class="uk-child-width-1-2@s uk-padding" uk-grid>
                                @foreach($address as $item)
                                    <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-width-1-2@m">
                                        <div class="uk-card-badge">
                                            <a class="uk-label-danger uk-padding-small"
                                               uk-toggle="target: #modal-id{{ $item->id }}" uk-icon="icon: trash"></a>
                                        </div>
                                        <h3 class="uk-card-title">{{ $item->name }}</h3>
                                        <p><span> {{ $item->province->name }}</span>,
                                            <span>{{ $item->city->name }}</span> ,<span> {{ $item->fullAddress }}</span>
                                        </p>
                                        <p>موبایل : {{ $item->mobile }} - {{ $item->tell }}</p>
                                        <p>کد پستی : {{ $item->postal_code }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @if(isset($address) && count($address) > 0)
                    @foreach($address as $item)
                        <!-- This is the modal -->
                            <div id="modal-id{{ $item->id }}" uk-modal>
                                <div class="uk-modal-dialog uk-modal-body">
                                    <button class="uk-modal-close-default" type="button" uk-close></button>
                                    <div class="last-activity">
                                        <p>آیا از حذف این آدرس مطمئن هستید ؟</p>
                                    </div>
                                    <form action="{{ route('users.delete.address',$item->id) }}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <br>
                                        <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                               class="uk-button-danger uk-button">
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        <hr>
                    @endif

                    <form id="addressform" class="inputform" method="post"
                          action="{{ route('users.profile.add.address' , ['id' =>$profile->id]) }}"
                          enctype="multipart/form-data">
                        @include('generals.allErrors')
                        @csrf
                        <h4>افزودن آدرس جدید</h4>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-select">@lang('cms.state')</label>
                            <div class="uk-form-controls">
                                <select name="province_id" class="uk-select province" id="form-stacked-select">
                                    <option>@lang('cms.choose-state')</option>
                                    @foreach(\App\Model\Province::all() as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="uk-margin" id="result-ajax">
                            <div class="uk-form-controls">
                                <select name="city_id" class="uk-select" id="form-stacked-select city">
                                    <option value="">@lang('cms.choose-city')</option>
                                </select>
                            </div>
                        </div>

                        <div class="uk-margin">
                            <input class="uk-input" type="text" placeholder="آدرس" name="fullAddress" onkeypress="text(this)" onchange="text(this)" keyup="text(this)" keydown="text(this)"
                                   value="{{ old('fullAddress') }}">
                        </div>

                        <div class="uk-margin">
                            <input class="uk-input" type="text" name="name" value="{{ old('name') }}" onkeypress="text(this)" onchange="text(this)" keyup="text(this)" keydown="text(this)"
                                   placeholder="نام و نام خانوادگی">
                        </div>

                        <div class="uk-margin">
                            <input class="uk-input" type="number" name="mobile" value="{{ old('mobile') }}"
                                   placeholder="تلفن همراه یا موبایل">
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="number" name="tell" value="{{ old('tell') }}"
                                   placeholder="تلفن ثابت">
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="text" name="postal_code" value="{{ old('postal_code') }}"
                                   placeholder="کد پستی منزل یا محل کار">
                        </div>


                        <button type="submit" class="uk-button-danger uk-button">افزودن آدرس جدید</button>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
@section('site-js')
    <script>
        $('.province').change(function (e) {
            var province_id = $(this).val();
            e.preventDefault();
            /* start ajax */
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: "post",
                url: "{{route('users.panel.profile.ajaxCity')}}",

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
