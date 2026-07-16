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
                        <div class="pull-left margin-5">
                            @can('panel.profile.addressCreate')
                                <a href="{{ route('panel.profile.addressCreate')  }}"
                                   class="btn btn-outline-default btn-border-radius"> ایجاد آدرس جدید </a>
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

                                <li>
                                    <a href="{{ route('panel.profile.index')  }}" style="font-size: 10px">
                                       پروفایل کاربری
                                    </a>
                                </li>
                                @can('panel.profile.addressCreate')
                                    <li>
                                        <a href="{{ route('panel.profile.addressCreate')  }}" style="font-size: 10px">
                                            ایجاد آدرس جدید
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        @if (isset($address) && count($address) > 0)
                            <table class="table table-bordered table-striped table-hover js-basic-example ">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center"> نام گیرنده </th>
                                    <th class="text-center">موبایل</th>
                                    <th class="text-center"> استان </th>
                                    <th class="text-center"> شهر </th>
                                    <th class="text-center"> کد پستی </th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($address as $val)

                                    <tr>
                                        <td class="text-center"> {{ $loop->iteration  }} </td>
                                        <td class="text-center"> {{ $val->name }} </td>
                                        <td class="text-center"> {{ $val->mobile }} </td>
                                        <td class="text-center"> {{ $val->province->name }} </td>
                                        <td class="text-center"> {{ $val->city->name }} </td>
                                        <td class="text-center"> {{ $val->postal_code }} </td>

                                        <td class="text-center">
                                            {{-- details --}}
                                            @can('panel.profile.addressDetails')
                                                <button type="button"
                                                        class="btn btn-info btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#detailModel{{$val->id}}">
                                                    <i class="material-icons">search</i>
                                                </button>
                                            @endcan
                                            {{-- edit --}}
                                            @can('panel.profile.addressEdit')
                                                <a href="{{ route('panel.profile.addressEdit',['id' => $val->id]) }}"
                                                   class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                    <i class="material-icons">update</i>
                                                </a>
                                            @endcan
                                            {{-- delete --}}
                                            @can('panel.profile.addressDelete')
                                                <button type="button"
                                                        class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#deleteModel{{$val->id}}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>

                                    @can('panel.profile.addressDelete')
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
                                                        action="{{ route('panel.profile.addressDelete' , ['id' => $val->id])  }}"
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
                                    @endif

                                    @can('panel.profile.addressDetails')
                                        {{-- modal detail --}}
                                        <div class="modal fade" id="detailModel{{$val->id}}" tabindex="-1"
                                             role="dialog" aria-labelledby="exampleModalLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">جزییات</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p> آدرس: </p>
                                                        {!! $val->fullAddress !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- modal detail --}}
                                    @endif



                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center"> نام گیرنده </th>
                                    <th class="text-center">موبایل</th>
                                    <th class="text-center"> شهر </th>
                                    <th class="text-center"> استان </th>
                                    <th class="text-center"> کد پستی </th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </tfoot>

                            </table>

                        @else
                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                        @endif
                        <div class="container">
                            <div class="pull-left">
                                @if(isset($address) && $address->count() > 0)
                                    <span style="margin-right: 45%">{!! $address->render() !!}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->

@endsection


