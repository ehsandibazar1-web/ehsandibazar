<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet"
          id="bootstrap-css">

    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <style type="text/css">
        * {
            direction: rtl !important;
        }

        body {
            background-image: none !important;
            font-family: 'iransans', Sans-serif;

        }

        .total-price {
            font-weight: 500;
            font-size: 16px;
        }

        .tg {
            border-collapse: collapse;
            width: 100%;
            direction: rtl
        }

        .tg td {
            border-width: 1px;
            font-size: 14px;
            padding: 10px 5px;
            word-break: normal;
        }

        .tg th {
            border-width: 1px;
            font-size: 14px;
            font-weight: normal;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        .tg .tg-0pky {
            text-align: right;
            vertical-align: middle;
        }

        .tg .tg-0lax {
            text-align: center;
            vertical-align: middle;
        }

  .min-hight{
        min-height: 115vh;
  }


.sheet {
 margin: 0;
  overflow: hidden;
  position: relative;
  box-sizing: border-box;
  page-break-after: always;
}
td.tg-0pky.iteration {

    border: 1px solid #a5a3a3;
    text-align: center;
}
/** Paper sizes **/
body.A3           .sheet { width: 297mm; height: 419mm }
body.A3.landscape .sheet { width: 420mm; height: 296mm }
body.A4           .sheet { width: 210mm; height: 296mm }
body.A4.landscape .sheet { width: 297mm; height: 209mm }
body.A5           .sheet { width: 148mm; height: 209mm }
body.A5.landscape .sheet { width: 210mm; height: 147mm }

/** Padding area **/
.sheet.padding-10mm { padding: 10mm }
.sheet.padding-15mm { padding: 15mm }
.sheet.padding-20mm { padding: 20mm }
.sheet.padding-25mm { padding: 25mm }

/** For screen preview **/
@media screen {

  .sheet {
    background: white;
    box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
    margin: 5mm;
  }
}

/** Fix for Chrome issue #273306 **/
@media print {
           body.A3.landscape { width: 420mm }
  body.A3, body.A4.landscape { width: 297mm }
  body.A4, body.A5.landscape { width: 210mm }
  body.A5                    { width: 148mm }
  .A5 tr td{max-width:50px;}
  hr{ margin:0px;}
  br.vv {
    display: none;
}
tg td {

    font-size: 12px;
}
td.tg-0pky.title{
     max-width:320px;  
}
p{
    
    max-width: 220px;
    white-space: inherit;
    overflow-wrap: break-word;   
}

td.tg-0pky.iteration {
    width: 41px;
        border: 1px solid #a5a3a3;
    text-align: center;
}
td.tg-0pky.title {
    width: 40%;
}
.bright{
    border-right: 2px solid #a5a3a3;
}
}
td.tg-0pky.aa {
    max-width: 200px;
}
.tg td {

    padding: 5px 5px;

}

@page { size: A5 }
    </style>
</head>
<body dir="rtl" class="A5">

<div class="container ">
    @foreach($orders as $order)
        <div class="row min-hight">
            <table class="tg">

                <tr class="">
                    <th class="tg-0pky" colspan="4">
                        <span><img src="http://kaci.ir/storage/photos/1/images/project/footer-logo.png" width="70"></span>
                       <span> فاکتور فروش</span>
                    </th>

                </tr>
    <tr>
         <td class="tg-0pky" colspan="4"><hr></td>
    </tr>

                <tr>
                    <td class="tg-0pky aa">شماره سفارش:{{ $order->tracking_code }}</td>
              
                    <td class="tg-0pky aa" colspan="2">نام و نام
                        خانوادگی:{{ $order->user->name." ".$order->user->family }}</td>


                </tr>
                <tr>
                    <td class="tg-0pky aa">تاریخ:{{ $order->created_at }}</td>
            
                    <td class="tg-0pky aa" colspan="2">تلفن:{{ unserialize($order->user_info)['addressSession']->tell." - ".unserialize($order->user_info)['addressSession']->mobile }}</td>


                </tr>
                <tr>
                    <td class="tg-0pky aa">وضعیت سفارش:{{ \App\Utility\Status::getOrderStatus($order->status) }}</td>
                  
                    <td class="tg-0pky aa" colspan="2">
                        کد پستی : {{ unserialize($order->user_info)['addressSession']->postal_code }}
                        <br >
                        نشانی:{{ unserialize($order->user_info)['addressSession']->province->name." ".
    unserialize($order->user_info)['addressSession']->city->name . " ".
    unserialize($order->user_info)['addressSession']->fullAddress}}</td>


                </tr>

            </table>

            <table class="tg">
                <tr>
                    <td class="tg-0pky" colspan="5">
                        <hr>
                    </td>

                </tr>

                @php
                    $totalPrice = 0;
                    $totalDiscount = 0;
                @endphp
                @foreach($order->orderItem as $orderItems)
                    @if(isset($orderItems->details) && !empty($orderItems->details))
                        @php $unserializeOrderItems = unserialize($orderItems->details); @endphp
                        <tr class="bright">
                            <td class="tg-0pky iteration"
                               >{{$loop->iteration}}</td>

                            <td class="tg-0pky title" style="border: 1px solid #a5a3a3;text-align:center;">
                                {{ $unserializeOrderItems['item']->title }}

                                @if(isset($orderItems->product) && !empty($orderItems->product->package_detail))
                                    ({!! $orderItems->product->package_detail !!})
                                @endif
                                <br class="vv">
                                {{isset( $unserializeOrderItems['item']->AttributeValue) ?  "رنگ :" . $unserializeOrderItems['item']->AttributeValue : null }}
                                <br class="vv">
                                {{ isset($unserializeOrderItems['item']->relatedVariationValue) ? "سایز".$unserializeOrderItems['item']->relatedVariationValue : null  }}
                            </td>

                            <td class="tg-0pky"
                                style="border: 1px solid #a5a3a3;text-align:center;">{{number_format($orderItems->product->variations[0]->price)}}
                                تومان
                            </td>

                            <td class="tg-0pky" style="border: 1px solid #a5a3a3;text-align:center;">تعداد
                                : {{$unserializeOrderItems['qty']}} </td>
                            <td class="tg-0pky" style="border: 1px solid #a5a3a3;text-align:center;">
                                @if(isset($orderItems->amount_discount) && !empty($orderItems->amount_discount) && !$orderItems->order->user->isColleague())
                                    {!! number_format($orderItems->amount_discount*$unserializeOrderItems['qty']) !!}
                                @else
                                    {!! number_format($orderItems->amount*$unserializeOrderItems['qty']) !!}
                                @endif
                                تومان
                            </td>
                        </tr>
                    @endif
                    @php
                        $totalPrice += $unserializeOrderItems['price'];
                        $totalDiscount += isset($orderItems->amount_discount) && !empty($orderItems->amount_discount) ? $unserializeOrderItems['item']->price - $orderItems->amount_discount : 0;
                    @endphp
                @endforeach

                <tr>
                    <td class="tg-0pky"></td>
                    <td class="tg-0pky"></td>
                    <td class="tg-0lax"></td>
                    <td class="tg-0pky"></td>
                    <td class="tg-0pky"></td>
                </tr>
                @if(isset($order->shippingCost) && $order->shippingCost > 0  && !empty($order->shippingCost))
                    <tr>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0lax"></td>
                        <td class="tg-0pky">هزینه ارسال</td>
                        <td class="tg-0pky">{{  number_format($order->shippingCost) . " " . \Illuminate\Support\Facades\Lang::get('cms.tooman') }}</td>

                    </tr>
                @endif

                @if(!empty($totalDiscount) && $totalDiscount > 0)
                    <tr>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0lax"></td>
                        <td class="tg-0pky">تخفیف</td>
                        <td class="tg-0pky">{{ number_format($totalDiscount)."تومان" }}</td>
                    </tr>
                @endif

                @if(unserialize($order->user_info)['userLogin']->isColleague())
                    <tr>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0lax"></td>
                        <td class="tg-0pky">تخفیف همکار</td>
                        <td class="tg-0pky">{{ unserialize($order->user_info)['userLogin']->discount_percent }}
                            %
                        </td>

                    </tr>
                @endif
                @if(isset($order->coupon) && !empty($order->coupon))
                    <tr>

                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0lax"></td>
                        <td class="tg-0pky">کوپن</td>
                        <td class="tg-0pky"> {!!   unserialize($order->coupon)->baseon == \App\Utility\DiscountType::cent ? '<b style=color:red>%</b>'.unserialize($order->coupon)->cent : number_format(unserialize($order->coupon)->cent).' تومان '  !!}</td>

                    </tr>

                @endif
                <tr>

                    <td class="tg-0lax"></td>
                    <td class="tg-0pky"></td>
                    <td class="tg-0pky"></td>
                    <td class="tg-0pky">مبلغ کل</td>
                    <td class="tg-0pky total-price">
                        @if($order->shippingCost > 0 && !is_null($order->shippingCost) )
                            @if(unserialize($order->user_info)['userLogin']->isColleague())
                                @php
                                    $totalPrice = $order->total_discount;
                                @endphp
                            @else
                                @php
                                    $totalPrice = $order->total_discount + $order->shippingCost;
                                @endphp
                            @endif

                            {!! number_format($totalPrice).' تومان' !!}
                        @else
                            {!! number_format($order->total_discount).' تومان' !!}
                        @endif
                    </td>

                </tr>

            </table>
        </div>
    @endforeach
</div>

</body>
</html>
