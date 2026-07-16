@extends('panel-old.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.manage-setting')

                    <button type="button" class="btn btn-xs btn-success" data-toggle="modal"
                            href="#insert">@lang('cms.create-new-item')
                    </button>

                    <!-- Modal insert -->
                    <div class="modal fade" id="insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title">@lang('cms.create-new-item')</h4>
                                </div>
                                <div class="modal-body">

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          action="{{route('panel.setting.store')}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="name" name="name" minlength="2"
                                                       type="text" required/>
                                            </div>
                                        </div>


                                        <div class="form-group ">
                                            <label for="title"
                                                   class="control-label col-lg-2">@lang('cms.description')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="description" name="description"
                                                       minlength="2"
                                                       type="text"/>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-success pull-left" type="submit"
                                                       value="@lang('cms.save')">
                                            </div>
                                        </div>

                                    </form>
                                </div>


                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal insert -->
                </header>

                @include('generals.allErrors')
                @include('generals.sessionMessage')

                <div class="container">
                    <section class="panel">
                        <div class="panel-body">
                            <table class="table table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>@lang('cms.num')</th>
                                    <th>@lang('cms.name')</th>
                                    <th>@lang('cms.description')</th>
                                    <th>@lang('cms.operation')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if(isset($systmeinf))

                                    @foreach($systmeinf as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $val->name }}</td>
                                            <td>{{ str_limit($val->description , 40) }}</td>
                                            {{--<td><a href="{{route('panel.setting.status' , ['id' => $val->id])}}">{{ \App\Utility\Status::getStatus($val->status) }}</a></td>--}}
                                            <td>
                                                <a class="btn btn-default btn-xs" title="@lang('cms.manage')"
                                                   data-toggle="modal"
                                                   href="{{route('panel.manage' , ['id' => $val->id])}}"><i
                                                        class="icon-cogs"></i></a>
                                                <button class="btn btn-primary btn-xs" title="@lang('cms.edit')"
                                                        data-toggle="modal"
                                                        href="#edit{{ $val->id }}"><i class="icon-pencil"></i></button>
                                                {{--                                        <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal"--}}
                                                {{--                                                href="#delete{{ $val->id }}"><i class="icon-trash "></i></button>--}}
                                            </td>
                                        </tr>

                                    @endforeach
                                @endif
                                </tbody>

                            </table>

                            {{--                    @if(isset($systmeinf) && $systmeinf->count() > 0)--}}
                            {{--                        <span style="margin-right: 45%">{!! $systmeinf->render() !!}</span>--}}
                            {{--                    @endif--}}
                        </div>
                    </section>


                    <!-- Modal edit -->
                    @if(isset($systmeinf) && $systmeinf->count() > 0)
                        @foreach($systmeinf as $val)
                            <div class="modal fade" id="edit{{ $val->id }}" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close close-white" data-dismiss="modal"
                                                    aria-hidden="true">
                                                &times;
                                            </button>
                                            <h4 class="modal-title">@lang('cms.edit-item')</h4></div>
                                        <div class="modal-body">

                                            <form class="cmxform form-horizontal tasi-form" id="commentForm"
                                                  method="post"
                                                  action="{{route('panel.setting.update',['id' => $val->id])}}">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="PATCH">

                                                <div class="form-group ">
                                                    <label for="title"
                                                           class="control-label col-lg-2">@lang('cms.name') </label>
                                                    <div class="col-lg-10">
                                                        <input class=" form-control" value="{{$val->name}}" id="title"
                                                               name="name" minlength="2" type="text" required/>
                                                    </div>
                                                </div>


                                                <div class="form-group ">
                                                    <label for="title"
                                                           class="control-label col-lg-2">@lang('cms.description')</label>
                                                    <div class="col-lg-10">
                                                        <input class=" form-control" value="{{$val->description}}"
                                                               id="title"
                                                               name="description" minlength="2" type="text" required/>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <div class="col-lg-offset-2 col-lg-10">
                                                        <input class="btn btn-warning pull-left" type="submit"
                                                               value="@lang('cms.edit')">

                                                    </div>
                                                </div>
                                            </form>
                                        </div>


                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal edit -->
                        @endforeach
                    @endif


                <!-- Modal delete -->
                    @if(isset($systmeinf) && $systmeinf->count() > 0)
                        @foreach($systmeinf as $val)
                            <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                &times;
                                            </button>
                                            <h4 class="modal-title">@lang('cms.alert')</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                @lang('cms.question-delete')
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('panel.setting.delete', ['id' => $val->id]) }}"
                                                  method="POST">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">

                                                <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                                       class="btn btn-danger">
                                                <button type="button" class="btn btn-default pull-right"
                                                        data-dismiss="modal">
                                                    @lang('cms.cancel')
                                                </button>
                                            </form>

                                        </div>


                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal delete -->
    @endforeach
    @endif
                </div>
            </section>
        </div>
    </div>

@endsection
