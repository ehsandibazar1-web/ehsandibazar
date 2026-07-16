@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel padding-bottom-panel">
                <br>
                <header class="panel-heading">
                    @lang('cms.list-request-product')
                    <a type="button" class="btn btn-xs btn-success top-left"
                       href="{{route('panel.customer.create',['id' => $findProduct->id])}}">@lang('cms.insert-customer')
                    </a>
                    <a class="btn btn-xs btn-info pull-left margin-top-1"
                       href="{{route('panel.product.index')}}">@lang('cms.back')</a>
                </header>


            </section>
        </div>

        @include('generals.allErrors')
        @include('generals.sessionMessage')

        <div class="container">
            <section class="panel">
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.title')</th>
                            <th>@lang('cms.featuring-image')</th>
                            <th>@lang('cms.category')</th>
                            <th>@lang('cms.customer')</th>
                            <th>@lang('cms.count-request-product-variety')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($variationsFind) && !empty($variationsFind) && count($variationsFind) > 0 )
                            <?php  $arrayUniqUser = [] ?>
                            @foreach($variationsFind as $val)
                                @if(!empty($val->user) && !in_array($val->user_id , $arrayUniqUser) )
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                       <td> <a target="_blank" href="{{route('site.products' , ['slug' => $val->product->slug])}}">{{ str_limit($val->product->title , 20) }}</a></td>
                                        <td>
                                            @if(!empty($val->product->image) && !empty($val->product->image))
                                                <img class="fit-image" src="{{$val->product->image[0]->url}}"
                                                     alt="{{$val->product->title}}">
                                            @endif
                                        </td>
                                        <td>{{ \App\Utility\parentName::getCategoryProductName($val->product->category_id) }}</td>

                                        {{--<td> {{ \App\Utility\ProductType::productType($val->product->type)  }} </td>--}}
                                        {{-- <td>
                                             <a href="{{route('panel.customer.status' , ['id' => $val->id])}}">{{  \App\Utility\Status::getStatus($val->status)  }}</a>
                                         </td>--}}

                                        <td>{{ $val->user->name }}</td>

                                       <td>
                                           <span class="badge badge-info"> {{ \App\Utility\Variation::countUserRequestVariation($findProduct->id,$val->user->id) }}</span>
                                       </td>

                                        <td>
                                                <a class="btn btn-warning btn-xs" title="@lang('cms.edit')"
                                                   href="{{ route('panel.customer.edit',['id'=>$val->id ]) }}"><i
                                                        class="icon-edit "></i></a>

                                                <button class="btn btn-info btn-xs" title="@lang('cms.show')"
                                                        data-toggle="modal"
                                                        href="#show{{$val->id}}"><i class="icon-eye-open "></i></button>

                                                <button class="btn btn-danger btn-xs" title="@lang('cms.delete')"
                                                        data-toggle="modal"
                                                        href="#delete{{$val->id}}"><i class="icon-trash "></i></button>

                                            {{-- <a href="{{route('panel.product.seller',['id' => $val->id])}}" class="btn btn-xs btn-default" title="@lang('cms.seller-this-product')">
                                                 <span class="badge-danger">{{\App\Utility\VariationStatus::variationStatus($val->id)}}</span>
                                                 <i class="icon-star"></i>
                                             </a>--}}

                                        </td>
                                    </tr>
                                    <?php $arrayUniqUser [] = $val->user->id ?>
                                @endif
                            @endforeach

                        @endif
                        </tbody>

                    </table>

                    @if(isset($variationsFind) && !empty($variationsFind) && count($variationsFind) > 0)
                        <span style="margin-right: 45%">{!! $variationsFind->render() !!}</span>
                    @endif


                </div>
            </section>
        </div>


        <!-- Modal show -->
        @if(isset($variationsFind) && !empty($variationsFind) && count($variationsFind) > 0)
            @foreach($variationsFind as $val)
                <div class="modal fade" id="show{{ $val->id }}" tabindex="-1" role="dialog"
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

                                <div class="container-fluid">
                                    <div class=" col-md-12 text-center">
                                        @if(!empty($val->product->image))
                                            <img class="fit-image" src="{{$val->product->image[0]->url}}"
                                                 alt="{{$val->product->title}}">
                                            <br>
                                            <br>
                                        @endif
                                    </div>
                                </div>

                                <div class="container-fluid">
                                    @php
                                        $variationUser = \App\Model\Variation::where('product_id',$findProduct->id)
                                        ->where('user_id' , $val->user_id)->get();
                                    @endphp
                                    {{-- todo reade store name --}}
                                    <p class="request_user"> @lang('cms.request-customer') :
                                        <span> {{ $variationUser[0]->user->name  . " - " . $variationUser[0]->user->email  }} </span>

                                        <span class="img-reqeust-product"> <img class="pull-left"
                                                                                src="{{url('admin_theme/img/product-customer.png')}}"
                                                                                width="24"
                                                                                alt="request-product"> </span>
                                    </p>

                                    <table class="table table-hover">
                                        <tr>
                                            <th>@lang('cms.num')</th>
                                            <th>@lang('cms.title')</th>
                                            <th> @lang('cms.color') </th>
                                            <th>@lang('cms.size')</th>
                                            <th>@lang('cms.count')</th>
                                            <th>@lang('cms.price')(@lang('cms.tooman'))</th>
                                            <th> @lang('cms.show-more')</th>
                                        </tr>
                                        @foreach($variationUser as $itemVariation)
                                            <tr>
                                                <td> {{ $loop->iteration }} </td>
                                                <td> {{ str_limit($itemVariation->product->title,20) }} </td>
                                                <td>
                                                    @if(\App\Utility\Variation::typeOfVariation($itemVariation->attributeTypeValue->attribute_type_id))
                                                        {{$itemVariation->attributeTypeValue->value}}
                                                    @else
                                                        {{"-"}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(\App\Utility\Variation::typeOfVariation($itemVariation->attributeTypeValue->attribute_type_id))
                                                        {{ isset($itemVariation->relatedVariations) && !empty($itemVariation->relatedVariations[0]->attribute_type_value_id) ? $itemVariation->relatedVariations[0]->attributeTypeValue->value : null }}
                                                    @else
                                                        {{$itemVariation->attributeTypeValue->value}}
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
                                                        <a attr-datas="showCustomerDetails{{$itemVariation->id}}"
                                                           class="btn btn-xs btn-primary desc-customers"
                                                           title="@lang('cms.show')"><i
                                                                class="icon-eye-open"></i> </a>
                                                        <button class="btn btn-xs btn-danger delete-customer" type="submit"
                                                                title="@lang('cms.delete')"><i class="icon-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>
                                            <div class="modals display-none showCustomerDetails{{$itemVariation->id}}">
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
                                    <hr>
                                </div>

                                <p class="product_description"> @lang('cms.product-description') :
                                    <span class="product-desc-img"><img src="{{url('admin_theme/img/pricing.png')}}"
                                                                        alt="product-description"></span>
                                </p>

                                <div class="container-fluid">
                                    <p> @lang('cms.title') : <span> {{$val->product->title}} </span></p>
                                    <p> @lang('cms.category') : <span> {{$val->product->categories[0]->title}} </span>
                                        <span class="btn btn-xs btn-info pull-left" data-toggle="modal"
                                              href="#detail{{$val->id}}"> @lang('cms.details') </span></p>

                                </div>


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
        @if(isset($variationsFind) && !empty($variationsFind) && count($variationsFind) > 0)
            @foreach($variationsFind as $val)
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
                                <form action="{{ route('panel.customer.allVariationDelete' , ['id' => isset($val->user) && !empty($val->user->id) ? $val->user->id : null])  }}"
                                      method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">

                                    <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                           class="btn btn-danger">
                                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                                        @lang('cms.cancel')
                                    </button>
                                    <input type="hidden" name="product_id" value="{{$findProduct->id}}">
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
        @if(isset($variationsFind) && !empty($variationsFind) && count($variationsFind) > 0)
            @foreach($variationsFind as $val)
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
                                @foreach($val->product->categories[0]->attributes as $item)
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


    {{-- description in show box --}}
    <script>
        var thiss;
        $('a.desc-customers').click(function () {
            thiss = $(this).attr('attr-datas');
            //alert(thiss);
            $('.' + thiss).animate({right: '240px'}).fadeIn('3000').css('display', 'block');
        });

        $('.closeDivision').click(function (e) {
            e.preventDefault();
            $('.modals').fadeIn('3000').css('display', 'none');
        });

    </script>


    {{-- alert for delete in show box --}}
    <script>

        $('.delete-customer').click(function (e) {
            if(confirm("{{\Illuminate\Support\Facades\Lang::get('cms.question-delete')}}")){
                // do
            }else{
                // nothing
                e.preventDefault();
            }
        });

    </script>

@endsection

