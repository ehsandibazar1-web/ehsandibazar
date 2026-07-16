
text/x-generic index.blade.php ( HTML document, UTF-8 Unicode text, with very long lines )
@extends('panel.layout.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.discount')

                    <a class="btn-xs btn-success"
                       href="{{ route('panel.discount.create') }}">@lang('cms.create-new-item')</a>
                </header>
            </section>
        </div>
        <div class="container">
            <section class="panel">
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.title')</th>
                            <th>@lang('cms.typeDiscount')</th>
                            <th>@lang('cms.based-on')</th>
                            <th>@lang('cms.discount-value-cent')</th>
                            <th> وضعیت تخفیف</th>
                            <th> فعال / غیرفعال</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($discount as $key =>  $val)
                            @php
                                $result =  \App\Http\Controllers\Admin\DiscountController::statusApplyDiscount(false,false,$val);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $val->title }}</td>
                                <td>
                                    {{ \App\Utility\DiscountType::discountType($val->type)  }}
                                </td>
                                <td>{{ \App\Utility\DiscountType::discountBaseOn($val->baseon) }}</td>
                                <td>{!!  $val->baseon == \App\Utility\DiscountType::cent ? '<b style=color:red>%</b>'.$val->cent : number_format($val->cent)  !!} </td>
                                @if($val->discountable_type != \App\Utility\DiscountType::role && $val->discountable_type!=\App\Utility\DiscountType::user && is_null($val->count_buy))
                                    <td>

                                        @php
                                            if(count($result) > 0){
                                                echo "<span class='btn btn-xs btn-success'> اعمال شده </span>";
                                            }else{
                                             echo "<a href='".route('panel.discount.edit' , ['id' => $val->id])."'><span class='btn btn-xs btn-danger'>اعمال نشده</span></a>";
                                            }
                                        @endphp
                                    </td>
                                @else
                                    <td > -- </td>
                                @endif
                                <td><a href="{{route('panel.discount.status' ,['id' => $val->id] )}}">
                                        {{ \App\Utility\Status::getStatus($val->status) }}
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-xs" title="@lang('cms.show')" data-toggle="modal"
                                            href="#show{{ $val->id }}"><i class="icon-eye-open"></i></button>

                                    {{--                                              @can('update')--}}
                                    <a class="btn btn-warning btn-xs" title="@lang('cms.edit')"
                                       href="{{ route('panel.discount.edit',$val->id) }}"><i
                                                class="icon-pencil"></i></a>
                                    {{--                                            @endcan--}}

                                    @can('delete')
                                        <button class="btn btn-danger btn-xs" title="@lang('cms.delete')"
                                                data-toggle="modal" href="#delete{{ $val->id }}"><i class="icon-trash "></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                    <span style="margin-right: 45%">{!! $discount->render() !!}</span>
                </div>
            </section>
        </div>


        <!-- Modal show -->
        @foreach($discount as $val)
            <div class="modal fade" id="show{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">@lang('cms.show-details')</h4>
                        </div>
                        <div class="modal-body">
                            <h3>{{ $val->title }}</h3>
                            <label>@lang('cms.discount-desc-note') : </label>
                            <p>
                                {!! $val->description !!}
                            </p>

                            <hr>
                            <label>@lang('cms.typeDiscount') : </label>
                            <span> {{ \App\Utility\DiscountType::discountType($val->type)  }}</span><br>
                            @if($val->type==\App\Utility\DiscountType::discountCode)
                                <span>{{ isset($val->discountCode) && !empty($val->discountCode) && isset($val->discountCode[0]) ? $val->discountCode[0]->code : "null" }}</span>

                            @elseif($val->type==\App\Utility\DiscountType::discountTime)
                                @if(isset($val->discountTime) && !empty($val->discountTime) && isset($val->discountTime[0]))
                                    <span>{{  Verta::createTimestamp($val->discountTime[0]->expire_date)->format('Y/m/j H:i:s') }}</span>
                                @endif
                            @elseif($val->type==\App\Utility\DiscountType::discountCodeTime)
                                @if(isset($val->discountCode) && !empty($val->discountCode) && isset($val->discountCode[0]) && isset($val->discountCode[0]->discountCodeTime[0]) )
                                    @lang('cms.expire_date') :
                                    <span>{{  Verta::createTimestamp($val->discountCode[0]->discountCodeTime[0]->expire_date)->format('Y/m/j H:i:s')}}</span>
                                @endif
                                <br>
                                @if(isset($val->discountCode) && !empty($val->discountCode) && isset($val->discountCode[0]))
                                    @lang('cms.code-single') :<span>{{ $val->discountCode[0]->code }}</span>
                                @endif
                            @elseif($val->type==\App\Utility\DiscountType::coupon)
                                @if(isset($val->coupon) && !empty($val->coupon) && isset($val->coupon[0]))
                                    @lang('cms.code-coupon') : <span>{{  $val->coupon[0]->code }}</span><br>
                                @endif
                                @if(isset($val->coupon) && !empty($val->coupon) && isset($val->coupon[0]))
                                    @lang('cms.coupon-expire_date') :
                                    <span>{{  Verta::createTimestamp($val->coupon[0]->expire_date)->format('Y/m/j H:i:s') }}</span>
                                @endif
                            @elseif($val->type==\App\Utility\DiscountType::amazing)
                                @if(isset($val->discountTime) && !empty($val->discountTime) && isset($val->discountTime[0]))
                                    @lang('cms.expire_date') :
                                    <span>{{ \App\Http\Controllers\Admin\DiscountController::convertToJalali(\App\Http\Controllers\Admin\DiscountController::TimestampToMiladi($val->discountTime[0]->expire_date)) }}</span>
                                @endif
                            @endif
                            <hr>

                            <label for=""> @lang('cms.requester') : </label>
                            <p> {{ $val->user->name . " " . $val->user->family . " " . "-" . $val->user->email }} </p>

                            <h5 class="heading-discount">@lang('cms.discount-on') :
                                <img src="{{ url('admin_theme/img/discount.png') }}" height="40" width="40"
                                     class="discount-img">

                            </h5>

                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>تخفیف روی</th>
                                    <th>وضعیت</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($val->disable as $itemDiscount)
                                    <tr>
                                        <td>{{ $loop->iteration.')' }}</td>
                                        <td>
                                            @if($val->discountable_type == \App\Utility\DiscountType::product)
                                                <a target="_blank" href="">
                                                    {{--                                                    {{ isset($itemDiscount->discountable->id) ?  App\Utility\DiscountType::discountableName($itemDiscount->discountable_type).' '.$itemDiscount->discountable->product->title ." - ".$itemDiscount->discountable->attributeTypeValue->value ." ".\App\Utility\Variation::checkRelationVariation($itemDiscount->discountable->id) : App\Utility\DiscountType::discountableName($itemDiscount->discountable_type).' '.$itemDiscount->discountable->name }}--}}
                                                    {{ isset($itemDiscount->discountable->id,$itemDiscount->discountable->product) ?  App\Utility\DiscountType::discountableName($itemDiscount->discountable_type).' '.$itemDiscount->discountable->product->title  : ""}}
                                                </a>
                                            @else
                                                <a target="_blank" href="">
                                                    {{ isset($itemDiscount->discountable->title) ?  App\Utility\DiscountType::discountableName($itemDiscount->discountable_type).' '.$itemDiscount->discountable->title: App\Utility\DiscountType::discountableName($itemDiscount->discountable_type).' '.$itemDiscount->discountable->name }}
                                                </a>
                                            @endif

                                        </td>
                                        <td>
                                            @if(isset($itemDiscount->discountable->name))
                                                {{ isset($itemDiscount->discountable->name) ? App\Utility\DiscountType::DiscountUserUsed($itemDiscount->is_used) : '-'  }}
                                            @else
                                                <button
                                                        class="btn btn-xs btn-info"
                                                        data-toggle="modal"
                                                        href="#details{{ $itemDiscount->id }}">
                                                    @lang('cms.details')
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <span class="mr-left-discount label label-info pull-right">@lang('cms.typeDiscount')  : {{ \App\Utility\DiscountType::discountType($val->type)  }}
</span>
                            <span
                                    class="mr-left-discount label label-danger pull-right">@lang('cms.create-by') : {{ $val->user->name }}</span>
                            @if(is_null($val->count_buy))
                                <span class="mr-left-discount  label label-primary pull-right">@lang('cms.discount-count-user') : {{ is_null($val->count_user) ? \Illuminate\Support\Facades\Lang::get('cms.unlimited') : $val->count_user.\Illuminate\Support\Facades\Lang::get('cms.discount-number-of-people')}}</span>
                            @else
                                <span class="mr-left-discount  label label-primary pull-right">@lang('cms.discount-count-buy') : {{ $val->count_buy }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            <!-- /.modal show -->
        @endforeach
    <!-- Modal show -->


        <!--  modal show -->
        @if(isset($val))
            @foreach($val->disable as $itemDiscount)
                <div class="modal fade" id="details{{ $itemDiscount->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title">@lang('cms.details')</h4>
                            </div>
                            <div class="modal-body">
                                <h5 class="request_user"> @lang('cms.requester') :
                                    <span class="img-reqeust-product"> <img class="pull-left"
                                                                            src="{{url('admin_theme/img/product-customer.png')}}"
                                                                            width="24" alt="request-product"> </span>
                                </h5>
                                <div class="col-md-12">
                                    <p> @lang('cms.name-lastName') :
                                        <span> {{ $val->user->name . " " .  $val->user->family  }} </span></p>
                                    <p> @lang('cms.email') :
                                        <span>  {{ isset($val->user->email) ? $val->user->email : "-" }} </span></p>
                                </div>
                                <hr>
                                <h5 class="product_description"> جزییات بیشتر :‌
                                    <span class="product-desc-img"><img src="{{url('admin_theme/img/pricing.png')}}"
                                                                        alt="product-description"></span>
                                </h5>
                                <div class="col-md-12">
                                    @if($val->discountable_type == \App\Utility\DiscountType::product)
                                        <a target="_blank" href="">
                                            @if(isset($itemDiscount->discountable->id) && !empty($itemDiscount->discountable->id))
                                                <p> نام محصول :
                                                    <span>  {{$itemDiscount->discountable->product->title}}  </span></p>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <span> خصوصیت :</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div style="border-right: 2px solid sienna;padding-right: 5px">
                                                            <span>  {{$itemDiscount->discountable->attributeTypeValue->value}}  </span>
                                                            <br>
                                                            <span> {{ \App\Utility\Variation::checkRelationVariation($itemDiscount->discountable->id) }} </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>


                                                <p> نام فروشنده :
                                                    <span> {{ $itemDiscount->discountable->user->name }}   </span></p>
                                                <hr>

                                                <p> قیمت : <span
                                                            class="{{ $itemDiscount->discountable->discountPrice != 0  ? "line-red" : null   }}"> {{ number_format($itemDiscount->discountable->price) }}   </span>
                                                </p>
                                                <hr>
                                                @if($itemDiscount->discountable->discountPrice != 0)
                                                    <p> قیمت با تخفیف :
                                                        <span> {{ number_format($itemDiscount->discountable->discountPrice) }}   </span>
                                                    </p>
                                                @endif

                                                <br>
                                            @endif
                                            <h5 class="heading-discount"> جزییات تخفیف :
                                                <img src="{{url('admin_theme/img/discount.png')}}" height="40"
                                                     width="40" class="discount-img">
                                            </h5>
                                            <div class="col-md-12">
                                                <p> نوع تخفیف :
                                                    <span> {{ \App\Utility\DiscountType::discountType($val->type)  }} </span>
                                                </p>
                                                <p> بر اساس :
                                                    <span> {{ \App\Utility\DiscountType::discountBaseOn($val->baseon) }} {!!  $val->baseon == \App\Utility\DiscountType::cent ? '<b style=color:red>%</b>'.$val->cent : number_format($val->cent)  !!}</span>
                                                </p>
                                            </div>

                                        </a>

                                    @else
                                        <a target="_blank" href="">
                                            {{ isset($itemDiscount->discountable->title) ?  App\Utility\DiscountType::discountableName($itemDiscount->discountable_type).' '.$itemDiscount->discountable->title: App\Utility\DiscountType::discountableName($itemDiscount->discountable_type).' '.$itemDiscount->discountable->name }}
                                        </a>
                                    @endif
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left"
                                        data-dismiss="modal">@lang('cms.cancel')</button>
                            </div>


                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
                <!-- /.modal delete -->
            @endforeach
        @endif
    <!-- Modal show -->


        <!-- Modal delete -->
        @foreach($discount as $val)
            <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">@lang('cms.alert')</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                @lang('cms.question-delete')
                            </p>
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('panel.discount.delete',$val->id) }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                       class="btn btn-danger">
                                <button type="button" class="btn btn-default pull-right"
                                        data-dismiss="modal">@lang('cms.cancel')</button>
                            </form>
                        </div>


                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            <!-- /.modal delete -->
    @endforeach
    <!-- Modal delete -->
    </div>
@endsection


@section('admin-js')
    <script src="{{url('admin_theme/js/calender/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{url('admin_theme/js/calender/bootstrap-datepicker.fa.min.js')}}"></script>

    {{-- calender --}}
    <script>
        $(document).ready(function () {
            $("#datepicker0").datepicker();

            $("#datepicker1").datepicker();
            $("#datepicker1btn").click(function (event) {
                event.preventDefault();
                $("#datepicker1").focus();
            });

            $("#datepicker2").datepicker({
                showOtherMonths: true,
                selectOtherMonths: true
            });

            $("#datepicker3").datepicker({
                numberOfMonths: 3,
                showButtonPanel: true
            });

            $("#datepicker4").datepicker({
                changeMonth: true,
                changeYear: true
            });

            $("#datepicker5").datepicker({
                minDate: 0,
                maxDate: "+14D"
            });

            $("#datepicker6").datepicker({
                isRTL: true,
                dateFormat: "d/m/yy"
            });
        });
    </script>

@endsection