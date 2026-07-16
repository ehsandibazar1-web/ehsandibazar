@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت |  مدیران سایت
@endsection--}}


@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
@lang('cms.admin-of-site')
                   <div class="btn-group">
                       <a class="btn btn-info" href="{{ route('level.create') }}">@lang('cms.record-level-for-user')</a>
                   </div>
                </header>


                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.username')</th>
                            <th>@lang('cms.email')</th>
                            <th>@lang('cms.levels')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($roles as $role)
                            @if(count($role->users))
                                @foreach($role->users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$role->name}} - {!! $role->label !!}</td>


                                        <td>
                                            <form action="{{ route('level.destroy'  , ['id' => $user->id]) }}" method="post">
                                                {{ method_field('delete') }}
                                                {{ csrf_field() }}
                                                <button class="btn btn-delete btn-xs" title="@lang('cms.delete')"><i class="icon-trash "></i></button>
                                            <a class="btn btn-warning btn-xs" title="@lang('cms.edit')"  href="{{ route('level.edit' , ['id' => $user->id]) }}"><i class="icon-pencil"></i></a>
                                            </form>


                                        </td>
                                    </tr>

                                @endforeach
                            @endif

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
                                        <h4 class="modal-title"><span class=""> @lang('cms.number-of-user')   {{ $role->id }}   </span> </h4>
                                    </div>
                                    <div class="modal-body">
                                        <h3>{{$role->name}}</h3>
                                        <p>
                                            <img src="{{ Url('img/users/'.$role->img) }}">

                                            {!! $role->email !!}
                                            <br>
                                            {{$role->about}}
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



</div>

</section>
</div>
</div>

@endsection
