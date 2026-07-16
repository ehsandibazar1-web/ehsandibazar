@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت |  مقام ها
@endsection--}}


@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
@lang('cms.levels')
                   <div class="btn-group">
                       <a class="btn btn-info" href="{{ route('roles.create') }}">@lang('cms.create-level')</a>
                       <a class="btn btn-warning" href="{{ route('permissions.index') }}">@lang('cms.access')</a>
                   </div>
                </header>


                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.name')</th>
                            <th>@lang('cms.description')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{!! $role->label !!}</td>

                                <td>
                                    <button class="btn btn-success btn-xs" title="@lang('cms.show')" data-toggle="modal" href="#show{{ $role->id }}"><i class="icon-eye-open"></i></button>
                                      @can('delete')
                                    <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal" href="#delete{{ $role->id }}"><i class="icon-trash "></i></button>
                                    @endcan
                                    <a class="btn btn-warning btn-xs" title="@lang('cms.edit')"  href="{{ route('roles.edit' , ['id' => $role->id]) }}"><i class="icon-pencil"></i></a>


                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                    <span style="margin-right: 45%">{!! $roles->render() !!}</span>

                    <!-- Modal show -->
                    @foreach($roles as $role)
                        <div class="modal fade "  id="show{{ $role->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title"><span class=""> @lang('cms.name-level')  {{ $role->name }}   </span> </h4>
                                    </div>
                                    <div class="modal-body">
                                        <h3>{{$role->name}}</h3>
                                        <p>

                                            {!! $role->label !!}
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

<!-- Modal delete -->
@foreach($roles as $role)
<div class="modal fade" id="delete{{ $role->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title"> @lang('cms.delete-level') {{$role->name}}</h4>
</div>
<div class="modal-body">

<h3>{{$role->name}}</h3>
<p>
    {{$role->label}}</p>
</div>
<div class="modal-footer">
<form action="{{ route('roles.destroy'  , ['id' => $role->id]) }}" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="_method" value="DELETE">

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
