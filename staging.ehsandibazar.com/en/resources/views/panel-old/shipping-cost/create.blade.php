@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    {{ $title }}

                    <a class="btn btn-xs btn-primary pull-left" href="{{route('panel.shippingCost.index')}}">فهرست</a>
                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')


                <div class="panel-body">
                    <div class=" form">

                        @if(isset($find) && !empty($find))
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.shippingCost.update' , ['id' => $find->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.shippingCost.store')}}">

                                        @endif

                                        @csrf

                                        <div class="form-group ">
                                            <label for="of_weight" class="control-label col-lg-2">از وزن</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($find) && !empty($find) ? $find->of_weight : null }}"
                                                       id="of_weight" name="of_weight" type="number" required/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="upto_weight" class="control-label col-lg-2">تا وزن</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($find) && !empty($find) ? $find->upto_weight : null }}"
                                                       id="upto_weight" name="upto_weight" type="number" required/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="price" class="control-label col-lg-2">هزینه یا قیمت</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($find) && !empty($find) ? $find->price : null }}"
                                                       id="price" name="price" type="number" required/>
                                            </div>
                                        </div>


                                        <div class="form-group ">
                                            <label for="upto_weight" class="control-label col-lg-2">نوع پست</label>
                                            <div class="col-lg-10">
                                                <select name="type" class="form-control">
                                                    @foreach(\App\Model\ShippingCost::TYPES as  $key => $type)
                                                        @if($type != \App\Model\ShippingCost::TYPES['bikeDelivery'])
                                                            <option value="{{$type}}" {{ isset($find) && $find->type == $type ? 'selected' : null }}>@php \App\Model\ShippingCost::$preventAttrSet= false @endphp {{ \App\Model\ShippingCost::getTypeAttribute($type) }} @php \App\Model\ShippingCost::$preventAttrSet= true @endphp</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="upto_weight" class="control-label col-lg-2">نوع ارسال</label>
                                            <div class="col-lg-10">
                                                <select name="post_type" class="form-control">
                                                    @foreach(\App\Model\ShippingCost::TYPES_OF_POST as $type)
                                                        <option value="{{$type}}" {{ isset($find) && $find->post_type == $type ? 'selected' : null }}>@php \App\Model\ShippingCost::$preventAttrSet= false @endphp {{ \App\Model\ShippingCost::getPosttypeAttribute($type) }} @php \App\Model\ShippingCost::$preventAttrSet= true @endphp</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            @if(isset($find))
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-warning pull-left" type="submit"
                                                           value="@lang('cms.edit')">
                                                </div>
                                            @else
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-success pull-left" type="submit"
                                                           value="@lang('cms.save')">
                                                </div>
                                            @endif

                                        </div>
                                    </form>

                    </div>

                </div>

            </section>
        </div>
    </div>

@endsection

