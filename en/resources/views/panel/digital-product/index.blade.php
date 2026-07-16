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
                        {{ $title ?? "" }}
                        <div class="pull-left margin-5">
                            @can('panel.digitalProduct.add')
                                <a href="{{ route('panel.digitalProduct.add')  }}"
                                   class="btn btn-outline-default btn-border-radius"> ایجاد سابقه خرید </a>
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
                                @can('panel.digitalProduct.add')
                                    <li>
                                        <a href="{{ route('panel.digitalProduct.add')  }}" style="font-size: 10px">
                                            ایجاد سابقه خرید
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
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">نام</th>
                                    <th class="text-center">نام خانوادگی</th>
                                    <th class="text-center">موبایل</th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($users as $val)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $val->name }}</td>
                                        <td class="text-center">{{ $val->family }}</td>
                                        <td class="text-center">{{$val->mobile}}</td>

                                        <td class="text-center">
                                            @can('panel.digitalProduct.modalDetails')
                                                <button type="button"
                                                        class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#detailsModel{{$val->id}}">
                                                    <i class="material-icons">search</i>
                                                </button>
                                            @endcan

                                            @can('panel.digitalProduct.show')
                                                <a href="{{ route('panel.digitalProduct.show',$val) }}"
                                                   class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                    <i class="material-icons">update</i>
                                                </a>
                                            @endcan


                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">نام</th>
                                    <th class="text-center">نام خانوادگی</th>
                                    <th class="text-center">موبایل</th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </tfoot>

                            </table>

                        @else
                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                        @endif
                        <div class="container">
                            <div class="pull-left">
{{--                                @if(isset($users) && $users->count() > 0)--}}
{{--                                    <span style="margin-right: 45%">{!! $users->render() !!}</span>--}}
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

