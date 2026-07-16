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
                            @can('panel.discount.create')
                                <a href="{{ route('panel.discount.create')  }}"
                                   class="btn btn-outline-default btn-border-radius"> ایجاد تخفیف </a>
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
                                @can('panel.discount.create')
                                    <li>
                                        <a href="{{ route('panel.discount.create')  }}" style="font-size: 10px">
                                            تخفیف جدید
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        @if (isset($discounts) && count($discounts) > 0)
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">@lang('cms.num')</th>
                                    <th class="text-center">@lang('cms.title')</th>
                                    <th class="text-center">@lang('cms.typeDiscount')</th>
                                    <th class="text-center">@lang('cms.based-on')</th>
                                    <th class="text-center">@lang('cms.discount-value-cent')</th>
                                    <th class="text-center"> وضعیت تخفیف</th>
                                    <th class="text-center"> فعال / غیرفعال</th>
                                    <th class="text-center">@lang('cms.operation')</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($discounts as $discount)
                                    @php
                                        $result =  \App\Http\Controllers\Admin\DiscountController::statusApplyDiscount(false,false,$discount);
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $discount->title }}</td>
                                        <td class="text-center">
                                            {{ \App\Utility\DiscountType::discountType($discount->type)  }}
                                        </td>
                                        <td class="text-center">{{ \App\Utility\DiscountType::discountBaseOn($discount->baseon) }}</td>
                                        <td class="text-center">{!!  $discount->baseon == \App\Utility\DiscountType::cent ? '<b style=color:red>%</b>'.$discount->cent : number_format($discount->cent)  !!} </td>
                                        @if($discount->discountable_type != \App\Utility\DiscountType::role && $discount->discountable_type!=\App\Utility\DiscountType::user && is_null($discount->count_buy))
                                            <td class="text-center">

                                                @php
                                                    if(count($result) > 0){
                                                        echo "<span class='btn btn-xs btn-success'> اعمال شده </span>";
                                                    }else{
                                                     echo "<a href='".route('panel.discount.edit' , ['id' => $discount->id])."'><span class='btn btn-xs btn-danger'>اعمال نشده</span></a>";
                                                    }
                                                @endphp
                                            </td>
                                        @else
                                            <td class="text-center"> --</td>
                                        @endif
                                        <td class="text-center"><a
                                                    href="{{route('panel.discount.status' ,['id' => $discount->id] )}}">
                                                {{ \App\Utility\Status::getStatus($discount->status) }}
                                            </a>
                                        </td>

                                        <td class="text-center">
                                            @can('panel.discount.modalDetails')
                                                <button type="button"
                                                        class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal"
                                                        data-target="#detailsModel{{$discount->id}}">
                                                    <i class="material-icons">search</i>
                                                </button>
                                            @endcan

                                            @can('panel.discount.edit')
                                                <a href="{{ route('panel.discount.edit',['id' => $discount->id]) }}"
                                                   class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                    <i class="material-icons">update</i>
                                                </a>
                                            @endcan

                                            @can('panel.discount.delete')
                                                <button type="button"
                                                        class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#deleteModel{{$discount->id}}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>



                                    @can('panel.discount.delete')
                                        {{-- modal delete --}}
                                        <div class="modal fade" id="deleteModel{{$discount->id}}" tabindex="-1"
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
                                                                action="{{ route('panel.discount.delete',$discount->id) }}"
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
                                    <th class="text-center">@lang('cms.num')</th>
                                    <th class="text-center">@lang('cms.title')</th>
                                    <th class="text-center">@lang('cms.typeDiscount')</th>
                                    <th class="text-center">@lang('cms.based-on')</th>
                                    <th class="text-center">@lang('cms.discount-value-cent')</th>
                                    <th class="text-center"> وضعیت تخفیف</th>
                                    <th class="text-center"> فعال / غیرفعال</th>
                                    <th class="text-center">@lang('cms.operation')</th>
                                </tr>
                                </tfoot>

                            </table>

                            @foreach($discounts as $discount)
                                @can('panel.discount.modalDetails')
                                    {{-- modal details --}}
                                    <div class="modal fade" id="detailsModel{{$discount->id}}" tabindex="-1"
                                         role="dialog"
                                         aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLabel">{{ $discount->title }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h3>{{ $discount->title }}</h3>
                                                    <label>@lang('cms.discount-desc-note') : </label>
                                                    <p>
                                                        {!! $discount->description !!}
                                                    </p>

                                                    <hr>
                                                    <label>@lang('cms.typeDiscount') : </label>
                                                    <span> {{ \App\Utility\DiscountType::discountType($discount->type)  }}</span><br>
                                                    @if($discount->type==\App\Utility\DiscountType::discountCode)
                                                        <span>{{ isset($discount->discountCode) && !empty($discount->discountCode) && isset($discount->discountCode[0]) ? $discount->discountCode[0]->code : "null" }}</span>

                                                    @elseif($discount->type==\App\Utility\DiscountType::discountTime)
                                                        @if(isset($discount->discountTime) && !empty($discount->discountTime) && isset($discount->discountTime[0]))
                                                            <span>{{  Verta::createTimestamp($discount->discountTime[0]->expire_date)->format('Y/m/j H:i:s') }}</span>
                                                        @endif
                                                    @elseif($discount->type==\App\Utility\DiscountType::discountCodeTime)
                                                        @if(isset($discount->discountCode) && !empty($discount->discountCode) && isset($discount->discountCode[0]) && isset($discount->discountCode[0]->discountCodeTime[0]) )
                                                            @lang('cms.expire_date') :
                                                            <span>{{  Verta::createTimestamp($discount->discountCode[0]->discountCodeTime[0]->expire_date)->format('Y/m/j H:i:s')}}</span>
                                                        @endif
                                                        <br>
                                                        @if(isset($discount->discountCode) && !empty($discount->discountCode) && isset($discount->discountCode[0]))
                                                            @lang('cms.code-single') :
                                                            <span>{{ $discount->discountCode[0]->code }}</span>
                                                        @endif
                                                    @elseif($discount->type==\App\Utility\DiscountType::coupon)
                                                        @if(isset($discount->coupon) && !empty($discount->coupon) && isset($discount->coupon[0]))
                                                            @lang('cms.code-coupon') :
                                                            <span>{{  $discount->coupon[0]->code }}</span><br>
                                                        @endif
                                                        @if(isset($discount->coupon) && !empty($discount->coupon) && isset($discount->coupon[0]))
                                                            @lang('cms.coupon-expire_date') :
                                                            <span>{{  Verta::createTimestamp($discount->coupon[0]->expire_date)->format('Y/m/j H:i:s') }}</span>
                                                        @endif
                                                    @elseif($discount->type==\App\Utility\DiscountType::amazing)
                                                        @if(isset($discount->discountTime) && !empty($discount->discountTime) && isset($discount->discountTime[0]))
                                                            @lang('cms.expire_date') :
                                                            <span>{{ \App\Http\Controllers\Admin\DiscountController::convertToJalali(\App\Http\Controllers\Admin\DiscountController::TimestampToMiladi($discount->discountTime[0]->expire_date)) }}</span>
                                                        @endif
                                                    @endif
                                                    <hr>

                                                    <label for=""> @lang('cms.requester') : </label>
                                                    <p> {{ $discount->user->name . " " . $discount->user->family . " " . "-" . $discount->user->email }} </p>

                                                    <h5 class="heading-discount">@lang('cms.discount-on') :
                                                        <img src="{{ url('admin_theme/img/discount.png') }}" height="40"
                                                             width="40"
                                                             class="discount-img">

                                                    </h5>

                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>تخفیف روی</th>
                                                            <th>وضعیت</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach($discount->disable as $itemDiscount)
                                                            <tr>
                                                                <td>{{ $loop->iteration.')' }}</td>
                                                                <td>
                                                                    @if($discount->discountable_type == \App\Utility\DiscountType::product)
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
                                                                        ---
{{--                                                                        <button--}}
{{--                                                                                class="btn btn-xs btn-info"--}}
{{--                                                                                data-toggle="modal"--}}
{{--                                                                                href="#details{{ $itemDiscount->id }}">--}}
{{--                                                                            @lang('cms.details')--}}
{{--                                                                        </button>--}}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
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
                            @endforeach

                        @else
                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                        @endif
                        <div class="container">
                            <div class="pull-left">
                                @if(isset($discounts) && $discounts->count() > 0)
                                    <span style="margin-right: 45%">{!! $discounts->render() !!}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->


@stop

