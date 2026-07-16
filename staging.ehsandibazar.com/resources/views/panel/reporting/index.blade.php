@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop
@section('admin-css')
    <style type="text/css">
        .title-form {
            font-size: 18px !important;
        }

        .time {
            margin-bottom: 20px !important;
        }

        #invoice-list {
            padding: 0 20px;
        }

        @media print {
            .invoice-block {
                width: 40% !important;
                float: left;
            }

            .invoice-btn {
                display: none !important;
            }

            .logo-print {
                width: 291px;
                height: 109px;
                display: list-item;
                list-style-image: url(../images/logo_print.png);
                list-style-position: inside;
            }

            #invoice-list {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ $title ?? "" }}
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

                </div>
                <div class="body">
                    <div class=" form">
                        <form class="form-horizontal" method="post"
                              enctype="multipart/form-data"
                              action="{{route('panel.reporting.report')}}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                    <label for="referees">محصولات</label>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                    <div class="form-group default-select select2Style">
                                        <select name="product[]" id="product" multiple="multiple"
                                                data-select="false" class="form-control select2">
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}">{{$product->title}}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-primary" type="button" onClick="selectAll();">انتخاب همه
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                    <label for="referees">دسته بندی</label>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                    <div class="form-group default-select select2Style">
                                        <select name="category" id="category" data-select="false"
                                                class="form-control select2">
                                            <option value=""></option>
                                            @foreach($allCategoryProducts as $Category)
                                                <option value="{{$Category->id}}">{{$Category->title}}
                                                    ==> {{$Category->titleparent}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
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
                                <div class="form-group col-md-3">
                                    <label class="form-check-label">
                                        <input type="checkbox" id="date" name="date-enable">
                                        <span class="form-check-sign"></span>
                                    </label>
                                    <label for="date">فعال کردن تاریخ</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <input class="btn btn-success pull-left" type="submit" value="گزارش">
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(isset($orders) && count($orders) > 0)
                        <div class="table-responsive invoice-list" id="invoice-list">

                            <div class="col-12 text-center">
                                <h5 class="title-form">گزارش فروش سایت {{ env('SITE_NAME_FA') }} </h5>
                            </div>
                            <div class="col-md-12 text-right corporate-id">
                                <img src="/header.png"
                                     alt="{{ env('SITE_NAME_FA') }}" class="logo-print">
                            </div>
                            <div class="col-12 time mb-3">
                                <span>تاریخ: </span>
                                <span> {{ $startDateInput }}</span>
                                <span>&nbsp;&nbsp;&nbsp; لغایت</span>
                                <span>{{ $endDateInput }}</span>
                                <span>
                                 @if(isset($category_data->title))
                                        <span>&nbsp;&nbsp;&nbsp; : دسته بندی</span>
                                        {{ $category_data->title }}
                                    @endif
                                </span>
                            </div>

                            @php
                                $totalAmount = 0;
                                $totalDiscount=0;
                                $totalBaseAmount=0;
                                $count=0;
                                $countAll=0;
                                $amountPAll=0;
                                $totalAmountAll=0;
                                $amountP=0;
                                $totalAmountSingle=0;
                                $totalAmountSinglenail=0;
                            @endphp
                            <table class="table table-bordered table-striped table-hover ">
                                <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام کالا</th>
                                    <th class="text-center">تعداد</th>
                                    <th class="text-center">قیمت پشت جلد</th>
                                    <th class="text-center">قیمت فروش</th>
                                    <th class="text-center">تخفیف</th>
                                    <th class="text-center">مبلغ کل</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($orders->groupBy('product_id') as $itemOrder)
                                    @foreach($itemOrder as $order)
                                        @php
                                            $count += $order->itemCount;
                                            $amountP += unserialize($order->details)["item"]->price;
                                            $totalPriceItem = isset($order->amount_discount) && !empty($order->amount_discount) ? $order->amount_discount : unserialize($order->details)["item"]->price;
                                            $totalPriceItem = $totalPriceItem * $order->itemCount;
                                            $totalAmountSingle += $totalPriceItem;
                                             $totalPriceItemnail = $order->amount * $order->itemCount;
                                            $totalAmountSinglenail += $totalPriceItemnail;
                                            $totalAmount += $totalAmountSingle;

                                        @endphp

                                    @endforeach
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td>{{ unserialize($itemOrder[0]->details)["item"]->title }}</td>
                                        <td class="text-center">{{ $count }}</td>
                                        <td class="text-center">{{ number_format($itemOrder[0]->amount) }}</td>
                                        <td class="text-center">{{ number_format($totalAmountSinglenail) }}</td>
                                        <td class="text-center">
                                            @if(isset($itemOrder[0]->discount) && !empty($itemOrder[0]->discount))
                                                @if (unserialize($itemOrder[0]->discount)->baseon == \App\Utility\DiscountType::cent)
                                                    {{  unserialize($itemOrder[0]->discount)->cent." % " }}
                                                @else
                                                    {{  number_format(unserialize($itemOrder[0]->discount)->cent) }}
                                                @endif
                                            @else
                                                -----
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php

                                                $totalDiscount += (unserialize($itemOrder[0]->details)["item"]->price  * $itemOrder[0]->itemCount) - $totalPriceItem;
                                                $totalBaseAmount += unserialize($itemOrder[0]->details)["item"]->price;
                                            @endphp
                                            {{ number_format($totalAmountSingle) }}
                                        </td>
                                    </tr>
                                    @php

                                        $countAll=$countAll+$count;
                                        $amountPAll=$amountPAll+$totalAmountSingle;
                                        $totalAmountAll=$totalAmountAll+$totalAmountSinglenail;
                                        $count=0;
                                        $amountP=0;
                                        $totalAmount=0;
                                        $totalAmountSingle=0;
                                        $totalAmountSinglenail=0;
                                    @endphp
                                @endforeach
                                </tbody>

                            </table>


                            <div class="row justify-content-end">
                                <div class="col-lg-4 col-5 invoice-block ">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="text-center">
                                                <b>تعداد کل فروش :</b>
                                            </td>
                                            <td class="text-center">
                                                <b>{{ $countAll }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <b>مبلغ ناخالص :</b>
                                            </td>
                                            <td class="text-center">
                                                <b>{{ number_format($totalAmountAll) }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <b>تخفیف :</b>
                                            </td>
                                            <td class="text-center">
                                                <b>{{ number_format($totalAmountAll-$amountPAll) }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <b>جمع کل :</b>
                                            </td>
                                            <td class="text-center">
                                                <b>{{ number_format($amountPAll) }}</b>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>

                            <div class="text-center invoice-btn ">
                                <a class="btn btn-info text-light" onclick="printerDiv('invoice-list')"><i
                                            class="fa fa-print"></i> پرینت </a>
                            </div>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
@section('admin-js')
    <script type="text/javascript">

        function selectAll() {
            options = document.getElementsByTagName("option");
            for (i = 0; i < options.length; i++) {
                options[i].selected = "true";
            }
        }

        function printerDiv(divID) {
            var divElements = document.getElementById(divID).innerHTML;
            var oldPage = document.body.innerHTML;
            document.body.innerHTML = "<html><head><title></title></head><body>" +
                divElements + "</body>";
            window.print();
            document.body.innerHTML = oldPage;
        }

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

