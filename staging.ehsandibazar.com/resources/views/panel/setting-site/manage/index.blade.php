@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')

    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        @can('panel.manage.store')
                            <div class="pull-left margin-5">
                                <a href="{{ route('panel.manage.create',$id) }}"
                                   class="btn btn-outline-default btn-border-radius">افزودن آیتم جدید </a>
                            </div>
                            @can('panel.setting.index')
                                <div class="pull-left margin-5">
                                    <a href="{{ route('panel.setting.index') }}"
                                       class="btn btn-outline-light btn-border-radius">فهرست تنظیمات</a>
                                </div>
                            @endcan
                        @endcan

                    </h2>

                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="#" onClick="return false;" class="dropdown-toggle" data-toggle="dropdown"
                               role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="{{ route('panel.dashboard.index')  }}">داشبورد</a>
                                </li>
                                @can('panel.setting.index')
                                    <li>
                                        <a href="{{route('panel.setting.index')}}">بازگشت</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        @if (isset($systeminfmanage) && count($systeminfmanage) > 0)

                            <table class="table table-bordered table-striped table-hover js-basic-example ">
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


                                @foreach($systeminfmanage as $val)

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $val->name }}</td>
                                        <td>{{ isset($val->code) ? $val->code : "--" }}</td>
                                        <td>{{ !empty($val->code2) ? $val->code2 : "--" }}</td>
                                        <td>{{ !empty($val->code3) ? \Illuminate\Support\Str::limit($val->code3,100) : "--" }}</td>
                                        <td> {!!  !empty($val->code4) ? \Illuminate\Support\Str::limit(strip_tags($val->code4),80) : "--" !!} </td>
                                        <td><img width="100"
                                                 src="{{ isset($val) && !empty($val->code5) ? $val->code5 : null  }}">
                                        </td>
                                        <td>
                                            <a href="{{route('panel.manage.status',['id' => $val->id])}}">{{\App\Utility\Status::getStatus($val->status)}}</a>
                                        </td>
                                        <td>
                                            @can('panel.manage.update')
                                                <a href="{{ route('panel.manage.edit',$val->id) }}"
                                                   class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float">
                                                    <i class="material-icons">update</i>
                                                </a>
                                            @endcan

                                            @can('panel.manage.delete')
                                                @if($val->systeminf_id != 10)
                                                    <button type="button"
                                                            class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                            data-toggle="modal" data-target="#deleteModel{{$val->id}}">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                @endif
                                            @endcan

                                        </td>
                                    </tr>



                                    {{-- delete modal --}}
                                    <div class="modal fade" id="deleteModel{{$val->id}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">هشدار!</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    آیا از حذف آیتم مورد نظر اطمینان دارین؟
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('panel.manage.delete' , ['id' => $val->id])  }}"
                                                          method="post">
                                                        @csrf
                                                        {{ method_field('DELETE')  }}
                                                        <button type="submit" class="btn btn-danger waves-effect">حذف
                                                        </button>
                                                    </form>

                                                    <button type="button" class="btn btn-default waves-effect"
                                                            data-dismiss="modal">انصراف
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                                </tbody>
                                <tfoot>
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
                                </tfoot>

                            </table>

                        @else
                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                        @endif

                        {{-- detail --}}
                        <div class="modal fade" id="detailsModel" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">جزییات</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="{{route('panel.manage.store')}}">
                                            @csrf
                                            <div class="form-group">
                                                <input type="text" name="name" class="form-control"
                                                       placeholder="نام ...">
                                            </div>

                                            <div class="form-group">
                                                <input type="text" name="code" class="form-control"
                                                       placeholder="مقدار ...">
                                            </div>

                                            <div class="form-group">
                                                <input type="text" name="code2" class="form-control"
                                                       placeholder="مقدار2 ...">
                                                <a target="_blank" href="https://fontawesome.com/"><span> آیکون </span></a>
                                            </div>

                                            <div class="form-group">
                                                <input type="text" name="code3" class="form-control"
                                                       placeholder="مقدار3 ...">
                                            </div>


                                            <div class="form-group">
                                                <textarea rows="7" placeholder="مقدار 4 را وارد نمایید یا paste کنید"
                                                          type="text" name="code4"
                                                          class="form-control ckeditor"></textarea>
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

                                            <button type="submit" class="btn btn-info btn-round">ذخیره</button>
                                            <button type="button" class="btn btn-default waves-effect pull-left"
                                                    data-dismiss="modal">انصراف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="pull-left">
                                {{--                                @if(isset($systeminfmanage) && $systeminfmanage->count() > 0)--}}
                                {{--                                    <span style="margin-right: 45%">{!! $systeminfmanage->render() !!}</span>--}}
                                {{--                                @endif--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
@stop

