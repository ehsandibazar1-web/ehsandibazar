@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت |  شهر ها
@endsection--}}


@section('content')
    <div class="row">
@include('errors.errors')
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.header-list-city')

                   <div class="btn-group">
                       <a class="btn btn-warning" href="{{ route('province.index') }}">@lang('cms.states')</a>
                       <button type="button" class="btn btn-success" data-toggle="modal" href="#insert">@lang('cms.new-city')</button>

                   </div>
                    <!-- Modal insert -->

                    <div class="modal fade" id="insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title">@lang('cms.create-new-item')</h4>
                                </div>
                                <div class="modal-body">

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post" action="{{Url('panel/city')}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"  id="cname" name="name" minlength="2" type="text" required />
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.state') </label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="province_id">
                                                    <option value="">@lang('cms.choose-state')</option>
                                                    @foreach(\App\Province::All() as $province)
                                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-danger" type="submit" value="@lang('cms.save')">

                                            </div>
                                        </div>
                                    </form>
                                </div>



                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </header>


                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.name')</th>
                            <th>@lang('cms.state')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($city as $val)
                            <tr>
                                <td>{{ $val->id }}</td>
                                <td>{{ $val->name }}</td>
                                <td>{{ $val->province->name }}</td>
                                <td>
                                    <button class="btn btn-success btn-xs" title="@lang('cms.show')" data-toggle="modal" href="#show{{ $val->id }}"><i class="icon-eye-open"></i></button>
                                    <button class="btn btn-warning btn-xs" title="@lang('cms.edit')" data-toggle="modal" href="#update{{ $val->id }}"><i class="icon-pencil"></i></button>
                                    <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal" href="#delete{{ $val->id }}"><i class="icon-trash "></i></button>


                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                    <span style="margin-right: 45%">{!! $city->render() !!}</span>

                    <!-- Modal show -->
                    @foreach($city as $val)
                        <div class="modal fade "  id="show{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title"><span class="">  @lang('cms.show-details')   </span> </h4>
                                    </div>
                                    <div class="modal-body">
                                        <h3>{{$val->name}}</h3>
                                        <p>

                                            {!! $val->email !!}
                                            <br>
                                            {{$val->about}}
                                        </p>
                                        <hr>



                                    </div>



                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                @endforeach
                <!-- /.modal-dialog -->
                </div>
                <!-- /.modal show -->

                <!-- Modal edit -->
                @foreach($city as $val)
                    <div class="modal fade" id="update{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title"> @lang('cms.edit-city')  {{$val->name}}</h4> </div>
                                <div class="modal-body">

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post" action="{{ route('city.update'  , ['id' => $val->id]) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PATCH">

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">نام </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->name}}"  id="cname" name="name" minlength="2" type="text" required />
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.state') </label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="province_id">
                                                    <option value="">@lang('cms.choose-state')</option>
                                                    @foreach(\App\Province::All() as $province)
                                                        <option value="{{ $province->id }}" @if($province->id==$val->province_id) selected="selected" @endif>{{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-danger" type="submit" value="@lang('cms.edit')">

                                            </div>
                                        </div>
                                    </form>
                                </div>



                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
        </div>
        <!-- /.modal edit -->
    @endforeach


<!-- Modal delete -->
@foreach($city as $val)
<div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title"> @lang('cms.delete-city') {{ $val->name }}    </h4>
</div>
<div class="modal-body">

<h3>{{$val->name}}</h3>
<p>
    {{$val->email}}</p>
</div>
<div class="modal-footer">
    <form action="{{ route('city.destroy'  , ['id' => $val->id]) }}" method="post">
        {{ method_field('delete') }}
        {{ csrf_field() }}

<input type="submit" name="btndelete" value="@lang('cms.delete')" class="btn btn-danger">
</form>
<br>
<button type="button" class="btn btn-default" data-dismiss="modal">@lang('cms.cancel')</button>
</div>



</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal delete -->
@endforeach

</div>

</section>
</div>
</div>

@endsection
