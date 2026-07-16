@extends('panel-old.layout.master')
{{--@section('title')
    پنل | ویرایش پروفایل
@endsection--}}

@section('content')
    <div class="row">
        @include('panel-old.profile.partials.sidebar-profile')
        <aside class="profile-info col-lg-9">
            <section>
                <div class="panel panel-info">
                    <div class="panel-heading">@lang('cms.address')</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="post"
                              action="{{ route('profile.add.address' , ['id' =>$profile->id]) }}"
                              enctype="multipart/form-data">
                            @include('generals.allErrors')
                            @csrf

                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.state')</label>
                                <div class="col-lg-6">
                                    <select name="province_id" class="form-control select-option" id="province">
                                        <option>@lang('cms.choose-state')</option>
                                        @foreach(\App\Model\Province::all() as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.city')</label>
                                <div id="result-ajax">
                                    <div class="col-lg-6">
                                        <select name="city_id" class="form-control select-option" id="city">
                                            <option value="">@lang('cms.choose-city')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.address')</label>
                                <div class="col-lg-6">
                                    <input type="text" name="fullAddress" class="form-control" value="{{ old('address') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.name')</label>
                                <div class="col-lg-6">
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.tell')</label>
                                <div class="col-lg-6">
                                    <input type="text" name="tell" class="form-control" value="{{ old('tell') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.mobile')</label>
                                <div class="col-lg-6">
                                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">@lang('cms.postal-code')</label>
                                <div class="col-lg-6">
                                    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button type="submit" class="btn btn-info">@lang('cms.save')</button>
                                    <button type="button" class="btn btn-default">@lang('cms.cancel')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <section class="panel">
                <div class="bio-graph-heading">
                   @lang('cms.your-address')
                </div>
               @foreach($address as $item)
                    <div class="panel-body bio-graph-info">
                        <h1>{{ $item->name }}</h1>
                        <p> @lang('cms.state')  {{ $item->province->name }} ،  @lang('cms.city')  {{ $item->city->name }} ، {{ $item->fullAddress }}</p>
                        <span><i class="icon-mobile-phone"> {{ $item->mobile }}</i></span>
                        <br>
                        <span><i class="icon-phone"> {{ $item->tell }}</i></span>
                        <br>
                        <br>
                        <br>
                        <hr style="width: 70%">

                        <div style="float: left">
                            <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal" href="#delete{{ $item->id }}"><i class="icon-trash"></i></button>
                        </div>
                    </div>
                  <hr style="border-bottom: 3px solid #f1f2f7">
                @endforeach
            </section>


        <!-- Modal delete -->
            @foreach($address as $val)
                <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">@lang('cms.alert')</h4>
                            </div>
                            <div class="modal-body">
                                <p>
                                    @lang('cms.question-delete')
                                </p>
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('delete.address',$val->id) }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">

                                    <input type="submit" name="btndelete" value="@lang('cms.delete')" class="btn btn-danger">
                                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">@lang('cms.cancel')</button>
                                </form>
                            </div>



                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal delete -->
            @endforeach
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


