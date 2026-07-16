@extends('panel-old.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.manage-setting')

                    <button type="button" class="btn btn-xs btn-success" data-toggle="modal"
                            href="#insert">@lang('cms.create-manage')
                    </button>

                    <a class="btn btn-xs btn-info pull-left" data-toggle="modal"
                       href="{{route('panel.setting')}}">@lang('cms.back')
                    </a>

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
                                          action="{{route('panel.manage.store')}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="name" name="name" minlength="2"
                                                       type="text" required/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">@lang('cms.value')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="code" name="code"
                                                       type="text"/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="title"
                                                   class="control-label col-lg-2">@lang('cms.value2')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="code2" name="code2"
                                                       type="text"/>
                                                <a target="_blank"
                                                   href="https://fontawesome.com/v3.2.1/icons"><span> @lang('cms.icon') </span></a>
                                            </div>

                                        </div>

                                        <div class="form-group ">
                                            <label for="title"
                                                   class="control-label col-lg-2">@lang('cms.value3')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="code3" name="code3"
                                                       type="text"/>
                                            </div>
                                        </div>


                                        <div class="form-group ">
                                            <label for="title"
                                                   class="control-label col-lg-2">@lang('cms.value4')</label>
                                            <div class="col-lg-10">
                                                <textarea name="code4" class="form-control" id="code4" cols="30"
                                                          rows="10"></textarea>
                                            </div>
                                        </div>

                                        {{-- image --}}
                                        <div class="form-group ">
                                            <label for="images"
                                                   class="control-label col-lg-2">@lang('cms.featuring-image')
                                            </label>
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                  <span class="input-group-btn">
                                                    <a id="lfm" data-input="thumbnail2" data-preview="holder2"
                                                       class="btn btn-primary">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>
                                                    <input id="thumbnail2" class="form-control" type="text"
                                                           name="filepath">
                                                </div>
                                            </div>

                                            <img id="holder2" style="margin-top:15px;max-height:100px;">
                                        </div>

                                        <input type="hidden" name="syshidden" value="{{$id}}">

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
                    <!-- /.modal-dialog -->
        </div>
        <!-- /.modal insert -->
        </header>


        @include('generals.allErrors')
        @include('generals.sessionMessage')

        <div class="container">
            <section class="panel">
                <div class="panel-body">
                    <table class="table" id="datatable">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.name')</th>
                            <th>@lang('cms.value')</th>
                            <th>@lang('cms.value2')</th>
                            <th>@lang('cms.value3')</th>
                            <th>@lang('cms.value4')</th>
                            <th>@lang('cms.picture')</th>
                            <th>@lang('cms.status')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($systeminfmanage))

                            @foreach($systeminfmanage as $val)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $val->name }}</td>
                                    <td>{{ isset($val->code) ? $val->code : "--" }}</td>
                                    <td>{{ !empty($val->code2) ? $val->code2 : "--" }}</td>
                                    <td>{{ !empty($val->code3) ? str_limit($val->code3,100) : "--" }}</td>
                                    <td> {!!  !empty($val->code4) ? "<span href='#desc". $val->id ."' title='".\Illuminate\Support\Facades\Lang::get('cms.description')."' class='btn btn-xs btn-info' data-toggle='modal'> ".\Illuminate\Support\Facades\Lang::get('cms.description')." </span>" : "--" !!} </td>
                                    <td>
                                        @if(isset($val->code5) && !empty($val->code5) && $val->code5 != env('APP_URL'))
                                            <img width="100"
                                                 src="{{ isset($val) && !empty($val->code5) ? $val->code5 : null  }}">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('panel.manage.status',['id' => $val->id])}}">{{\App\Utility\Status::getStatus($val->status)}}</a>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-xs" title="@lang('cms.edit')"
                                                data-toggle="modal"
                                                href="#edit{{ $val->id }}"><i class="icon-pencil"></i></button>
                                                
                                                  @can('delete')
                                        <button class="btn btn-danger btn-xs" title="@lang('cms.delete')"
                                                data-toggle="modal"
                                                href="#delete{{ $val->id }}"><i class="icon-trash "></i></button>
                                                @endcan
                                    </td>
                                </tr>

                            @endforeach
                        @endif
                        </tbody>

                    </table>

                    {{--                    @if(isset($systeminfmanage) && $systeminfmanage->count() > 0)--}}
                    {{--                        <span style="margin-right: 45%">{!! $systeminfmanage->render() !!}</span>--}}
                    {{--                    @endif--}}
                </div>
            </section>


            <!-- Modal edit -->
            @if(isset($systeminfmanage) && $systeminfmanage->count() > 0)
                @foreach($systeminfmanage as $val)
                    <div class="modal fade" id="edit{{ $val->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close close-white" data-dismiss="modal"
                                            aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title">@lang('cms.edit-setting')</h4></div>
                                <div class="modal-body">

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          action="{{route('panel.manage.update',['id' => $val->id])}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PATCH">

                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->name}}" id="title"
                                                       name="name" minlength="2" type="text" required/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="title"
                                                   class="control-label col-lg-2"> @lang('cms.value') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->code}}" id="title"
                                                       name="code" type="text"/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="title"
                                                   class="control-label col-lg-2"> @lang('cms.value2') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->code2}}" id="title"
                                                       name="code2" type="text"/>
                                                <a target="_blank"
                                                   href="https://fontawesome.com/v3.2.1/icons"><span> @lang('cms.icon') </span></a>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="title"
                                                   class="control-label col-lg-2"> @lang('cms.value3') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->code3}}" id="title"
                                                       name="code3" type="text"/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="title"
                                                   class="control-label col-lg-2"> @lang('cms.value4') </label>
                                            <div class="col-lg-10">
                                                <textarea name="code4" class="form-control" id="code4" cols="30"
                                                          rows="10">{{$val->code4}}</textarea>
                                            </div>
                                        </div>


                                        {{-- image --}}
                                        <div class="form-group ">
                                            <label for="images"
                                                   class="control-label col-lg-2">@lang('cms.featuring-image')
                                            </label>
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                  <span class="input-group-btn">
                                                    <a id="lfm1" data-input="thumbnail{{$val->id}}"
                                                       data-preview="holder{{$val->id}}"
                                                       class="btn btn-primary lfm1">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>
                                                    <input id="thumbnail{{$val->id}}" class="form-control" type="text"
                                                           value="{{isset($val) && !empty($val->code5) ? $val->code5 : null}}"
                                                           name="filepath">
                                                </div>

                                                <img id="holder{{$val->id}}"
                                                     src="{{isset($val) && !empty($val->code5) ? $val->code5 : null}}"
                                                     style="margin-top:15px;max-height:100px;">
                                                <br>
                                                <img id="holder{{$val->id}}" style="margin-top:15px;max-height:100px;">
                                                <br>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-danger pull-left" type="submit"
                                                       value="@lang('cms.edit')">
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


    <!-- Modal part show -->
        @if(isset($systeminfmanage))
            @foreach($systeminfmanage as $val)
                <div class="modal fade" id="desc{{ $val->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h4 class="modal-title"><span
                                        class="">@lang('cms.show-details')</span>
                                </h4>
                            </div>
                            <div class="modal-body">

                                <p> @lang('cms.description') :</p>

                                {!! $val->code4 !!}
                            </div>


                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            @endforeach
        @endif
    <!-- /.modal-dialog -->


        <!-- Modal delete -->
        @if(isset($systeminfmanage) && $systeminfmanage->count() > 0)
            @foreach($systeminfmanage as $val)
                <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
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
                                <form action="<?= route('panel.manage.delete', ['id' => $val->id]); ?>" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">

                                    <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                           class="btn btn-danger">
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
