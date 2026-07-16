@extends('panel-old.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">

                    @lang('cms.pages')

                    <button type="button" class="btn btn-xs btn-success" data-toggle="modal" href="#insert">@lang('cms.create-new-item')
                    </button>

                    <!-- Modal insert -->

                    <div class="modal fade" id="insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog screens">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title">@lang('cms.create-new-item')</h4>
                                </div>
                                <div class="modal-body">

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          action="{{route('page.store')}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2"> @lang('cms.title') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="cname" name="title" minlength="2"
                                                       type="text" required/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.description')</label>
                                            <div class="col-lg-10">
                                                <textarea name="body" id="" cols="30" rows="10"></textarea>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-success pull-left" type="submit" value="@lang('cms.create-2')">

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
        <!-- /.modal insert -->
        </header>


        @include('generals.allErrors')
        @include('generals.sessionMessage')

        <div class="container">
            <section class="panel">
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.title')</th>
                            <th>@lang('cms.description')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($pages) && !empty($pages))
                            @foreach($pages as $val)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="{{ $val->path() }}" target="_blank">{{ $val->title }}</a></td>
                                    <td>{!! str_limit($val->body,100) !!}</td>

                                    <td>
                                        <button class="btn btn-success btn-xs" title="@lang('cms.show')" data-toggle="modal"
                                                href="#show{{ $val->id }}"><i class="icon-eye-open"></i></button>
                                        <button class="btn btn-warning btn-xs" title="@lang('cms.edit')" data-toggle="modal"
                                                href="#edit{{ $val->id }}"><i class="icon-pencil"></i></button>
                                        <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal"
                                                href="#delete{{ $val->id }}"><i class="icon-trash "></i></button>
                                    </td>
                                </tr>

                            @endforeach
                        @endif
                        </tbody>

                    </table>

                    @if(isset($pages) && count($pages) > 0)
                        <span style="margin-right: 45%">{!! $pages->render() !!}</span>
                    @endif
                </div>
            </section>


            <!-- Modal show -->
            @if(isset($pages) && count($pages) > 0)
                @foreach($pages as $val)
                    <div class="modal fade" id="show{{ $val->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog screens">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close close-white" data-dismiss="modal"
                                            aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title">@lang('cms.show-item')</h4></div>
                                <div class="modal-body">
                                    <p> @lang('cms.description') </p>
                                    {!!  $val->body  !!}
                                    <hr>
                                    <p>@lang('cms.count-view')</p>

                                </div>

                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
        </div>
        <!-- /.modal edit -->
        @endforeach
        @endif

            <!-- Modal edit -->
            @if(isset($pages) && count($pages) > 0)
                @foreach($pages as $val)
                    <div class="modal fade" id="edit{{ $val->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog screens">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close close-white" data-dismiss="modal"
                                            aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title">@lang('cms.edit-item')</h4></div>
                                <div class="modal-body">

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          action="{{route('page.update',['id' => $val->id])}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PATCH">

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2"> @lang('cms.title') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->title}}" id="cname"
                                                       name="title" minlength="2" type="text" required/>
                                            </div>
                                        </div>


                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.description')</label>
                                            <div class="col-lg-10">
                                                <textarea name="body" id="" cols="30" rows="10">{{$val->body}}</textarea>
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
        @endif


    <!-- Modal delete -->
        @if(isset($pages) && count($pages) > 0)
            @foreach($pages as $val)
                <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog screens">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title">@lang('cms.alert')</h4>
                            </div>
                            <div class="modal-body">
                                <p>
                                   @lang('cms.question-delete')
                                </p>
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('page.destroy',['id' => $val->id]) }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">

                                    <input type="submit" name="btndelete" value="@lang('cms.delete')" class="btn btn-danger">
                                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                                        @lang('cms.cancel')
                                    </button>
                                </form>

                            </div>


                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
    </div>
    <!-- /.modal delete -->
    @endforeach
    @endif
    </div>

    </section>
    </div>
    </div>

@endsection
