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
        @include('generals.allErrors')
        @include('generals.sessionMessage')
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.role.create')
                                <a href="{{ route('panel.role.create')  }}"
                                   class="btn btn-outline-default btn-border-radius"> ایجاد نقش کاربری جدید </a>
                            @endcan
                        </div>
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
                                @can('panel.role.create')
                                    <li>
                                        <a href="{{ route('panel.role.create')  }}" style="font-size: 10px">
                                            ایجاد نقش کاربری جدید
                                        </a>
                                    </li>
                                @endcan
                                @can('panel.permission.create')
                                    <li>
                                        <a href="{{ route('panel.permission.create')  }}" style="font-size: 10px">
                                            تعریف سطوح دسترسی
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        @if (isset($allRole) && count($allRole) > 0)
                            <table class="table table-bordered table-striped table-hover js-basic-example ">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">نام</th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($allRole as $val)

                                    <tr>
                                        <td class="text-center"> {{ $loop->iteration  }} </td>
                                        <td class="text-center"> {{ $val->name }} </td>

                                        <td class="text-center">

                                            @can('panel.role.modalDetails')
                                            <button type="button"
                                                    class="btn btn-info btn-circle waves-effect waves-circle waves-float"
                                                    data-toggle="modal" data-target="#detaileModel{{$val->id}}">
                                                <i class="material-icons">search</i>
                                            </button>
                                            @endcan
                                            @can('panel.role.edit')
                                            <a href="{{ route('panel.role.edit',['id' => $val->id]) }}"
                                               class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                <i class="material-icons">update</i>
                                            </a>
                                            @endcan

                                            @can('panel.role.delete')
                                                <button type="button"
                                                        class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#deleteModel{{$val->id}}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endcan

                                        </td>
                                    </tr>

                                    {{-- modal delete --}}
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
                                                    <form action="{{ route('panel.role.delete' , ['id' => $val->id])  }}"
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
                                    {{-- modal delete --}}


                                    {{-- modal details --}}
                                    <div class="modal fade" id="detaileModel{{$val->id}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">جزییات!</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <p> نام مقام : <span> {{ $val->name }} </span></p>
                                                    <p> برچسب : <span> {{ $val->label }} </span></p>
                                                    <hr>
                                                    <p> دسترسی ها : </p>
                                                    <ul>
                                                        @if (isset($val->permissions[0]) && !empty($val->permissions))
                                                            @foreach($val->permissions as $itemPermission)
                                                                <?php  $explod = explode(".", $itemPermission->name);  ?>
                                                                <li> - {{ isset($itemPermission->label) ? $itemPermission->label :   \Illuminate\Support\Facades\Lang::get('cms.'.$explod[2]) . " " .\Illuminate\Support\Facades\Lang::get('cms.'.$explod[1]) }} </li>
                                                            @endforeach
                                                        @else
                                                            <p> برای این نقش کاربری دسترسی در نظر گرفته نشده است </p>
                                                        @endif
                                                    </ul>

                                                    <hr>
                                                    <button type="button" class="btn btn-default waves-effect pull-left"
                                                            data-dismiss="modal">انصراف
                                                    </button>

                                                </div>
                                                <div class="modal-footer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- modal details --}}


                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">نام</th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </tfoot>

                            </table>

                        @else
                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                        @endif
                        {{--<div class="container">--}}
                        {{--<div class="pull-left">--}}
                        {{--@if(isset($allRole) && $allRole->count() > 0)--}}
                        {{--<span style="margin-right: 45%">{!! $allRole->render() !!}</span>--}}
                        {{--@endif--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->

@stop

