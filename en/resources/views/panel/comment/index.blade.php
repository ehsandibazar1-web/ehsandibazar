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
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        @if (isset($comments) && count($comments) > 0)
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">نام کاربر</th>
                                    <th class="text-center">صفحه</th>
                                    <th class="text-center"> وضعیت</th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($comments as $val)
                                    <tr>
                                        <td class="text-center"> {{ $loop->iteration  }} </td>
                                        <td class="text-center"> {{ $val->user->name }} </td>
                                        <td class="text-center">
                                            <a href="{{ $val->commentable->path() }}"
                                               target="_blank">{{ $val->commentable->title ?  $val->commentable->title :  "دیدگاه پست ".$val->commentable->user->fullName }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a @can('panel.comments.status') href="{{ route('panel.comments.status' , ['id' => $val->id])  }}" @endcan>
                                                {{ \App\Utility\CommentStatus::getStatusComment($val->status) }}
                                            </a>
                                        </td>

                                        <td class="text-center">

                                            @can('panel.comments.modalDetails')
                                                <button type="button"
                                                        class="btn btn-info btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#detailsModel{{$val->id}}">
                                                    <i class="material-icons">search</i>
                                                </button>
                                            @endcan

                                            @can('panel.comments.delete')
                                                <button type="button"
                                                        class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#deleteModel{{$val->id}}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endcan

                                        </td>
                                    </tr>

                                    @can('panel.comments.modalDetails')
                                        {{-- modal details --}}
                                        <div class="modal fade" id="detailsModel{{$val->id}}" tabindex="-1"
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

                                                        <p> نام کاربر :
                                                            <span> {{ $val->user->name . $val->user->family   }} </span>
                                                            <br>
                                                            {!! $val->comment !!}
                                                        </p>
                                                        <span class="btn btn-info">ip : {{ $val->ip }}</span>
                                                        <span
                                                            class="btn btn-warning">تاریخ : {{ $val->created_at }}</span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default waves-effect"
                                                                data-dismiss="modal">انصراف
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- modal details --}}
                                    @endcan

                                    @can('panel.comments.delete')
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
                                                        <form
                                                            action="{{ route('panel.comments.delete' , ['id' => $val->id])  }}"
                                                            method="post">
                                                            @csrf
                                                            {{ method_field('DELETE')  }}
                                                            <button type="submit" class="btn btn-danger waves-effect">
                                                                حذف
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
                                    @endcan

                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">نام</th>
                                    <th class="text-center">صفحه</th>
                                    <th class="text-center"> وضعیت</th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </tfoot>

                            </table>

                        @else
                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                        @endif

                        <div class="container">
                            <div class="pull-left">
{{--                                @if(isset($comments) && $comments->count() > 0)--}}
{{--                                    <span style="margin-right: 45%">{!! $comments->render() !!}</span>--}}
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

