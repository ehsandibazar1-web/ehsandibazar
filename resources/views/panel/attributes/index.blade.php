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
                        {{ $title ?? ''  }}
                        <div class="pull-left margin-5">
                            @can('panel.attribute.create')
                                <a href="{{ route('panel.attribute.create')  }}"
                                   class="btn btn-outline-success btn-border-radius">ایجاد ویژگی </a>
                            @endcan
                            @can('panel.attributeGroup.index')
                                <a href="{{ route('panel.attributeGroup.index')  }}"
                                   class="btn btn-outline-warning btn-border-radius">فهرست گروه ویژگی ها</a>
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
                                @can('panel.attributeGroup.create')
                                    <li>
                                        <a href="{{ route('panel.attributeGroup.create')  }}" style="font-size: 10px">ایجاد
                                            گروه ویژگی جدید</a>
                                    </li>
                                @endcan
                                @can('panel.attribute.index')
                                    <li>
                                        <a href="{{ route('panel.attribute.index')  }}" style="font-size: 10px">فهرست
                                            ویژگی </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        @if (isset($attribute) && count($attribute) > 0)
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">نام</th>
                                    <th class="text-center">برچسب</th>
                                    <th class="text-center">گروه بندی</th>
                                    <th class="text-center">وضعیت</th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($attribute as $val)

                                    <tr>
                                        <td class="text-center"> {{ $loop->iteration  }} </td>
                                        <td class="text-center"> {{ $val->name }} </td>
                                        <td class="text-center"> {{ isset($val->label) && !empty($val->label) ? $val->label : '---' }} </td>
                                        <td class="text-center"> {{ $val->attributeGroup->name }} </td>
                                        <td class="text-center"><a
                                                    href="{{ route('panel.attribute.status',$val->id) }}">
                                                {{ \App\Utility\Status::getStatus($val->status) }}
                                            </a></td>
                                        <td class="text-center">

                                            @can('panel.attribute.modalDetails')
                                                <button type="button"
                                                        class="btn btn-info btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#detailsModel{{$val->id}}">
                                                    <i class="material-icons">search</i>
                                                </button>
                                            @endcan

                                            @can('panel.attribute.edit')
                                                <a href="{{ route('panel.attribute.edit',['id' => $val->id]) }}"
                                                   class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                    <i class="material-icons">update</i>
                                                </a>
                                            @endcan

                                            @can('panel.attribute.delete')
                                                <button type="button"
                                                        class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#deleteModel{{$val->id}}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endcan


                                        </td>
                                    </tr>

                                    @can('panel.attribute.modalDetails')
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

                                                        <div> ایجاد کننده :
                                                            <span> {{ $val->user->name . $val->user->family   }} </span>
                                                            <br>
                                                            @if(isset($val->attributeValues) && !empty($val->attributeValues) && $val->isMultiValue() && count($val->attributeValues) > 0)
                                                                <h5 for="values">مقادیر:</h5>
                                                                <ul>
                                                                    @foreach($val->attributeValues as $value)
                                                                        <li>{{ $loop->iteration.") ".$value->value }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                            <br>
                                                        </div>
                                                        <span class="btn btn-warning">تاریخ ایجاد : {{ $val->created_at }}</span>
                                                        <br>
                                                        <span class="btn btn-default">تاریخ ویرایش : {{ $val->updated_at }}</span>
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


                                    @can('panel.attribute.delete')
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
                                                                action="{{ route('panel.attribute.delete' , ['id' => $val->id])  }}"
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
                                    <th class="text-center">برچسب</th>
                                    <th class="text-center">گروه بندی</th>
                                    <th class="text-center">وضعیت</th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </tfoot>

                            </table>

                        @else
                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->


@stop

