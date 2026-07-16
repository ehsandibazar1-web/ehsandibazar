<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   
    <style>
table tr td {
  font-size: 12pt;
  /*font-family: 'iransans';*/
}
.card-body {
    padding: 0 20px;
}
        
body {


     font-family: 'iransans', Sans-serif;
    font-weight: normal;
    font-size: 13px;

}
.card {

    border: 1px solid #ccc;
    border-radius: 5px;
}
        .print-items ul li {
            display: inline-block;
            /*width: 37px;*/
            height: 37px;
            list-style: none;
            text-align: center;
            line-height: 37px;
            vertical-align: middle;
            margin: 0 10px;
        }

        .item-count {
            border: 1px solid #000;
            min-width:25px;
            display:inline-block;
            margin:0 2px;
            text-align: center;
padding: 0 !important;
        }

     .print-items span {
    line-height: 1.7;
} 

        .img-box {
            position: absolute !important;
            left: 28px !important;
            top: 0 !important;
        }
       
       
       .grid {

}

/* clear fix */
.grid:after {
  content: '';
  display: block;
  clear: both;
}

/* ---- .grid-item ---- */

.grid-sizer,
.grid-item {
  width: 49.333%;

}

.grid-item {
  float: right;
}
.print-item.grid-item {
    position: relative;
}
 .card {

    border: 1px solid #ccc;
    border-radius: 5px;
    width:90%;
    margin:20px auto;
    min-height:280px;
    position:relative;

 }
    </style>
</head>
<body>

<div class="container">

       
        @foreach($orders->chunk(2) as $key => $orders)
           <div class="row grid">
            @foreach($orders as $key => $order)
                <div class="{{ $key %2==0 ? 'print-item' : 'print-item-l' }} grid-item">
            <div class="card">
                <div class="card-body">
                        @if(isset($order->shippingMethod))
                    <span class="img-box">
                        <img src="{{ $order->shippingMethod->code5 }}"
                             class="pull-left float-left img-responsive "
                             width="50">
                    </span>

                @endif
                <br>
                 @php
                    $address = unserialize($order->user_info)['addressSession'];
                @endphp
                <div style="font-weight: bold;margin-top:20px;font-size:18px;">گیرنده :
                <div style="display:inline-block;font-size:13px;font-weight: 400;">
                    {{$address->province->name . " ". $address->city->name." ".$address->fullAddress}}
                </div>
                </div>
               
                <p style="display:block;text-align:center;color:#000;margin-top:10px;font-family:iransans;font-weight:600;">{{ $order->tracking_code }}</p>
                <label for="code_posti">کد پستی :</label>
                <span id="code_posti">{{ $address->postal_code }} - {{ $address->name }}</span>
                <br>
                <span>{{ $address->mobile }}-{{ $address->tell }}</span>
                 <hr>
                <div class="print-items" style="margin:10px 0;">
                   
                        @foreach($order->orderItem as $item)
                            @php
                                $product = unserialize($item->details)
                            @endphp
                            <span>{{$product['item']->title}}&nbsp;<span class="item-count" style="width:20px;padding:0 20px;display:inline-block;margin:0 2px;">&nbsp;{{$product['qty']}}&nbsp;</span>
                            </span>
                        @endforeach
                  
                </div>
                </div>
            </div>

            </div>
            @endforeach
         </div>
        @endforeach
  
</div>

</body>
</html>
