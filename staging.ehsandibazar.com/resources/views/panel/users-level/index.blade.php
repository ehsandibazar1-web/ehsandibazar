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
                            @can('panel.LevelManage.create')
                                <a href="{{ route('panel.LevelManage.create')  }}"
                                   class="btn btn-outline-default btn-border-radius">ثبت نقش جدید</a>
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
                                @can('panel.LevelManage.create')
                                    <li>
                                        <a href="{{ route('panel.LevelManage.create')  }}" style="font-size: 10px">
                                            ثبت نقش جدید
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
                        @if (isset($users) && count($users) > 0)
                            <table class="table table-bordered table-striped table-hover js-basic-example ">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">نام کاربر</th>
                                    <th class="text-center">ایمیل</th>
                                    <th class="text-center">مقام</th>
                                    <th class="text-center">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($users as $user)
                                    @if(count($user->roles) > 0)

                                        <tr>
                                            <td class="text-center">{{ $user->id }}</td>
                                            <td class="text-center">{{ $user->name ." ".$user->family }}</td>
                                            <td class="text-center">{{$user->email}}</td>
                                            <td class="text-center">
                                                @foreach($user->roles as $role)
                                                    {{ $loop->iteration }}- {{$role->name}} - {!! $role->label !!}
                                                    <br>
                                                @endforeach
                                            </td>


                                            <td class="text-center">

                                                @can('panel.role.modalDetails')
                                                    <button type="button"
                                                            class="btn btn-info btn-circle waves-effect waves-circle waves-float"
                                                            data-toggle="modal"
                                                            data-target="#detaileModel{{$user->id}}">
                                                        <i class="material-icons">search</i>
                                                    </button>
                                                @endcan
                                                @can('panel.role.edit')
                                                    <a href="{{ route('panel.LevelManage.edit',['user' => $user]) }}"
                                                       class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                        <i class="material-icons">update</i>
                                                    </a>
                                                @endcan

                                                @can('panel.role.delete')
                                                    <button type="button"
                                                            class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                            data-toggle="modal"
                                                            data-target="#deleteModel{{$user->id}}">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                @endcan

                                            </td>
                                        </tr>

                                        {{-- modal delete --}}
                                        <div class="modal fade" id="deleteModel{{$user->id}}" tabindex="-1"
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
                                                        آیا از حذف دسترسی این کاربر اطمینان دارید ؟
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form
                                                            action="{{ route('panel.LevelManage.delete'  , ['user' => $user]) }}"
                                                            method="post">
                                                            {{ method_field('delete') }}
                                                            {{ csrf_field() }}
                                                            <button type="submit"
                                                                    class="btn btn-danger waves-effect">حذف
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
                                        <div class="modal fade" id="detaileModel{{$user->id}}" tabindex="-1"
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

                                                        <p> نام مقام : <span> {{ $user->name }} </span></p>
                                                        <p> برچسب : <span> {{ $user->label }} </span></p>
                                                        <hr>
                                                        <p> دسترسی ها : </p>
                                                        <ul>
                                                            @if (isset($user->permissions[0]) && !empty($user->permissions))
                                                                @foreach($user->permissions as $itemPermission)
                                                                    <?php  $explod = explode(".", $itemPermission->name);  ?>
                                                                    <li>
                                                                        - {{ isset($itemPermission->label) ? $itemPermission->label :   \Illuminate\Support\Facades\Lang::get('cms.'.$explod[2]) . " " .\Illuminate\Support\Facades\Lang::get('cms.'.$explod[1]) }} </li>
                                                                @endforeach
                                                            @else
                                                                <p> برای این نقش کاربری دسترسی در نظر گرفته نشده
                                                                    است </p>
                                                            @endif
                                                        </ul>

                                                        <hr>
                                                        <button type="button"
                                                                class="btn btn-default waves-effect pull-left"
                                                                data-dismiss="modal">انصراف
                                                        </button>

                                                    </div>
                                                    <div class="modal-footer">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- modal details --}}


                                    @endif

                                @endforeach

                                </tbody>
                                <tfoot>

                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">نام کاربر</th>
                                    <th class="text-center">ایمیل</th>
                                    <th class="text-center">مقام</th>
                                    <th class="text-center">عملیات</th>
                                </tr>

                                </tfoot>

                            </table>

                        @else
                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                        @endif
                        {{--<div class="container">--}}
                        {{--<div class="pull-left">--}}
                        {{--@if(isset($users) && $users->count() > 0)--}}
                        {{--<span style="margin-right: 45%">{!! $users->render() !!}</span>--}}
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

