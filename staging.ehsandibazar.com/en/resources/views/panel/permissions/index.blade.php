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
                    <div class="pull-left margin-5">
                        @can('panel.permission.create')
                            <a href="{{ route('panel.permission.create')  }}"
                               class="btn btn-outline-default btn-border-radius"> سطوح دسترسی جدید </a>
                        @endcan
                        @if (auth()->user()->isSuperAdmin())
                            <a href="{{ route('panel.permission.updateToDate')  }}"
                               class="btn btn-outline-danger btn-border-radius"> بروز رسانی سطوح دسترسی
                                <span class="badge bg-orange"> {{ isset($permissionCount) && isset($countNewRoute) ?  $countNewRoute -  $permissionCount : 0  }} </span>
                            </a>
                        @endif
                            @can('panel.role.index')
                                <a href="{{ route('panel.role.index')  }}"
                                   class="btn btn-outline-light btn-border-radius"> گروه های کاربری </a>
                            @endcan

                    </div>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="body">
                                    <div class="table-responsive">
                                        @if (isset($arrayRoute) && count($arrayRoute) > 0)
                                            <table class="table table-hover js-basic-example contact_list">
                                                <thead>
                                                <tr>
                                                    <th class="center">#</th>
                                                    <th class="center">دسترسی به صفحه</th>
                                                    <th class="center">متد</th>
                                                    <th class="center">عملیات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($arrayRoute as $key => $itemRout)
                                                    <?php
                                                    $explod = explode(".", $itemRout->name);
                                                    ?>
                                                    @if (isset($explod) && count($explod) >=3)
                                                        <tr>
                                                            <td class="center"> {{ $key + $arrayRoute->firstItem() }} </td>
                                                            <td class="center">   {{ isset($itemRout->label) ? $itemRout->label :   \Illuminate\Support\Facades\Lang::get('cms.'.$explod[2]) . " " .\Illuminate\Support\Facades\Lang::get('cms.'.$explod[1]) }} </td>
{{--                                                            <td class="center"> {{  isset($itemRout->label) ? $itemRout->label : $itemRout->name }} </td>--}}
                                                            <td class="center"> {{  $itemRout->method }} </td>
                                                            <td class="center">
                                                                @can('panel.permission.edit')
                                                                    <a href="{{ route('panel.permission.edit' , ['id' => $itemRout->id])  }}"
                                                                       class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float">
                                                                        <i class="material-icons">update</i>
                                                                    </a>
                                                                @endcan
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="pull-left">
                                @if(isset($arrayRoute) && count($arrayRoute) > 0)
                                    <span style="margin-right: 45%">{!! $arrayRoute->render() !!}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->

    <button style="display: none" type="button"
            class="submit-booking"
            data-toggle="modal" data-target="">
        <i class="material-icons">submit</i>
    </button>

@stop

@section('admin-js')
    <script src="{{ url('admin/assets/js/pages/tables/jquery-datatable.js')  }}"></script>
    <script src=" {{ url('admin/assets/js/pages/forms/basic-form-elements.js')  }} "></script>

@stop

