@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel padding-bottom-panel">
                <br>
                <header class="panel-heading">
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    @lang('cms.header-list-product')
                    <a type="button" class="btn btn-xs btn-success top-left" data-toggle="modal"
                       href="{{route('panel.product.create')}}">@lang('cms.create-new-item')
                    </a>

                </header>
{{--                <p class="alert alert-default border-right-dark bg-auction">کالا هایی که با این رنگ نمایش داده میشوند--}}
{{--                    کالا های مزایده میباشند</p>--}}
            </section>
        </div>

        @include('generals.allErrors')
        @include('generals.sessionMessage')

        <div class="container">
            <section class="panel">
                <div class="panel-body">
                    <table class="table table-hover"  id="datatable">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.title')</th>
                            <th>@lang('cms.featuring-image')</th>
                            <th>@lang('cms.category')</th>
                            <th>@lang('cms.brand')</th>
                            <th>@lang('cms.type-product')</th>
                            <th>موجودی</th>
                            <th>@lang('cms.status')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($products) && count($products) > 0)
                            @foreach($products as $val)

                                <tr {{ $val->type == \App\Utility\ProductType::AUCTION ? 'class=bg-auction' : null }}>
{{--                                    <td>{{ $loop->iteration }}</td>--}}
                                    <td>{{ $val->id }}</td>
                                    <td><a target="_blank"
                                           href="{{ $val->path() }}">{{ str_limit($val->title , 40) }} </a></td>

                                    <td>
                                        @if(isset($val->image[0]) && !empty($val->image[0]))
                                            <img class="fit-image" src="{{$val->image[0]->url}}" alt="{{$val->title}}">

                                        @endif
                                    </td>

                                    <td>{{ $val->categories[0]->title }}</td>
                                    <td>{{ $val->brand->title }}</td>
                                    <td>{{ \App\Utility\ProductType::productType($val->type) }}</td>
                                    <td>{{ isset($val->variations[0]) ? $val->variations[0]->count : '' }}</td>
                                    <td>
                                        <a href="{{route('panel.product.status' , ['id' => $val->id])}}">{{  \App\Utility\Status::getStatus($val->status)  }}</a>
                                    </td>

                                    <td>
                                        
                                          @can('update')
                                        <a class="btn btn-warning btn-xs" title="@lang('cms.edit')"
                                           href="{{ route('panel.product.edit',['id'=>$val->id ]) }}"><i
                                                class="icon-edit "></i></a>
                                                @endcan

                                        <button class="btn btn-info btn-xs" title="@lang('cms.show')"
                                                data-toggle="modal"
                                                href="#show{{$val->id}}"><i class="icon-eye-open "></i></button>
                                                
                                                  @can('delete')
                                        <button class="btn btn-danger btn-xs" title="@lang('cms.delete')"
                                                data-toggle="modal"
                                                href="#delete{{$val->id}}"><i class="icon-trash "></i></button>
                                                
                                                @endcan


{{--                                        <a href="{{route('panel.customer',['id' => $val->id])}}"--}}
{{--                                           class="btn btn-xs btn-default"--}}
{{--                                           title="@lang('cms.count-seller-this-product')">--}}
{{--                                            <span class="badge-danger">--}}
{{--                                                 {{  \App\Utility\Variation::countVariation($val->id)  }}--}}
{{--                                            </span>--}}
{{--                                            <i class="icon-star"></i>--}}
{{--                                        </a>--}}

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>

                    </table>

{{--                    @if(isset($products) && count($products) > 0)--}}
{{--                        <span style="margin-right: 45%">{!! $products->render() !!}</span>--}}
{{--                    @endif--}}

                </div>
            </section>
        </div>


        <!-- Modal show -->
        @if(isset($products) && count($products) > 0)
            @foreach($products as $val)
                <div class="modal fade" id="show{{ $val->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog screens">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title">@lang('cms.show-details')</h4>
                            </div>
                            <div class="modal-body">

                                <div class="container-fluid">
                                    <div class=" col-md-12 text-center">
                                        @if(isset($val->image[0]) && !empty($val->image[0]) && count($val->image) > 0)
                                            @foreach($val->image as $image)
                                                <a href="{{ $image->url }}" target="_blank">
                                                    <img class="fit-image" src="{{$image->url}}" alt="{{$val->title}}">
                                                </a>
                                            @endforeach
                                            <br>
                                            <br>
                                        @endif
                                    </div>
                                </div>


                                <div class="container-fluid">
                                    @php
                                        $variationUser = \App\Model\Variation::with(['user'])->where('product_id',$val->id)->get();
                                    @endphp
                                    {{-- todo reade store name --}}
                                    <p class="request_user"> @lang('cms.request-customer-this-product')

                                        <span class="img-reqeust-product"> <img class="pull-left"
                                                                                src="{{url('admin_theme/img/product-customer.png')}}"
                                                                                width="24"
                                                                                alt="request-product"> </span>
                                    </p>

                                    @if(isset($variationUser) && count($variationUser) > 0)
                                        <table class="table table-hover table-hover-back">
                                            <tr>
                                                <th>@lang('cms.num')</th>
                                                <th>@lang('cms.customer')</th>
                                                <th> @lang('cms.color') </th>
                                                <th>@lang('cms.size')</th>
                                                <th>@lang('cms.count')</th>
                                                <th>@lang('cms.price')(@lang('cms.tooman'))</th>
                                                <th> @lang('cms.operation')</th>
                                            </tr>
                                            @foreach($variationUser as $itemVariation)
                                                @if(isset($itemVariation->user) && !empty($itemVariation->user) && in_array($itemVariation->user->level , \App\Utility\Level::levelAdmins()) )
                                                    <tr class="admin-back">
                                                        <td> {{ $loop->iteration }} </td>
                                                        <td> {{ $itemVariation->user->name }} </td>
                                                        <td>
                                                            @if(isset($itemVariation->attributeTypeValue->value) && \App\Utility\Variation::typeOfVariation($itemVariation->attributeTypeValue->attribute_type_id))
                                                                {{$itemVariation->attributeTypeValue->value}}
                                                            @else
                                                                {{"-"}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($itemVariation->relatedVariations[0]->attributeTypeValue->value) && \App\Utility\Variation::typeOfVariation($itemVariation->attributeTypeValue->attribute_type_id))
                                                                {{ isset($itemVariation->relatedVariations) && !empty($itemVariation->relatedVariations[0]->attribute_type_value_id) ? $itemVariation->relatedVariations[0]->attributeTypeValue->value : null }}
                                                            @else
                                                                {{ isset($itemVariation->attributeTypeValue->value) ? $itemVariation->attributeTypeValue->value : '--' }}
                                                            @endif
                                                        </td>
                                                        <td> {{$itemVariation->count}} </td>
                                                        <td> {{number_format($itemVariation->price)}} </td>
                                                        <td>
                                                            <form
                                                                action="{{route('panel.customer.delete' , ['id' => $itemVariation->id])}}"
                                                                method="post">
                                                                @csrf
                                                                {{method_field('delete')}}

                                                                <button class="btn btn-xs btn-danger" type="submit"
                                                                        title="@lang('cms.delete')"><i
                                                                        class="icon-trash"></i>
                                                                </button>

                                                                <a href="{{route('panel.product.edit' , ['id' => $val->id])}}"
                                                                   class="btn btn-xs btn-warning"
                                                                   title="@lang('cms.edit')">
                                                                    <i class="icon-pencil"></i>
                                                                </a>

                                                            </form>
                                                        </td>

                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td> {{ $loop->iteration }} </td>
                                                        <td> {{ $itemVariation->user->name }} </td>
                                                        <td>
                                                            @if(isset($itemVariation->attributeTypeValue->value) &&\App\Utility\Variation::typeOfVariation($itemVariation->attributeTypeValue->attribute_type_id))
                                                                {{$itemVariation->attributeTypeValue->value}}
                                                            @else
                                                                {{"-"}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($itemVariation->relatedVariations[0]->attributeTypeValue->value) && \App\Utility\Variation::typeOfVariation($itemVariation->attributeTypeValue->attribute_type_id))
                                                                {{ isset($itemVariation->relatedVariations) && !empty($itemVariation->relatedVariations[0]->attribute_type_value_id) ? $itemVariation->relatedVariations[0]->attributeTypeValue->value : null }}
                                                            @else
                                                                {{ isset($itemVariation->attributeTypeValue->value) ? $itemVariation->attributeTypeValue->value : '--' }}
                                                            @endif
                                                        </td>
                                                        <td> {{$itemVariation->count}} </td>
                                                        <td> {{number_format($itemVariation->price)}} </td>
                                                        <td>
                                                            <form
                                                                action="{{route('panel.customer.delete' , ['id' => $itemVariation->id])}}"
                                                                method="post">
                                                                @csrf
                                                                {{method_field('delete')}}
                                                                {{--                                                        <a attr-datas="showCustomerDetails{{$itemVariation->id}}"--}}
                                                                {{--                                                           class="btn btn-xs btn-primary desc-customers"--}}
                                                                {{--                                                           title="@lang('cms.show')"><i--}}
                                                                {{--


                                                                                                                            class="icon-eye-open"></i> </a>--}}


                                                                <a href="{{route('panel.customer.edit' , ['id' => $itemVariation->id])}}"
                                                                   class="btn btn-xs btn-primary"
                                                                   title="@lang('cms.edit')">
                                                                    <i class="icon-pencil"></i>
                                                                </a>

                                                                <button class="btn btn-xs btn-danger delete-customer"
                                                                        type="submit"
                                                                        title="@lang('cms.delete')"><i
                                                                        class="icon-trash"></i>
                                                                </button>


                                                            </form>
                                                        </td>

                                                    </tr>
                                                @endif
                                                <div
                                                    class="modals display-none showCustomerDetails{{$itemVariation->id}}">
                                                    <a class="close-header closeDivision"><span class="closer">×</span></a>
                                                    <h3 class="header-customer-product"> توضیحات : </h3>
                                                    <span class="desc-c">
                                                    @if(!empty($itemVariation->description))
                                                            {!! $itemVariation->description !!}
                                                        @else
                                                            <p> @lang('cms.don-have-description') </p>
                                                        @endif
                                                </span>
                                                </div>
                                            @endforeach
                                        </table>
                                    @else
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-4">
                                                    <img src="{{url('admin_theme/img/noCustomer.svg')}}"
                                                         alt="noCustomer">
                                                </div>
                                                <div class="col-md-8">
                                                    <div
                                                        class="text-center alert alert-info border-right-info alert-customer"> @lang('cms.no-customer-for-this-product') </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    <hr>
                                </div>

                                <p class="product_description"> @lang('cms.product-description') :
                                    <span class="product-desc-img"><img src="{{url('admin_theme/img/pricing.png')}}"
                                                                        alt="product-description"></span>
                                </p>

                                <div class="container-fluid">
                                    <p> @lang('cms.title') : <span> {{$val->title}} </span></p>
                                    <p> @lang('cms.category') : <span> {{$val->categories[0]->title}} </span> <span
                                            class="btn btn-xs btn-info pull-left" data-toggle="modal"
                                            href="#detail{{$val->id}}"> @lang('cms.details') </span></p>
                                </div>

                                <div class="container-fluid">
                                    <p> @lang('cms.pic-gallery') :
                                        <span> {{count($val->image) > 1 ? \Illuminate\Support\Facades\Lang::get('cms.have') : \Illuminate\Support\Facades\Lang::get('cms.dont-have')}} </span>
                                    </p>
                                    <p> @lang('cms.video') :
                                        <span> {{count($val->video) > 0 ? \Illuminate\Support\Facades\Lang::get('cms.have') : \Illuminate\Support\Facades\Lang::get('cms.dont-have')}} </span>
                                    </p>
                                    <p> @lang('cms.catalog') :
                                        <span> {{ isset($val->catalog) ? \Illuminate\Support\Facades\Lang::get('cms.have') : \Illuminate\Support\Facades\Lang::get('cms.dont-have')}} </span>
                                    </p>
                                    <p> @lang('cms.brand-2') : <span>{{$val->brand->title}}</span></p>

                                    <p> @lang('cms.created-at') : <span>{{$val->created_at}}</span></p>

                                    <p> @lang('cms.update-at') : <span>{{$val->updated_at}}</span></p>
                                    <p>تعداد فروش: <span>{{$val->soldCount}}</span></p>
                                    <hr>
                                    <p> @lang('cms.description') : <span> {!! $val->description !!} </span></p>
                                </div>

                                @if($val->type == \App\Utility\ProductType::AUCTION)
                                    <hr>
                                    <div class="container-fluid row">
                                        <div class="col-12">
                                            <div class="col-md-5">
                                                <p>نوع محصول
                                                    :<span> {{ \App\Utility\ProductType::productType($val->type) }} </span>
                                                </p>
                                                <p>تاریخ شروع :<span
                                                        class="red-date"> {{ \App\Http\Controllers\Admin\ProductController::convertToJalali((int)$val->auction->start_date) }} </span>
                                                </p>
                                                <p>قیمت واقعی محصول
                                                    :<span> {{ number_format($val->prices)." تومان " }} </span></p>
                                                <p>قیمت شروع
                                                    :<span> {{ number_format($val->auction->start_price)." تومان" }} </span>
                                                </p>
                                                <p>قیمت پایان
                                                    :<span> {{ number_format($val->auction->end_price)." تومان" }} </span>
                                                </p>
                                                <p>مبلغ افزایش قیمت هر کلیک
                                                    :<span> {{ number_format($val->auction->every_click_price)." تومان" }} </span>
                                                </p>
                                                <p>مبلغ هر کلیک برای پرداخت کاربر
                                                    :<span> {{ number_format($val->auction->every_click_price_for_pay)." تومان" }} </span>
                                                </p>
                                                <p>تعداد شرکت کنندگان
                                                    :<span> {{ $val->auction->participant_count }} </span>
                                                </p>
                                                <p>تعداد کلیک
                                                    :<span> {{ $val->auction->click_count }} </span>
                                                </p>
                                                <p>وضعیت مزایده
                                                    :<span> {{ $val->auction->status == 1 ? 'در حال برگزاری' : 'به اتمام رسیده' }} </span>
                                                </p>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="users">شرکت کنندگان : </label>
                                                @if(isset($val->auction->users) && !empty($val->auction->users) && count($val->auction->users) > 0)
                                                    <ul>
                                                        @foreach($val->auction->users as $user)
                                                            <li>{{ $loop->iteration }}
                                                                ) {{ $user->name." ".$user->family." - ".$user->mobile }} </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="alert alert-info">در حال حاضر کسی شرکت نکرده است</span>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <label for="users">نتیجه مزایده : </label>
                                                @if(isset($val->auction->users) && !empty($val->auction->users) && count($val->auction->users) > 0)
                                                    <ul>
                                                        @foreach($val->auction->auctionResult as $item)
                                                            <li class="{{ $item->type == 1 ? 'bg-auction-winner' : 'bg-auction-loser' }}">{{ $loop->iteration }}
                                                                ) {{ $item->user->name." ".$item->user->family." - ".$item->user->mobile }} </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="alert alert-default">در حال حاضر چیزی ثبت نشده</span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                @endif


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                                    @lang('cms.close')
                                </button>
                            </div>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
    </div>
    <!-- /.modal delete -->
    @endforeach
    @endif


    <!-- Modal delete -->
    @if(isset($products) && count($products) > 0)
        @foreach($products as $val)
            <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                            </button>
                            <h4 class="modal-title">@lang('cms.alert')</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                @lang('cms.question-delete')
                            </p>
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('panel.product.delete' , ['id' => $val->id])  }}"
                                  method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">

                                <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                       class="btn btn-danger">
                                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                                    @lang('cms.cancel')
                                </button>
                            </form>
                        </div>


                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            </div>
            <!-- /.modal delete -->
        @endforeach
    @endif



    <!-- Modal detail -->
    @if(isset($products) && count($products) > 0)
        @foreach($products as $val)
            <div class="modal fade" id="detail{{ $val->id }}" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                            </button>
                            <h4 class="modal-title">@lang('cms.show-details')</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                            @lang('cms.attribute-category-2')
                            @foreach($val->categoryproduct->attributes as $item)
                                <p> {{ $loop->iteration . ") " . $item->name }} </p>
                                @endforeach
                                </p>
                        </div>
                        <div class="modal-footer">


                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                                @lang('cms.close')
                            </button>
                        </div>


                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            </div>
            <!-- /.modal delete -->
            @endforeach
            @endif


            </div>

@endsection


@section('admin-js')

    {{-- alert for delete in show --}}
    <script>

        $('.delete-customer').click(function (e) {
            if (confirm("{{\Illuminate\Support\Facades\Lang::get('cms.question-delete')}}")) {
                // do
            } else {
                // nothing
                e.preventDefault();
            }
        });

    </script>
@endsection

