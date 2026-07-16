@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')

    @section('admin-css')
        <style>
            .select-wrapper input.select-dropdown{
                display: none;
            }
        </style>
    @endsection


    <!-- Basic Examples -->
    <div class="row clearfix">
        @include('generals.sessionMessage')
        @include('generals.allErrors')
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.newsLetter.index')
                                <a href="{{ route('panel.newsLetter.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> لیست خبرنامه </a>
                            @endcan
                        </div>
                    </h2>
                </div>
                <div class="body">
                    <form action="{{ route('panel.newsLetter.sends')  }}" method="post">
                        @csrf
                        <div role="tabpanel" class="tab-pane" id="email">

                            <br>
                            <select class="form-control select-option userGroupEmail" id="userGroupEmail">
                                <option value=""> @lang('cms.how-to-choose-send-message')</option>
                                <option value="1">@lang('cms.user-group')</option>
                                <option id="diff" value="2">@lang('cms.selective')</option>
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

                            <div id="search_email" class="display-none">
                                <select class="form-control js-example-basic-multiple" name="search_email[]"
                                        multiple="multiple">
                                    @if(isset($public_user) && $public_user->count() > 0)
                                        @foreach($public_user as $value)
                                            <option
                                                value="{{$value->id}}">{{ $value->name." ".$value->family . " - " . $value->email}}</option>
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
                                <textarea name="body" id="body" class="ckeditor" cols="30" rows="10"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success pull-left">@lang('cms.send')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->


@stop

@section('admin-js')
    <script src="{{ url('admin/assets/js/select2.js') }}"></script>

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

