@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت | دیدگاه ها
@endsection--}}
@section('content')

    <div class="row">

        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.payments')
                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                </header>
                <div ng-controller="myCtrl" ng-app="myApp" class="panel-body">

                    <p class="alert alert-info border-right-info">
                         ابتدا تعداد آگهی(محصول) درخواستی خود را وارد کرده پس از مشاهده  قیمت  روی دکمه " پرداخت " کلیک کنید , پس از پرداخت می توانید محصول خود را در سایت منتشر کنید.
                    </p>

                    @if(isset($priceForProduct) && !empty($priceForProduct) && $priceForProduct > 0)
                        <p class="alert alert-default border-right-dark">  قیمت ثبت هر آگهی (محصول) <span> {{  number_format($priceForProduct)  }} </span>  تومان می باشد.  </p>
                    @endif



                    <br>

                    <form action="{{ route('panel.payment.store')  }}" method="post">
                        @csrf

                        <div class="form-group">

                            <div class="col-md-2">
                                <label for="">@lang('cms.count-request-product')</label>
                            </div>
                            <div class="col-md-10">
                                <input min="0" name="numberOfProduct" ng-model="number" class="form-control text-center input-font" value="number_of_product" type="number">
                            </div>
                            <br><br>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn btn-info pull-left">@lang('cms.payment')</button>
                            </div>
                        </div>

                    </form>

                    <br>
                    <hr>

                    <div class="clearfix"></div>

                    <div class="col-md-12">
                        <p class="total"> @lang('cms.total') : <span ng-bind="number * {{ isset($priceForProduct) ? $priceForProduct : null  }} | currency : '' :  0  " class="totalS">0</span>
                            <span><img class="total-img" src="{{url('admin_theme/img/calculator.png')}}"
                                       alt="calculator"></span>
                        </p>
                    </div>


                </div>
            </section>
        </div>
    </div>



@endsection


@section('admin-js')
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>

    <script>

        var app = angular.module('myApp', []);
        app.controller('myCtrl', function ($scope) {
            $scope.number = "0";
        });

    </script>

@endsection
