@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
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

                            </ul>
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col-12">
                            @can('panel.order.print')
                                <div class="pull-left">
                                    <select name="printType" class="printType" style="height:25px;font-size:14px">
                                        <option value="0">لیبل</option>
                                        <option value="1">فاکتور</option>
                                    </select>
                                    <button type="button" class="btn btn-xs btn-primary pull-left print"
                                            style="font-size:14px">
                                        <i class="icon-print"></i>
                                        پرینت
                                    </button>
                                </div>
                            @endcan
                            @can('panel.order.changeSending')
                                <div class="pull-right">
                                    <select name="changeStatus" class="changeStatus" style="height:25px;font-size:13px">
                                        <option value="">انتخاب وضعیت</option>
                                        @foreach(\App\Utility\Status::eachChangewStatusOrder() as $key => $itemStatus)
                                            <option value="{{ $key }}"> {{ $itemStatus }} </option>
                                        @endforeach

                                        @can('delete-group')
                                            <option value="999">حذف گروهی</option>
                                        @endcan
                                    </select>
                                    <button type="button" class="btn btn-xs btn-info pull-left changeToSending">
                                        <i class="icon-random"></i>
                                        اعمال
                                    </button>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <hr>
                    @if(in_array(auth()->user()->level  , [\App\Utility\Level::ADMIN , \App\Utility\Level::SUPER_ADMIN ,  \App\Utility\Level::OPERATOR]))
                        <div class="col-lg-12">
                            <section class="panel">
                                <div class="panel-body">
                                    <form class="form-inline" role="form" action="{{ route('user.order-search') }}"
                                          method="get">

                                        <div class="form-group col-md-6 box_date">
                                            <label for="title" class="control-label col-lg-2">از تاریخ</label>
                                            <input disabled
                                                   value="{{ old('start_date') }}"
                                                   id="datepicker1" name="start_date"
                                                   class="form-control expire_date_value start_date"
                                                   type="text">
                                        </div>
                                        <div class="form-group col-md-6 box_date">
                                            <label for="title" class="control-label col-lg-2">تا تاریخ</label>
                                            <input disabled
                                                   value="{{ old('end_date') }}"
                                                   id="datepicker1" name="end_date"
                                                   class="form-control expire_date_value end_date"
                                                   type="text">
                                        </div>

                                        {{-- tracking_code --}}
                                        <div class="form-group col-md-6">
                                            <label class="sr-only" for="tracking_code">کد پیگیری</label>
                                            <input type="text" class="form-control" id="tracking_code"
                                                   name="tracking_code"
                                                   placeholder="کد پیگیری مورد نظر را وارد نمایید">
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label class="sr-only" for="status">وضعیت</label>
                                            <select name="status" id="status">
                                                <option value="">همه</option>
                                                @foreach(\App\Utility\Status::eachStatusOrder() as $key => $itemStatus)
                                                    <option value="{{ $key }}"> {{ $itemStatus }} </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- name --}}
                                        <div class="form-group col-md-6">
                                            <label class="sr-only" for="status">نام خریدار</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                   placeholder="نام خریدار">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-check-label">
                                                <input type="checkbox" id="date" name="date-enable">
                                                <span class="form-check-sign"></span>
                                            </label>
                                            <label for="date">فعال کردن تاریخ</label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-success">جستجو</button>

                                        </div>


                                    </form>
                                </div>
                            </section>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        @if (isset($orders) && count($orders) > 0)
                            <form class="form-inline" role="form" action="{{ route(Route::currentRouteName()) }}"
                                  method="get"
                                  id="numberOfPaginate" name="numberOfPaginate">
                                <div class="col-1">
                                    <select name="number" id="number" style="height:25px;font-size:13px"
                                            onchange="changeNumberOfPaginate()">
                                        <option value="10" {{ isset(request()->number) && !empty(request()->number) && request()->number == 10 ? 'selected' : null }}>
                                            10
                                        </option>
                                        <option value="20" {{ isset(request()->number) && !empty(request()->number) && request()->number == 20 ? 'selected' : null }}>
                                            20
                                        </option>
                                        <option value="30" {{ isset(request()->number) && !empty(request()->number) && request()->number == 30 ? 'selected' : null }}>
                                            30
                                        </option>
                                        <option value="40" {{ isset(request()->number) && !empty(request()->number) && request()->number == 40 ? 'selected' : null }}>
                                            40
                                        </option>
                                        <option value="50" {{ isset(request()->number) && !empty(request()->number) && request()->number == 50 ? 'selected' : null }}>
                                            50
                                        </option>
                                        <option value="60" {{ isset(request()->number) && !empty(request()->number) && request()->number == 60 ? 'selected' : null }}>
                                            60
                                        </option>
                                        <option value="70" {{ isset(request()->number) && !empty(request()->number) && request()->number == 70 ? 'selected' : null }}>
                                            70
                                        </option>
                                        <option value="80" {{ isset(request()->number) && !empty(request()->number) && request()->number == 80 ? 'selected' : null }}>
                                            80
                                        </option>
                                        <option value="90" {{ isset(request()->number) && !empty(request()->number) && request()->number == 90 ? 'selected' : null }}>
                                            90
                                        </option>
                                        <option value="100" {{ isset(request()->number) && !empty(request()->number) && request()->number == 100 ? 'selected' : null }}>
                                            100
                                        </option>

                                    </select>
                                </div>
                            </form>
                            <table class="table table-bordered table-striped table-hover ">
                                <thead>
                                <tr>
                                    <th class="no-sort text-center">
                                        &nbsp;<label class="form-check-label">
                                            <input type="checkbox" name="select-all" id="select-all" value=""/>
                                            <span class="form-check-sign"></span>
                                        </label>
                                    </th>
                                    <th>@lang('cms.name')</th>
                                    <th>@lang('cms.order-tracking-code')</th>
                                    <th>کد مرسوله</th>
                                    <th>@lang('cms.payment')</th>
                                    <th>@lang('cms.status')</th>
                                    <th>@lang('cms.date')</th>
                                    <th>@lang('cms.operation')</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($orders as $itemOrder)
                                    @php
                                        $serializeOrderInfo = unserialize($itemOrder->user_info);
                                    @endphp
                                    <tr>
                                        <td>
                                            &nbsp;
                                            &nbsp;&nbsp;<label class="form-check-label">
                                                <input type="checkbox" name="print[]" value="{{$itemOrder->id}}"/>
                                                <span class="form-check-sign"></span>
                                            </label>
                                        </td>
                                        <td @if(isset($itemOrder->orderItem[0],$itemOrder->orderItem[0]->product->type) && $itemOrder->orderItem[0]->product->type != 0) style="background-color: #f1d8a1ba;"
                                            @elseif(isset($itemOrder->orderItem[0],$itemOrder->orderItem[0]->product->type) && $itemOrder->orderItem[0]->product->type == 0 && $itemOrder->shipping_method_id == 174)  style="background-color: #fce3fc;" @endif>
                                            <a target="_blank"
                                               href="#">{{ $serializeOrderInfo['userLogin']->name }} {{ $serializeOrderInfo['userLogin']->family }}</a>
                                        </td>
                                        <td @if(isset($itemOrder->orderItem[0],$itemOrder->orderItem[0]->product->type) && $itemOrder->orderItem[0]->product->type != 0) style="background-color: #f1d8a1ba;"
                                            @elseif(isset($itemOrder->orderItem[0],$itemOrder->orderItem[0]->product->type) && $itemOrder->orderItem[0]->product->type == 0 && $itemOrder->shipping_method_id == 174)  style="background-color: #fce3fc;" @endif>{{ \Illuminate\Support\Str::limit($itemOrder->tracking_code,14) }}</td>
                                        <td>{{ isset($itemOrder->shipping_code) && !empty($itemOrder->shipping_code) ? $itemOrder->shipping_code : 'کد مرسوله وارد نشده' }}</td>
                                        <td>
                                            {{ \App\Utility\paymentMethods::whichPaymentMethod($itemOrder->payment_method_id)  }}
                                        </td>
                                        <td class="view-message ">

                                            @can('panel.order.status')
                                                <a title="@lang('cms.status')" data-toggle="modal"
                                                   href="#status{{$itemOrder->id}}"> {{ \App\Utility\Status::getOrderStatus($itemOrder->status) }} </a>
                                            @endcan
                                        </td>
                                        <td>
                                        {{$itemOrder->created_at}}
                                        <td>

                                            @can('panel.order.index')
                                                <button type="button"
                                                        class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal"
                                                        data-target="#detailsModel{{$itemOrder->id}}">
                                                    <i class="material-icons">search</i>
                                                </button>
                                            @endcan
                                            @can('panel.order.delete')
                                                <button type="button"
                                                        class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal"
                                                        data-target="#deleteModel{{$itemOrder->id}}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endcan

                                        </td>
                                    </tr>



                                    @can('panel.order.delete')
                                        {{-- modal delete --}}
                                        <div class="modal fade" id="deleteModel{{$itemOrder->id}}" tabindex="-1"
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
                                                                action="{{ route('panel.order.delete',$itemOrder->id) }}"
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
                                    <th class="no-sort text-center">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="select-all" id="select-all" value=""/>
                                            <span class="form-check-sign"></span>
                                        </label>
                                    </th>
                                    <th>@lang('cms.name')</th>
                                    <th>@lang('cms.order-tracking-code')</th>
                                    <th>کد مرسوله</th>
                                    <th>@lang('cms.payment')</th>
                                    <th>@lang('cms.status')</th>
                                    <th>@lang('cms.date')</th>
                                    <th>@lang('cms.operation')</th>
                                </tr>
                                </tfoot>

                            </table>

                            @can('panel.order.index')
                                @foreach($orders as $itemOrder)
                                    <div class="modal fade" id="detailsModel{{$itemOrder->id}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">جزییات سفارش</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @if(isset($itemOrder->orderItem) && !empty($itemOrder->orderItem) && empty($itemOrder->credit_count))
                                                        @php $unSerialize = "" @endphp

                                                        @if(isset($orders) && isset($orders->details))
                                                            @php
                                                                $unSerialize =  unserialize($orders->details);
                                                                $detailsUser = \App\Utility\serializeAndUnSerialize::serializeAndUnSerializeInfoUser(null,null,$itemOrder->id);
                                                            @endphp
                                                        @endif
                                                        <div class="demo">
                                                            <div class="row">
                                                                <div class="col-md-4 col-sm-6">
                                                                    <div class="pricingTable">
                                                                        <div class="pricingTable-header">
                                                                            <i class="material-icons">brightness_medium</i>
                                                                            <div class="price-value">
                                                                                <span class="month"></span>
                                                                            </div>
                                                                        </div>
                                                                        <h3 class="heading">سفارش دهنده</h3>
                                                                        <div class="pricing-content">

                                                                        </div>
                                                                        <div class="pricingTable-signup">
                                                                            <a title="@lang('cms.show')"
                                                                               data-toggle="modal"
                                                                               href="#showDetailsOrder{{ $itemOrder->id }}">جزییات</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 col-sm-6">
                                                                    <div class="pricingTable greenColor">
                                                                        <div class="pricingTable-header">
                                                                            <i class="material-icons">local_mall</i>
                                                                            <div class="price-value">
                                                                                <span class="month"></span>
                                                                            </div>
                                                                        </div>
                                                                        <h3 class="heading">گیرنده</h3>
                                                                        <div class="pricing-content">
                                                                        </div>
                                                                        <div class="pricingTable-signup">
                                                                            <a title="@lang('cms.show')"
                                                                               data-toggle="modal"
                                                                               href="#showDetailsAddress{{ $itemOrder->id }}">جزییات</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 col-sm-6">
                                                                    <div class="pricingTable blueColor">
                                                                        <div class="pricingTable-header">
                                                                            <i class="material-icons">spa</i>
                                                                            <div class="price-value">
                                                                                <span class="month"></span>
                                                                            </div>
                                                                        </div>
                                                                        <h3 class="heading">@lang('cms.show-list-product')</h3>
                                                                        <div class="pricing-content">
                                                                        </div>
                                                                        <div class="pricingTable-signup">
                                                                            <a title="@lang('cms.show')"
                                                                               data-toggle="modal"
                                                                               href="#showdetailsProduct{{ $itemOrder->id }}">جزییات</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <p class="alert alert-info border-right-info "> خرید اعتبار
                                                            : {{$itemOrder->credit_count}} عدد </p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default waves-effect"
                                                            data-dismiss="modal">بستن
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="showDetailsOrder{{$itemOrder->id}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLabel">@lang('cms.order-client')</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @if(isset($itemOrder->user_info))
                                                        @php
                                                            $detailsUser = \App\Utility\serializeAndUnSerialize::serializeAndUnSerializeInfoUser(null,null,$itemOrder->id);
                                                        @endphp

                                                        <p> @lang('cms.order-client') : </p>
                                                        <table class="table table-hover table-bordered">
                                                            <tr>
                                                                <th>@lang('cms.name')</th>
                                                                <th>@lang('cms.family')</th>
                                                                <th>@lang('cms.email')</th>
                                                                <th>@lang('cms.mobile')</th>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <a href="#">{{ isset($detailsUser['userLogin']->name) && !empty($detailsUser['userLogin']->name) ? $detailsUser['userLogin']->name : null  }}</a>
                                                                </td>
                                                                <td>
                                                                    <a href="#">{{isset($detailsUser['userLogin']->family) && !empty($detailsUser['userLogin']->family) ? $detailsUser['userLogin']->family : null}}</a>
                                                                </td>
                                                                <td>{{isset($detailsUser['userLogin']->email) && !empty($detailsUser['userLogin']->email) ? $detailsUser['userLogin']->email : null}}</td>
                                                                <td>{{isset($detailsUser['userLogin']->mobile) && !empty($detailsUser['userLogin']->mobile) ? $detailsUser['userLogin']->mobile : null}}</td>
                                                            </tr>

                                                        </table>
                                                        <br><br><br><br>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default waves-effect"
                                                            data-dismiss="modal">بستن
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="showDetailsAddress{{$itemOrder->id}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLabel"> @lang('cms.transferee') </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @if(isset($itemOrder->user_info))
                                                        @php
                                                            $detailsUser = \App\Utility\serializeAndUnSerialize::serializeAndUnSerializeInfoUser(null,null,$itemOrder->id);
                                                        //dd($detailsUser['addressSession']);
                                                        @endphp
                                                        <p> @lang('cms.transferee') : </p>
                                                        <table class="table table-hover table-bordered">
                                                            <tr>
                                                                <th>@lang('cms.name-family')</th>
                                                                <th>@lang('cms.mobile')</th>
                                                                <th>@lang('cms.tell')</th>
                                                            </tr>

                                                            <tr>
                                                                <td>{{ isset($detailsUser['addressSession']->name) && !empty($detailsUser['addressSession']->name) ? $detailsUser['addressSession']->name : "--"  }}</td>
                                                                <td>{{isset($detailsUser['addressSession']->mobile) && !empty($detailsUser['addressSession']->mobile) ? $detailsUser['addressSession']->mobile : "--"}}</td>
                                                                <td>{{ isset($detailsUser['addressSession']->tell) && !empty($detailsUser['addressSession']->tell) ? $detailsUser['addressSession']->tell  : "--"}}</td>
                                                            </tr>

                                                        </table>
                                                        <hr>
                                                        <p> @lang('cms.address-1') : </p>
                                                        <p> {!! \App\Utility\getProvinceAndCity::getProvinceAndCity($detailsUser['addressSession']->province->id , $detailsUser['addressSession']->city_id)  !!} </p>
                                                        <p> {{$detailsUser['addressSession']->fullAddress ?? ""}} </p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default waves-effect"
                                                            data-dismiss="modal">بستن
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="showdetailsProduct{{$itemOrder->id}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLabel">  @lang('cms.show-list-product')</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="pull-right">
                                                                <span class="text-style"> <b>کد پیگیری :</b>  {{ $itemOrder->tracking_code }}</span>
                                                            </div>
                                                            <div class="pull-left">
                                                                {{ \App\Utility\Status::getOrderStatus($itemOrder->status)  }}
                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive m-t-40">
                                                                <table class="table table-hover">
                                                                    <tr>
                                                                        <th class="text-center">@lang('cms.num')</th>
                                                                        <th class="text-center"> @lang('cms.picture') </th>
                                                                        <th class="text-center">@lang('cms.name-product')</th>

                                                                        @if(in_array(auth()->user()->level , [\App\Utility\Level::ADMIN , \App\Utility\Level::SUPER_ADMIN]))
                                                                            <th class="text-center">@lang('cms.seller')</th>
                                                                        @endif

                                                                        <th class="text-center">@lang('cms.count')</th>
                                                                        <th class="text-center">@lang('cms.price-each-unit')</th>
                                                                        <th class="text-center">@lang('cms.discount-price')</th>
                                                                        <th class="text-center">@lang('cms.total-price')
                                                                            (@lang('cms.tooman'))
                                                                        </th>
                                                                    </tr>

                                                                    <?php $totalPrice = 0; ?>
                                                                    @foreach($itemOrder->orderItem as $orderItems)
                                                                        @if(isset($orderItems->details) && !empty($orderItems->details))
                                                                            @php $unserializeOrderItems = unserialize($orderItems->details); @endphp
                                                                            <tr>
                                                                                <td class="text-center">{{$loop->iteration}}</td>
                                                                                <td class="text-center"><img
                                                                                            src="{{$unserializeOrderItems['item']->image}}"
                                                                                            width="50" alt="product">
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ $unserializeOrderItems['item']->title }}
                                                                                    <br>
                                                                                    {{isset( $unserializeOrderItems['item']->AttributeValue) ?  "رنگ :" . $unserializeOrderItems['item']->AttributeValue : null }}
                                                                                    <br>
                                                                                    {{ isset($unserializeOrderItems['item']->relatedVariationValue) ? "سایز".$unserializeOrderItems['item']->relatedVariationValue : null  }}
                                                                                </td>
                                                                                @if(in_array(auth()->user()->level , [\App\Utility\Level::ADMIN , \App\Utility\Level::SUPER_ADMIN]))
                                                                                    <td class="text-center"> {{ $unserializeOrderItems['item']->sellerName }}  {{ $unserializeOrderItems['item']->sellerFamily }}</td>
                                                                                @endif

                                                                                <td class="text-center"> {{$unserializeOrderItems['qty']}} </td>
                                                                                <td class="text-center">{{ isset($orderItems->product->variations[0]) && !empty($orderItems->product->variations[0]) ? number_format($orderItems->product->variations[0]->price) : 'قیمت محصول در دسترس نیست' }}</td>
                                                                                <td class="text-center">
                                                                                    @if(!$orderItems->order->user->isColleague())
                                                                                        @if(isset($orderItems->amount_discount) && $orderItems->amount_discount > 0 && !empty($orderItems->amount_discount) && !empty($orderItems->discount))
                                                                                            {!! number_format($orderItems->amount_discount) !!}
                                                                                            -( {!!   unserialize($orderItems->discount)->baseon == \App\Utility\DiscountType::cent ? '<b style=color:red>%</b>'.unserialize($orderItems->discount)->cent : number_format(unserialize($orderItems->discount)->cent).' تومان '  !!}
                                                                                            )

                                                                                        @elseif(isset($orderItems->discount) && !empty($orderItems->discount) )
                                                                                            {{--                                                                        @dd($orderItems->discount)--}}
                                                                                            @if($orderItems->itemCount > unserialize($orderItems->discount)->count_buy)
                                                                                                ( {!!   unserialize($orderItems->discount)->baseon == \App\Utility\DiscountType::cent ? '<b style=color:red>%</b>'.unserialize($orderItems->discount)->cent : number_format(unserialize($orderItems->discount)->cent).' تومان '  !!}
                                                                                                )
                                                                                            @else
                                                                                                <p>بدون تخفیف</p>
                                                                                            @endif
                                                                                        @else
                                                                                            <p>بدون تخفیف</p>
                                                                                        @endif
                                                                                    @else
                                                                                        <p>{{ $orderItems->order->user->discount_percent }}
                                                                                            <b style=color:red>%</b></p>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    @if(isset($orderItems->amount_discount) && !empty($orderItems->amount_discount) && !$orderItems->order->user->isColleague())
                                                                                        {!! number_format($orderItems->amount_discount*$unserializeOrderItems['qty']) !!}
                                                                                    @else
                                                                                        {!! number_format($orderItems->amount*$unserializeOrderItems['qty']) !!}
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                        <?php $totalPrice += $unserializeOrderItems['price']; ?>
                                                                    @endforeach
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">


                                                        @if(isset($itemOrder->coupon) && !empty($itemOrder->coupon))
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="pull-right m-t-30 text-right">
                                                                        <p> کوپن : </p>
                                                                        <p class="total-price">
                                                                            {!!   unserialize($itemOrder->coupon)->baseon == \App\Utility\DiscountType::cent ? '<b style=color:red>%</b>'.unserialize($itemOrder->coupon)->cent : number_format(unserialize($itemOrder->coupon)->cent).' تومان '  !!}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif


                                                        @if(isset($itemOrder) && !empty($itemOrder->total_amount))
                                                            <?php
                                                            $userUnserialize = unserialize($itemOrder->user_info);
                                                            $getLevelForDiscountStoreBrand = $userUnserialize['userLogin']->level;
                                                            ?>
                                                            <?php $tax = \App\Utility\taxCalculate::getTax($itemOrder->total_amount, $totalPrice); ?>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="pull-right m-t-30 text-right">
                                                                        @if($itemOrder->user->isColleague())
                                                                            <p style="color: red">تخفیف :
                                                                                {{ unserialize($itemOrder->user_info)['userLogin']->discount_percent }}
                                                                                %
                                                                            </p>
                                                                        @endif
                                                                        <p>قیمت کل
                                                                            : {{isset($totalPrice) && !empty($totalPrice) ? number_format($totalPrice) . " " . \Illuminate\Support\Facades\Lang::get('cms.tooman') : 0}}</p>

                                                                        @if(isset($itemOrder->shippingCost) && $itemOrder->shippingCost > 0  && !empty($itemOrder->shippingCost))
                                                                            <p> هزینه ارسال
                                                                                : {{  number_format($itemOrder->shippingCost) . " " . \Illuminate\Support\Facades\Lang::get('cms.tooman') }} </p>
                                                                        @endif
                                                                        @if(isset($itemOrder->shippingMethod))
                                                                            @if(isset($itemOrder->orderItem[0],$itemOrder->orderItem[0]->product->type) && $itemOrder->orderItem[0]->product->type == \App\Utility\ProductType::SIMPLE)
                                                                                <p>روش ارسال : <img
                                                                                            src="{{ $itemOrder->shippingMethod->code5 }}"
                                                                                            class="img-responsive img-thumbnail"
                                                                                            width="80" alt="{{ $itemOrder->shippingMethod->name }}"></p>
                                                                            @endif
                                                                        @endif

                                                                        {{--                                                                    <p>قیمت با مالیات--}}
                                                                        {{--                                                                        : {{ \App\Utility\taxCalculate::getTax($itemOrder->total_amount , $totalPrice ,  $itemOrder->discountUserGeneral)."درصد" }}</p>--}}
                                                                        @if(isset($itemOrder) && !empty($itemOrder->total_discount) )
                                                                            <p>قیمت نهایی :
                                                                                @if($itemOrder->shippingCost > 0 && !is_null($itemOrder->shippingCost) )
                                                                                    @if($itemOrder->user->isColleague())
                                                                                        @php
                                                                                            $totalPrice = $itemOrder->total_discount;
                                                                                        @endphp
                                                                                    @else
                                                                                        @php
                                                                                            $totalPrice = $itemOrder->total_discount + $itemOrder->shippingCost;
                                                                                        @endphp
                                                                                    @endif

                                                                                    {!! number_format($totalPrice).' تومان' !!}
                                                                                @else
                                                                                    {!! number_format($itemOrder->total_discount).' تومان' !!}
                                                                                @endif
                                                                            </p>
                                                                        @endif
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        @endif

                                                        @can('panel.shipping.code')
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    @include('generals.allErrors')
                                                                    <form role="form" method="post"
                                                                          action="{{ route('panel.shipping.code',$itemOrder->id) }}">
                                                                        @csrf
                                                                        <div class="form-group">
                                                                            <label for="exampleInputEmail1">کد پست
                                                                                مرسوله</label>
                                                                            <input type="text" name="code"
                                                                                   class="form-control"
                                                                                   id="exampleInputEmail1"
                                                                                   value="{{ $itemOrder->shipping_code }}"
                                                                                   placeholder="کد مرسوله را وارد نمایید">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="tracking_code"></label>
                                                                            <input type="text" name="tracking_code"
                                                                                   class="form-control"
                                                                                   id="tracking_code"
                                                                                   value="{{ $itemOrder->tracking_code }}"
                                                                                   placeholder="کد پیگیری">
                                                                        </div>


                                                                        <button type="submit" class="btn btn-success">
                                                                            ارسال
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endcan
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger waves-effect"
                                                                data-dismiss="modal">بستن
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="status{{$itemOrder->id}}" tabindex="-1"
                                             role="dialog" aria-labelledby="exampleModalLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="exampleModalLabel">@lang('cms.change-status')</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <ul>
                                                            {{\App\Utility\Status::getOrderStatusShow($itemOrder->status , $itemOrder->id)}}
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default waves-effect"
                                                                data-dismiss="modal">بستن
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                @endforeach
                            @endcan
                                        @else
                                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                                        @endif
                                        <div class="container">
                                            <div class="pull-left">
                                                @if(isset($orders) && $orders->count() > 0)
                                                    <span style="margin-right: 45%">{{ $orders->appends(request()->query())->links() }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                    </div>
                </div>
            </div>
        </div>
        @stop
        @section('admin-js')
            <script>
                function changeNumberOfPaginate() {
                    var number = $("#number").val();
                    setGetParameter('number', number);
                }

                function setGetParameter(paramName, paramValue) {
                    var url = window.location.href;
                    var hash = location.hash;
                    url = url.replace(hash, '');
                    if (url.indexOf(paramName + "=") >= 0) {
                        var prefix = url.substring(0, url.indexOf(paramName + "="));
                        var suffix = url.substring(url.indexOf(paramName + "="));
                        suffix = suffix.substring(suffix.indexOf("=") + 1);
                        suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
                        url = prefix + paramName + "=" + paramValue + suffix;
                    } else {
                        if (url.indexOf("?") < 0)
                            url += "?" + paramName + "=" + paramValue;
                        else
                            url += "&" + paramName + "=" + paramValue;
                    }
                    window.location.href = url + hash;
                }

                $('#select-all').click(function (event) {
                    if (this.checked) {
                        // Iterate each checkbox
                        $(':checkbox').each(function () {
                            this.checked = true;
                        });
                    } else {
                        $(':checkbox').each(function () {
                            this.checked = false;
                        });
                    }
                });

                @can('panel.order.print')
                $('.print').click(function (e) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    var val = [];
                    var type = $('.printType').find(":selected").val();
                    $(':checkbox:checked').each(function (i) {
                        val[i] = $(this).val();
                    });
                    $.ajax({
                        type: "post",
                        url: "{{ route('panel.order.print') }}",
                        data: {
                            data: val,
                            type: type,
                            _token: CSRF_TOKEN
                        },
                        success: function (data) {
                            newWin = window.open("");
                            newWin.document.write(data.result);
                            //  document.body.innerHTML =data.result;
                            //  window.print();
                            //  location.reload();

                        },
                        error: function (error) {

                        }
                    });

                });
                @endcan

                @can('panel.order.changeSending')
                $('.changeToSending').click(function (e) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    var val = [];
                    var status = $('.changeStatus').find(":selected").val();
                    $(':checkbox:checked').each(function (i) {
                        val[i] = $(this).val();
                    });
                    if (status != "" && val.length > 0) {
                        $.ajax({
                            type: "post",
                            url: "{{ route('panel.order.changeSending') }}",
                            data: {
                                data: val,
                                status: status,
                                _token: CSRF_TOKEN
                            },
                            success: function (data) {
                                alert(data.message);
                                location.reload();
                            },
                            error: function (error) {

                            }
                        });
                    } else {
                        alert('وضعیت را انتخاب نمایید');
                    }


                });
                @endcan

            </script>

            <script type="text/javascript">
                $(document).ready(function () {
                    $(".start_date").persianDatepicker({
                        format: 'YYYY/MM/DD H:m:s',
                        // initialValue: false,
                        timePicker: {
                            enabled: true,
                            meridiem: {
                                enabled: true
                            }
                        }
                    });
                    $(".end_date").persianDatepicker({
                        format: 'YYYY/MM/DD H:m:s',
                        // initialValue: false,
                        timePicker: {
                            enabled: true,
                            meridiem: {
                                enabled: true
                            }
                        }
                    });
                    $('#date').on('click', function () {
                        if ($(this).is(':checked')) {
                            $(this).val(1);
                            $('.box_date').find('input').prop('disabled', false);
                        } else {
                            $(this).val(0);
                            $('.box_date').find('input').prop('disabled', true);
                        }
                    });
                });


            </script>
@endsection