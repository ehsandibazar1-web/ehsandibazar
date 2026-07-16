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
                    </h2>

                    <div class="pull-left">
                        @can('panel.role.index')
                            <a href="{{ route('panel.role.index')  }}"
                               class="btn btn-outline-default btn-border-radius"> لیست نقش کاربری </a>
                        @endcan
                        @can('panel.permission.index')
                            <a href="{{ route('panel.permission.index')  }}"
                               class="btn btn-outline-default btn-border-radius">لیست سطوح دسترسی </a>
                        @endcan

                        @can('panel.users.create')
                            <a href="{{ route('panel.users.create')  }}"
                               class="btn btn-outline-default btn-border-radius"> ایجاد کاربر جدید </a>
                        @endcan
                        @can('panel.LevelManage.index')
                            <a href="{{ route('panel.LevelManage.index')  }}"
                               class="btn btn-outline-warning btn-border-radius">مدیریت نقش کاربران</a>
                        @endcan

                    </div>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>نام و نام خانوادگی</th>
                                <th>(بلاک) وضعیت</th>
                                <th>تایید</th>
                                <th> موبایل</th>
{{--                                <th>پروفایل</th>--}}
                                <th> عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (isset($users) && count($users) > 0)
                                @foreach($users as $val)
                                    <tr>
                                        <td> {{ $loop->iteration  }} </td>
                                        <td><a target="_blank">{{ $val->fullName }} </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('panel.users.active' , ['id' => $val->id])  }}">
                                                {{ \App\Utility\Status::getStatus($val->mobile_verified_at) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('panel.users.status' , ['id' => $val->id])  }}">
                                                {{ \App\Utility\Status::getStatus($val->active) }}
                                            </a>
                                        </td>
                                        <td> {{ $val->mobile }} </td>
{{--                                        <td>--}}
{{--                                            @if(isset($val->detail) && !empty($val->detail))--}}
{{--                                            <a @can('panel.users.statusProfile') href="{{ route('panel.users.statusProfile',$val)  }}" @endcan>--}}
{{--                                                {{ \App\Utility\Status::getStatusJob($val->detail->status) }}--}}
{{--                                            </a>--}}
{{--                                        @else--}}
{{--                                            پروفایل تکمیل نشده--}}
{{--                                            @endif--}}
{{--                                        </td>--}}
                                        <td>
                                            @can('panel.users.modalDetails')
                                                <button type="button"
                                                        class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#detailsModel{{$val->id}}">
                                                    <i class="material-icons">search</i>
                                                </button>
                                            @endcan

                                            @can('panel.users.edit')
                                                <a class="btn btn-warning btn-circle waves-effect waves-circle waves-float"
                                                   href="{{  route('panel.users.edit' , ['id' => $val->id]) }}">
                                                    <i class="material-icons">update</i>
                                                </a>
                                            @endcan

                                            @can('panel.users.delete')
                                                <button type="button"
                                                        class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#deleteModel{{$val->id}}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endcan

                                            @can('panel.users.showDetail')
                                                <a class="btn btn-primary btn-circle waves-effect waves-circle waves-float"
                                                   href="{{  route('panel.users.showDetail' ,$val) }}">
                                                    <i class="material-icons">perm_identity</i>
                                                </a>
                                            @endcan


                                        </td>
                                    </tr>

                                    {{-- modal delete --}}
                                    <div class="modal fade" id="deleteModel{{$val->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel"
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
                                                    <form
                                                        action="{{ route('panel.users.delete' , ['id' => $val->id])  }}"
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
                                    <div class="modal fade" id="detailsModel{{$val->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">جزییات</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @if(isset($val->image[0]) && !empty($val->image[0]->url))
                                                        <a target="_blank"
                                                           href="{{isset($val) && !empty($val->image) ? url($val->image[0]->url) : null}}">
                                                            <img class="img-circle" width="100" height="100"
                                                                 src="{{isset($val) && !empty($val->image) ? url($val->image[0]->url) : null}}"
                                                                 style="margin-top:15px;max-height:100px;">
                                                        </a>

                                                    @endif
                                                    <p> نام و نام خانوادگی : <span>
                                                            {{ $val->name." ".$val->family  }} </span></p>
                                                    <p> ایمیل : <span> {{ $val->email  }} </span></p>
                                                    <p> موبایل : <span> {{ $val->mobile  }} </span></p>

                                                    <p> نقش کاربری :
                                                        <span> {{ \App\Utility\Level::getLevel($val->level) }} </span>
                                                    </p>
                                                    <p> تاریخ عضویت : <span> {{ $val->created_at }} </span></p>


                                                    <hr>

                                                    <h4>لاگ ورود : </h4>
                                                    @if(isset($val->authentications) && !empty($val->authentications) && count($val->authentications) > 0)

                                                        @foreach($val->authentications as $log)
                                                            <span>{{ $loop->iteration }} ) ip : {{ $log->ip_address }} ---
                                                                    <span class="date">
                                                                       {{ verta($log->login_at)->format('%d %B %Y H:i') }}
                                                                    </span>
                                                                </span><br>
                                                        @endforeach
                                                    @else
                                                        <span>تا کنون لاگین نکرده است</span>
                                                    @endif


                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    {{-- modal details --}}

                                @endforeach

                            @else
                                <p> رکوردی یافت نشد </p>
                            @endif

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>نام و نام خانوادگی</th>
                                <th>(بلاک) وضعیت</th>
                                <th>تایید</th>
                                <th> موبایل</th>
{{--                                <th> پروفایل</th>--}}
                                <th> عملیات</th>
                            </tr>
                            </tfoot>

                        </table>
                        <div class="container">
                            <div class="pull-left">
                                {{--                                @if(isset($users) && $users->count() > 0)--}}
                                {{--                                    --}}{{--<span style="margin-right: 45%">{!! $users->render() !!}</span>--}}
                                {{--                                    --}}{{--                                    @if ( isset($allMyBookingUser->appends))--}}
                                {{--                                    <span--}}
                                {{--                                        style="margin-right: 45%">{{ $users->appends(request()->query())->links() }}</span>--}}
                                {{--                                    --}}{{--                                    @endif--}}
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


