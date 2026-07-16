@extends('panel-old.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   @lang('cms.header-email-sms')
                </header>
            </section>
        </div>
    </div>
    <!-- /.modal insert -->


    @include('generals.allErrors')
    @include('generals.sessionMessage')

    <div class="row">
        <div class="col-md-12 ">

            {{-- start email --}}
            <div class="col-md-6 well col-md-offset-3">
                <h2 class="text-center"> @lang('cms.email-panel') </h2>
                <div>
                    <form method="post" action="{{route('panel.newsLatter.sends')}}">
                        @csrf
                        <div role="tabpanel" class="tab-pane" id="email">

                            <br>
                            <select class="form-control select-option userGroupEmail" id="userGroupEmail" name="sendType">
                                <option value=""> @lang('cms.how-to-choose-send-message')</option>
                                <option value="1">@lang('cms.user-group')</option>
                                <option id="diff" {{ isset($userId) && !empty($userId)  ? 'selected' : null }} value="2">@lang('cms.selective')</option>
                            </select>

                            <br>


                            <div id="users_email" class="display-none">
                                <select class="form-control select-option" name="send" id="send">
                                    <option value=""> @lang('cms.choose-user-group')</option>
                                    <option value="newsLatters">@lang('cms.newsletters')</option>
                                    @foreach(\App\Utility\Level::levelEach() as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="search_email" @if(!isset($userId) && empty($userId)) class="display-none" @endif>
                                <select class="form-control js-example-basic-multiple" name="search_email[]"
                                        multiple="multiple">
                                    @if(isset($public_user) && $public_user->count() > 0)
                                        @foreach($public_user as $value)
                                            <option {{ isset($userId) && !empty($userId) && $userId == $value->id ? 'selected' : null }} value="{{$value->id}}">{{ $value->name." ".$value->family . " - " . $value->email}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <br>
                            <div class="form-group">

                                <label for="title">@lang('cms.subject')</label>
                                <input class="form-control" type="text" name="title">

                            </div>

                            <div class="form-group">
                                <label for="body">@lang('cms.content-2')</label>
                                <textarea name="body" id="body" cols="30" rows="10"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success pull-left">@lang('cms.send')</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- end email --}}

        </div>
    </div>


@endsection


@section('admin-css')
    <link href="{{ url('admin_theme/css/select2.css') }}" rel="stylesheet"/>
@endsection

@section('admin-js')

    <script src="{{ url('admin_theme/js/select2.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });

        $(".userGroup").change(function () {
            var selecteds = $(".userGroup option:selected").val();

            if (selecteds == 1) {
                $('#users').css('display', 'block');
                $('#search').css('display', 'none');
            } else if (selecteds == 2) {
                $('#search').css('display', 'block');
                $('#users').css('display', 'none');
            } else {
                $('#search').css('display', 'none');
                $('#users').css('display', 'none');
            }

        });


        $(".userGroupEmail").change(function () {
            var selectedss = $(".userGroupEmail option:selected").val();

            if (selectedss == 1) {
                $('#users_email').css('display', 'block');
                $('#search_email').css('display', 'none');
            } else if (selectedss == 2) {
                $('#search_email').css('display', 'block');
                $('#users_email').css('display', 'none');
            } else {
                $('#search_email').css('display', 'none');
                $('#users_email').css('display', 'none');
            }

        });


    </script>
@endsection

