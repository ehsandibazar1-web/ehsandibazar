@extends('panel-old.layout.master')
{{--@section('title')
   @lang('cms.info-payment')
@endsection--}}
@section('admin-css')
    <link rel="stylesheet" href="{{url('admin_theme/css/checkout.css')}}">
@endsection
@section('content')

    @if(isset($numberOfProduct) && isset($priceForProduct) && !empty($numberOfProduct) && !empty($priceForProduct) && $priceForProduct > 0 && $numberOfProduct > 0  )
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">
                <div class="col-md-12">
                    <a class="btn btn-xs btn-info pull-left margin-top-1" href="{{route('panel.payment.index')}}">@lang('cms.back')</a>
                </div>
                <header class="panel-heading">
                    @lang('cms.info-detail-payment')
                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                </header>
            </section>

            <div class="panel-body">
                <div class="cards" data-attrs="s">
                    <br>
                    <img src="{{url('admin_theme/img/payments.png')}}" alt="John">
                    <h1 class="details-cart">جزییات خرید</h1>
                    <table class="table table-hover table-bordered">
                        <tr>
                            <th class="text-center">تعداد آگهی (محصول)</th>
                            <th class="text-center">قیمت</th>
                        </tr>
                        <tr>
                                <td class="text-center">{{ $numberOfProduct}}</td>
                                <?php $totalAmount = $numberOfProduct * $priceForProduct;  ?>
                                <td class="text-center">{{  number_format($totalAmount) . " " . "تومان" }}</td>
                        </tr>
                    </table>
                    <div style="margin: 24px 0;">
                        <a href="#"><i class="fa fa-dribbble"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-linkedin"></i></a>
                        <a href="#"><i class="fa fa-facebook"></i></a>
                    </div>
                    <p>
                    <form action="{{route('panel.zarin.seller')}}" method="post">
                        @csrf
                        <button>@lang('cms.continue')</button>
                        <input type="hidden" name="numberOfProduct" value="{{$numberOfProduct}}">
                    </form>
                    </p>
                </div>


            </div>

        </div>
    </div>
    @endif

@endsection
