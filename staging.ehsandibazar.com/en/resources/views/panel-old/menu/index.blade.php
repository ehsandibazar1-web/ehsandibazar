@extends('panel-old.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.menus')

                    <button type="button" class="btn btn-xs btn-success" data-toggle="modal" href="#insert">@lang('cms.create-new-item')
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
                                          action="{{route('menu.store')}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="cname" name="title" minlength="2"
                                                       type="text" required/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.path-link')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="cname" name="src" minlength="2"
                                                       type="text"/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.submenu') </label>
                                            <div class="col-lg-10">
                                                <select name="parent_id" class="form-control col-lg-4 select-option">
                                                    <option  value="0">@lang('cms.main')</option>
                                                    @if(isset($allMenu) && count($allMenu))
                                                        @foreach($allMenu as $val)
                                                            <option value="{{ $val->id }}">{{ $val->title }}</option>
                                                        @endforeach
                                                    @endif

                                                </select>
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
                    <th>@lang('cms.name')</th>
                    <th>@lang('cms.path-link')</th>
                    <th>@lang('cms.submenu')</th>
                    <th>@lang('cms.operation')</th>
                </tr>
                </thead>

                <tbody>
                @if(isset($menu) && count($menu) > 0)
                    <?php $i=1 ?>
                    @foreach($menu as $val)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $val->title }}</td>
                            <td><a href="{{ $val->src ? url($val->src) : "#" }}" target="_blank">{{ $val->src ? $val->src : \Illuminate\Support\Facades\Lang::get('cms.main') }}</a></td>
                            <td>{{menu($val->parent_id)}}</td>
                           {{-- <td> {{ $val->lang=='fa' ? 'فارسی' : 'انگلیسی'  }}</td>--}}
                            <td>
                                <button class="btn btn-warning btn-xs" title="@lang('cms.edit')" data-toggle="modal"
                                        href="#edit{{ $val->id }}"><i class="icon-pencil"></i></button>
                                <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal"
                                        href="#delete{{ $val->id }}"><i class="icon-trash "></i></button>
                            </td>
                        </tr>
                        <?php $i++ ?>
                    @endforeach
                @endif
                </tbody>

            </table>

            @if(isset($menu) && count($menu) > 0)
                <span style="margin-right: 45%">{!! $menu->render() !!}</span>
            @endif
        </div>
            </section>


        <!-- Modal edit -->
            @if(isset($menu) && count($menu) > 0)
                @foreach($menu as $val)
                    <div class="modal fade" id="edit{{ $val->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close close-white" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title">@lang('cms.edit-menu')</h4></div>
                                <div class="modal-body">

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          action="{{route('menu.update',['id' => $val->id])}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PATCH">

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->title}}" id="cname"
                                                       name="title" minlength="2" type="text" required/>
                                            </div>
                                        </div>


                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.path-link')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="cname" name="src" minlength="2"
                                                       type="text" value="{{$val->src}}"/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.submenu') </label>
                                            <div class="col-lg-10">
                                                <select name="parent_id" class="form-control control-label col-lg-4 select-option">

                                                    <option value="0">@lang('cms.main')</option>

                                                    @foreach(menus() as $vals)
                                                        <option {{  $vals->id == $val->parent_id ? "selected" : null }} value="{{ $vals->id }}">{{ $vals->title }}</option>
                                                    @endforeach

                                                </select>
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
        @if(isset($menu) && count($menu) > 0)
            @foreach($menu as $val)
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
                                <form action="{{ route('menu.destroy',$val->id) }}" method="POST">
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
<?php
use App\Model\Menu;
function menu($id)
{

    if ($id == '0') {
        return \Illuminate\Support\Facades\Lang::get('cms.main');
    } else {
        $men = Menu::where('id', $id)->first()['title'];
        return $men;
    }
}

function menus(){
    return Menu::all();
}
?>
