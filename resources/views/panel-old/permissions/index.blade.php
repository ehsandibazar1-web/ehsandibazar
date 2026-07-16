@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت |   دسترسی
@endsection--}}


@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.access')
                    <div class="btn-group">
                       <a class="btn btn-info" href="{{ route('permissions.create') }}">@lang('cms.create-access')</a>
                   </div>
                </header>


                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.name-of-access')</th>
                            <th>@lang('cms.description')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>{!! strip_tags($permission->label) !!}</td>

                                <td>
                                    <button class="btn btn-success btn-xs" title="@lang('cms.show')" data-toggle="modal" href="#show{{ $permission->id }}"><i class="icon-eye-open"></i></button>
                                    <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal" href="#delete{{ $permission->id }}"><i class="icon-trash "></i></button>
                                    <a class="btn btn-primary btn-xs" title="@lang('cms.edit')"  href="{{ route('permissions.edit' , ['id' => $permission->id]) }}"><i class="icon-pencil"></i></a>


                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                    <span style="margin-right: 45%">{!! $permissions->render() !!}</span>

                    <!-- Modal show -->
                    @foreach($permissions as $permission)
                        <div class="modal fade "  id="show{{ $permission->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title"><span class=""> @lang('cms.show-details')   </span> </h4>
                                    </div>
                                    <div class="modal-body">
                                        <h3>{{$permission->name}}</h3>
                                        <p>

                                            {!! $permission->label !!}
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
@foreach($permissions as $permission)
<div class="modal fade" id="delete{{ $permission->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title">@lang('cms.delete-access-by-name') {{$permission->name}}</h4>
</div>
<div class="modal-body">

<h3>{{$permission->name}}</h3>
<p>
    {{$permission->label}}</p>
</div>
<div class="modal-footer">
<form action="{{ route('permissions.destroy'  , ['id' => $permission->id]) }}" method="post">
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
