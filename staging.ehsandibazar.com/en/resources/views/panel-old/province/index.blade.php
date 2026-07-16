@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت |  استان ها
@endsection--}}


@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.list-state')

                   <div class="btn-group">
                       <a class="btn btn-danger" href="{{ route('city.index') }}">@lang('cms.cities')</a>
                       <button type="button" class="btn btn-success" data-toggle="modal" href="#insert">@lang('cms.new-state')</button>

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

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post" action="{{Url('panel/province')}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"  id="cname" name="name" minlength="2" type="text" required />
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-success pull-left" type="submit" value="@lang('cms.save')">

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
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.name')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($province as $val)
                            <tr>
                                <td>{{ $val->id }}</td>
                                <td>{{ $val->name }}</td>
                                <td>
                                    <button class="btn btn-success btn-xs" title="@lang('cms.show')" data-toggle="modal" href="#show{{ $val->id }}"><i class="icon-eye-open"></i></button>
                                    <button class="btn btn-warning btn-xs" title="@lang('cms.edit')" data-toggle="modal" href="#update{{ $val->id }}"><i class="icon-pencil"></i></button>
                                    <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal" href="#delete{{ $val->id }}"><i class="icon-trash "></i></button>


                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                    <span style="margin-right: 45%">{!! $province->render() !!}</span>

                    <!-- Modal show -->
                    @foreach($province as $val)
                        <div class="modal fade "  id="show{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title"><span class=""> @lang('cms.state-name')   {{ $val->name }}   </span> </h4>
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
                @foreach($province as $val)
                    <div class="modal fade" id="update{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title"> @lang('cms.state-name')  {{$val->name}}</h4> </div>
                                <div class="modal-body">

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post" action="{{ route('province.update'  , ['id' => $val->id]) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PATCH">

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->name}}"  id="cname" name="name" minlength="2" type="text" required />
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-warning pull-left" type="submit" value="@lang('cms.edit')">

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
@foreach($province as $val)
<div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title"> @lang('delete-state') {{ $val->name }}    </h4>
</div>
<div class="modal-body">

<h3>{{$val->name}}</h3>
<p>
    {{$val->email}}</p>
</div>
<div class="modal-footer">
    <form action="{{ route('province.destroy'  , ['id' => $val->id]) }}" method="post">
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
