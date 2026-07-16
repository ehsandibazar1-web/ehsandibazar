<?php

namespace App\Http\Controllers\Api\v1\site;

use App\Events\E_endCountUserDiscountInBasket;
use App\Model\Address;
use App\Model\Coupon;
use App\Model\Discount;
use App\Model\Order;
use App\Model\OrderItem;
use App\Model\Systeminfmanage;
use App\Model\Variation;
use App\User;
use App\Utility\DiscountType;
use App\Utility\forgetSession;
use App\Utility\incrementVariation;
use App\Utility\paymentMethods;
use App\Utility\PaymentStatus;
use App\Utility\serializeAndUnSerialize;
use App\Utility\Status;
use App\Utility\taxCalculate;
use App\Utility\zarinPall;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use SEO;

class BasketZarinPalController extends Controller
{

    protected $urlBack;
    protected $getTax;
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
        $this->urlBack = route('api.basket.check');
        $this->getTax = Systeminfmanage::where('id', 33)->where('status', 1)->first();
    }
    
    public function UpdateAuthority(Request $request){
        $authority = $request->authority;
        $order = $request->orderId;
        $urlPay = $request->urlBack;

         $findOrder = Order::where('id' , $order)->update(['rest_number' => $authority]);
                if($findOrder > 0){
                     response([
                    'status' => 200,
                    'order' => $order, 
                     'authority' => $authority, 
                    'message' => "success..."
                ]);
                    // return redirect($urlPay . $result->Authority);
                }else{
                     response([
                    'status' => 110,
                    'message' => "redirect payment list..."
                ]);
                    return redirect()->route('panel.payment.index');
                }
    }

    public function finishBasket(Request $request)
    {
        $paymentMethods = $request->input('payment');
        if (empty($paymentMethods)){
            $paymentMethods = 0;
        }
        $totalPrice = 0;
        $totalCount = 0;
        $shippingCost = 0;


        $sessionBasket = Auth::user()->basket[0]->session;
        $sessionAddress = Auth::user()->basket[0]->address;
        $sessionCoupon = Auth::user()->basket[0]->coupon;
        $sessionShippingCost = Auth::user()->basket[0]->shippingCost;

        /* start validate session with table variations */
        $resultVariation = $this->validationSessionWithVariation($sessionBasket, $sessionCoupon);

        $this->validationSessionWithAddress($sessionAddress);

        if (isset($resultVariation['status']) && $resultVariation['status'] == 101) {
            return response([
                'status' => 101,
                'message' =>'Product Inventory ' . $resultVariation['product'] . 'Is less than the selected value.'
            ]);
        } elseif (isset($resultVariation['status']) && $resultVariation['status'] == 103) {
            return response([
                'status' => 103,
                'message' =>$resultVariation['message']
            ]);
        } elseif (isset($resultVariation['status']) && $resultVariation['status'] == 104) {
            return response([
                'status' => 104,
                'message' =>$resultVariation['message']
            ]);
        } else {
            $totalPrice = $resultVariation['totalPrice'];
            /* finishPrice */
            $totalDiscount = $resultVariation['finishPrice'];
            $totalCount = $resultVariation['totalCount'];
            $coupon = $resultVariation['coupon'];

            $discountCountBuy = $resultVariation['discountCountBuy'];
        }

        /* check shipping cost */
        if ($sessionShippingCost != null || $sessionShippingCost != 0) {
            if (isset($totalDiscount) && $totalDiscount > 0 && !is_null($totalDiscount)) {
                $shippingCost = CheckoutController::checkSessionShippingCost($totalDiscount);
            } elseif (isset($totalPrice) && $totalPrice > 0 && !is_null($totalPrice)) {
                $shippingCost = CheckoutController::checkSessionShippingCost($totalPrice);
            }
        } else {
            $shippingCost = 0;
        }


        /* check used discount */
        if (isset($coupon) && !empty($coupon)) {
            $discountCoupon = unserialize($coupon);
            if ($discountCoupon->count_user === 0) {
                response([
                    'status' => 110,
                    'message' => Lang::get('cms.coupon_is_not_available')
                ]);
            }
            $resultCheck = $this->checkUsedUserDiscount($coupon, $this->user->id);
            if (!$resultCheck) {
                response([
                    'status' => 110,
                    'message' =>Lang::get('cms.discount-coupon-used')
                ]);
            }
        }
        /* check used discount */

        /* start check count_user discount */
        /* hichvaght nemiad  to in shart enshaallah :) */
        $objectDiscount = BasketZarinPalController::checkHasDiscount($resultVariation['variationsFind']->id, 0, 1);
        if (isset($objectDiscount->count_user) && $objectDiscount->count_user === 0) {

            $findVariation = Variation::with('discount')->findOrFail($resultVariation['variationsFind']->id);
            $resultEvent = event(new E_endCountUserDiscountInBasket($findVariation));
            if ($resultEvent) {
                $totalDiscount = 0;
            }
        }
        /* end check count_user discount */

        /* end validate session with table variations */
        if (isset($this->getTax) && !empty($this->getTax) && isset($this->getTax->code) && !empty($this->getTax->code)) {

            $totalPrice = taxCalculate::taxCalculate($totalPrice, 1);
            if ($totalPrice <= 0) {
               Auth::user()->basket[0]->delete();
               return response([
                   'status' => 110,
                   'message' => 'redirect user index app'
               ]);
            }

            if ($totalDiscount > 0 && !empty($totalDiscount)) {

                if ($coupon === 0 || is_null($coupon) || empty($coupon)) {
                    $discountCountBuy = array_filter($discountCountBuy);
                    $totalDiscount = $this->whichDiscountCountBuy($discountCountBuy, $totalDiscount);
                    if ($totalDiscount <= 0) {
                        Auth::user()->basket[0]->delete();
                        return response([
                            'status' => 110,
                            'message' => 'redirect user index app'
                        ]);
                    }
                    $totalDiscount = taxCalculate::taxCalculate($totalDiscount, 1);
                } else {
                    $totalDiscount = taxCalculate::taxCalculate($totalDiscount, 1);
                    if ($totalDiscount <= 0) {
                        Auth::user()->basket[0]->delete();
                        return response([
                            'status' => 110,
                            'message' => 'redirect user index app'
                        ]);
                    }
                }

            } else {
                $totalDiscount = 0;
            }
        }

        $sessionAddress = Address::find($sessionAddress['id']);
        $saveItem = [
            'user_id' => $this->user->id,
            'total_amount' => $totalPrice,
            'total_discount' => (int)round($totalDiscount),
            'coupon' => $coupon,
            'shippingCost' => $shippingCost,
            'tracking_code' => env('PERFIX') . "-" . time(),
            'payment_method_id' => $paymentMethods,
            'status' => isset($paymentMethods) && $paymentMethods == paymentMethods::DELIVARY ? Status::WAITING : Status::PENDING,
            'user_info' => serializeAndUnSerialize::serializeAndUnSerializeInfoUser($this->user, $sessionAddress),
            'expire' => Carbon::now()->addMinutes(10)->timestamp
        ];


        $orderCreate = Order::create($saveItem);
        if ($orderCreate instanceof Order) {
            $orderCreate->payments()->create([
                'user_id' => $this->user->id
            ]);
            /* start Of save discount coupon for user Or Role */
            if (isset($coupon) && !empty($coupon)) {
                $coupon = unserialize($coupon);
                $coupon->discountUser()->attach($this->user->id);
                $coupon->decrement('count_user', 1);
            }
            /* End Of save discount coupon for user Or Role */

            $array_key = array_keys($sessionBasket['items']);
            $count = count($array_key);
            $orderItemCreate = "";
            $arrayDiscount = [];
            $arrayVariationId = [];
            for ($i = 0; $i < $count; $i++) {

                $carts = $sessionBasket['items'][$array_key[$i]];

                /* validation variation */
                $variationFind = Variation::findOrFail($carts['item']['variation_id']);

                $arrayVariationId [] = $carts['item']['variation_id'];

                $discountSerilize = BasketZarinPalController::checkHasDiscount($carts['item']['variation_id'], 1);

                /* when count_user == 0 set null discount and bedune takhfif beshavad  */
                if (!is_null($discountSerilize)) {
                    $countUser = unserialize($discountSerilize);

                    if ($countUser->count_user > 0 || $countUser->count_user == null) {
                        $discountSerilize = serialize($countUser);
                    } else {
                        $discountSerilize = null;
                    }
                }


                $saveOrderItem = [
                    'order_id' => $orderCreate->id,
                    'product_id' => \App\Utility\Variation::findProduct($carts['item']['variation_id']),
                    'amount' => $carts['item']['price'],
                    'amount_discount' => $totalDiscount == 0 ? 0 : $carts['item']['discountPrice'],
                    'discount' => $discountSerilize,
                    'details' => serialize($carts),
                    'itemCount' => $carts['qty']
                ];

                $orderItemCreate = OrderItem::create($saveOrderItem);
                $updateVariation = "";
                $updateDiscount_count_user = "";
                if (isset($orderItemCreate) && !empty($orderItemCreate)) {

                    /* start decrement from variation table -> count */
                    $variation = Variation::where('id', $carts['item']['variation_id'])->first();
                    $objectDiscount = BasketZarinPalController::checkHasDiscount($variation->id, 0, 1);

                    $arrayDiscount [] = $objectDiscount;

                    if ($variation) {

                        /*if (isset($objectDiscount) && !empty($objectDiscount) && $objectDiscount->count_user > 0) {

//                            if ($objectDiscount->count_user > 0 && $objectDiscount->user_id == $carts['item']->seller){
                            $updateDiscount_count_user = $objectDiscount->decrement('count_user', 1);
//                            }

                            if ($objectDiscount->count_user == 0) {
//                                $findVariation = Variation::with('discount')->findOrFail($resultVariation['variationsFind']->id);
                                $findVariation = Variation::with('discount')->findOrFail($carts['item']->variation_id);
                                $resultEvent = event(new E_endCountUserDiscountInBasket($findVariation));

                                if ($updateVariation) {
                                    $totalDiscount = 0;
                                }
                            }
                        }*/

                        //$updateDiscount_count_user = isset($objectDiscount) && !empty($objectDiscount) && $objectDiscount->count_user > 0 ? $objectDiscount->decrement('count_user', 1) : null;

//                        $updateVariation = $variation->update([
//                            'count' => $variation->count = $variation->count - $carts['qty']
//                        ]);
                    }
                    /* end decrement from variation table -> count */

//                    if (!$variation || !$updateVariation) {

                    if (!$variation) {
                        /* delete order item */
                        $deleteOrderCreate = Order::find($orderCreate->id)->delete();

                        $updateDiscount_count_user = isset($objectDiscount) && !empty($objectDiscount) ? $objectDiscount->increment('count_user', 1) : null;
//                        $updateVariation = $variation->update([
//                            'count' => $variation->count = $variation->count + $carts['qty']
//                        ]);

                        if ($deleteOrderCreate) {
                            return response([
                                'status' => 110,
                                'message' => 'Sorry, your order was not registered.redirect user index app'
                            ]);

                        } else {
                            return response([
                                'status' => 110,
                                'message' => 'Sorry, your order was not registered. Issue Tracking :redirect user index app'
                            ]);

                        }
                    }

                } else {
                    /* delete order item */
                    $deleteOrderCreate = Order::find($orderCreate->id)->delete();

                    if ($deleteOrderCreate) {
                        return response([
                            'status' => 110,
                            'message' => 'Sorry, your order was not registered.redirect user index app'
                        ]);
                    } else {
                        return response([
                            'status' => 110,
                            'message' => 'Sorry, your order was not registered. Issue Tracking :redirect user index app'
                        ]);
                    }
                }

            }

            $arrayDiscountUnique = array_unique($arrayDiscount);

            foreach ($arrayDiscountUnique as $itemDiscount) {
                if (isset($itemDiscount) && !empty($itemDiscount) && $itemDiscount->count_user > 0) {

                    $updateDiscount_count_user = $itemDiscount->decrement('count_user', 1);

                    if ($itemDiscount->count_user === 0) {
                        foreach ($arrayVariationId as $itemVariationId) {
                            $findVariation = Variation::with('discount')->findOrFail($itemVariationId);
                            $findDiscount = self::checkHasDiscount($itemVariationId);
                            if (isset($findDiscount) && isset($findDiscount->id) && isset($itemDiscount) && ($findDiscount->id == $itemDiscount->id)) {
                                event(new E_endCountUserDiscountInBasket($findVariation));
                            }
                        }
                    }
                }
            }


            // todo  check discount price and total price and shipping cost (برای استفاده از درگاه باید چک کنیم total price  و discount price همچنین shipping cost)

            if ($paymentMethods == paymentMethods::DELIVARY) {
                Auth::user()->basket[0]->delete();
                return response([
                    'status' => 200,
                    'message' => Lang::get('cms.order-save-success') . Lang::get('cms.tracking-code') . $orderCreate->tracking_code
                ]);
            } else {
                /* start zarin pal */
                if (isset($totalDiscount) && !empty($totalDiscount) && $totalDiscount > 0) {
                    $totalPrice = $totalDiscount + $shippingCost;
                } else {
                    $totalPrice = $totalPrice + $shippingCost;
                }

                if (isset($orderItemCreate) && !empty($orderItemCreate)) {
                    Auth::user()->basket[0]->delete();
                    return response([
                        'status' => 200,
                        'data' => [
                            'merchantId' => env('MERCHANTID'),
                            'urlPay' => env('URLPAY'),
                            'urlCheck' => env('URLCHECK'),
                            'userEmail' => $this->user->email,
                            'urlBack' => $this->urlBack,
                            'totalPrice' => $totalPrice,
                            'orderCreate' => $orderCreate->id,
                        ],
                        'message' => 'Connect Gateway...'
                    ]);
//                    return zarinPall::zarinPal(env('MERCHANTID'), env('URLPAY'), env('URLCHECK'), $this->user->email
//                        , $this->urlBack, $totalPrice, $orderCreate->id);
                } else {
                    return response([
                        'status' => 110,
                        'message' => 'Sorry, your order was not registered. Issue Tracking :redirect user index app'
                    ]);
                }
                /* end zarin pal */
            }


        } else {
            return back()->with(['error' => "Error performing operation, please try again later."]);
        }
    }

    public function checkBasket()
    {

        $Authority = request('Authority');
        $findOrder = Order::where('rest_number', $Authority)->firstOrFail();
        $shippingCost = Systeminfmanage::where('id', 21)->whereStatus(1)->first()->code;


        if (isset($findOrder->total_discount) && !empty($findOrder->total_discount)) {
            $totalPrice = $findOrder->total_discount + $shippingCost;
        } else {
            $totalPrice = $findOrder->total_amount + $shippingCost;
        }


        if (request('Status') == 100) {

            $client = new SoapClient(env('URLCHECK'), ['encoding' => 'UTF-8']);

            $result = $client->PaymentVerification(
                [
                    'MerchantID' => env('MERCHANTID'),
                    'Authority' => $Authority,
                    'Amount' => $totalPrice,
                ]
            );

            if ($result->Status == 100) {
                $updateOrder = $findOrder->update([
                    'status' => Status::PAID
                ]);

                if (isset($findOrder->payments[0]) && !empty($findOrder->payments[0])){
                    $findOrder->payments[0]->update([
                        'payment' => PaymentStatus::SUCCESSFUL
                    ]);
                }

                $tracking_code = $findOrder->tracking_code;
                if ($updateOrder > 0) {
                    
                response([
                    'status' => 200,
                    'message' => "Your payment was made correctly. Issue Tracking . $tracking_code"
                    ]);
                
                } else {
                     response([
                    'status' => 400,
                    'message' => "Sorry, your order has not been registered, please follow through with support. Issue Tracking : . $tracking_code"
                    ]);
                    
                }

            } else {

                $findOrder->update([
                    'status' => Status::CANCELED
                ]);
                incrementVariation::incrementVariations($findOrder->id);
                 response([
                    'status' => 400,
                    'message' => "The transaction has been canceled by you."
                    ]);
               
            }

        } else {
            $findOrder->update([
                'status' => Status::CANCELED
            ]);
            incrementVariation::incrementVariations($findOrder->id);
              response([
                    'status' => 400,
                    'message' => "The transaction has been canceled by you."
                    ]);
                    
         
        }
    }

    /*=============================== validation ====================================*/
    private function validationSessionWithVariation($sessionBasket, $sessionCoupon)
    {

        if (isset($sessionBasket) && !empty($sessionBasket) && isset($sessionBasket['items']) && !empty($sessionBasket['items'])) {

            /* validation coupon */
            if (isset($sessionCoupon) && !empty($sessionCoupon)) {

                $findSessionCoupon = Coupon::with(['discount'])->where('code', $sessionCoupon)->where('expire_date', ">", Carbon::now()->timestamp)->firstOrFail();

                if (!$findSessionCoupon) {
                    return [
                        'status' => 104,
                        'message' => Lang::get('cms.coupon_is_not_available'),
                    ];
                }
            }

            $totalPrice = 0;
            $totalDiscountPrice = 0;
            $finishPrice = 0;
            $totalCount = 0;
            $priceDiscountVariation = 0;
            $variationsFind = 0;
            $justTotalDiscount = 0;
            $discountCountBuy = [];
            foreach ($sessionBasket['items'] as $itemSession) {
                /* start qty session */
                $qty = $itemSession['qty'];
                /* end qty session */

                $variation_id = $itemSession['item']['variation_id'];

                $variationsFind = \App\Utility\Variation::findVariation($variation_id);

                if (is_null($variationsFind)) {
                    return [
                        'status' => '101',
                        'product' => 'Optional'
                    ];
                }

                /* start product name */
                $productTitle = $variationsFind->product->title;
                /* end product name */

                /* start qty variation */
                $qtyVariation = $variationsFind->count;
                /* end qty variation */

                /* start price variation */
                $priceVariation = $variationsFind->price;
                /* end price variation */

                if (empty($variationsFind->discountPrice) || $variationsFind->discountPrice < 0 || is_null($variationsFind->discountPrice)) {
                    $priceDiscountVariation = $variationsFind->price;
//                    $priceDiscountVariation += 0;
                } else {
                    $justTotalDiscount += $variationsFind->discountPrice;
                    $priceDiscountVariation = $variationsFind->discountPrice;
                }

                /* check discount count buy price */
                $discountCountBuy [] = $this->findDiscountCountBuy($variationsFind, $qty);


                /* validation qty */
                if ($qty <= $qtyVariation) {
                    $totalCount += $qty;
                    $totalPrice += ($qty * $priceVariation);
                    $totalDiscountPrice += ($qty * $priceDiscountVariation);
                    $justTotalDiscount = ($qty * $justTotalDiscount);

                } else {
                    return [
                        'status' => 101,
                        'product' => $productTitle
                    ];
                }

            }


            /* finish price */
            if (isset($findSessionCoupon) && !empty($findSessionCoupon) && !empty($totalDiscountPrice) && $totalDiscountPrice > 0) {

                if (isset($findSessionCoupon->discount)) {

                    if ($findSessionCoupon->discount->baseon == DiscountType::price) {

                        $discountCountBuy = array_filter($discountCountBuy);

                        $totalDiscount = $this->whichDiscountCountBuy($discountCountBuy, $totalDiscountPrice);

                        if (!is_null($totalDiscount)) {
                            $finishPrice = $totalDiscount - $findSessionCoupon->discount->cent;
                        } else {
                            $finishPrice = $totalDiscountPrice - $findSessionCoupon->discount->cent;
                        }

                    } elseif ($findSessionCoupon->discount->baseon == DiscountType::cent) {

                        $discountCountBuy = array_filter($discountCountBuy);

                        $totalDiscount = $this->whichDiscountCountBuy($discountCountBuy, $totalDiscountPrice);

                        if (!is_null($totalDiscount)) {
                            $finishPrice = $totalDiscount - ((($totalDiscount * $findSessionCoupon->discount->cent) / 100));
                        } else {
                            $finishPrice = $totalDiscountPrice - ((($totalDiscountPrice * $findSessionCoupon->discount->cent) / 100));
                        }

                    }
                }

            }

            /* finish price */
            return [
                'variationsFind' => $variationsFind,
                'totalPrice' => $totalPrice,
//                'totalDiscountPrice' => $totalDiscountPrice,
                'totalDiscountPrice' => $justTotalDiscount,
                'finishPrice' => isset($finishPrice) && !empty($finishPrice) ? $finishPrice : $totalDiscountPrice,
                'totalCount' => $totalCount,
                'coupon' => isset($findSessionCoupon) && isset($findSessionCoupon->discount) ? serialize($findSessionCoupon->discount) : 0,
                'discountCountBuy' => $discountCountBuy,
            ];
        } else {
            return [
                'status' => 103,
                'message' => 'Your cart is empty.'
            ];
        }
    }

    private function validationSessionWithAddress($sessionAddress)
    {
        $addressSession = $sessionAddress['user_id'];
        User::findOrFail($addressSession);
    }

    /*=============================== extra function ====================================*/

    /* start check has discount */
    private static function has_discount($hasDiscount, $hasCountUser = null)
    {
//        dd($hasDiscount);
        if (isset($hasDiscount) && !empty($hasDiscount) && $hasDiscount->count() > 0 && $hasCountUser != null) {
            $findDiscount = $hasDiscount[0]->discount;

            if (isset($findDiscount)) {
                return $findDiscount;
            } else {
                return null;
            }
        } elseif (isset($hasDiscount) && !empty($hasDiscount) && $hasDiscount->count() > 0) {
            $discount = $hasDiscount[0]->discount;
        } else {
            $discount = null;
        }

        return $discount;
    }

    public static function checkHasDiscount($variationId, $flag = 0, $count_user = null)
    {
        $findVariation = Variation::with('discount')->findOrFail($variationId);
        $productNumber = DiscountType::product;
        $brandNumber = DiscountType::brand;
        $categoryNumber = DiscountType::category;

        if (isset($findVariation) && !empty($findVariation)) {
            $discount = null;
            //check discount type...
            $discountType = self::checkHasDiscountType($findVariation);
            switch ($discountType) {

                case $productNumber:
                    $hasDiscount = $findVariation->discount;
                    $discount = self::has_discount($hasDiscount, $count_user);
                    break;
                case $brandNumber:
                    $discountAble = self::findDiscountAbleForEachUser($findVariation, $brandNumber);
                    $discount = self::has_discount($discountAble, $count_user);
                    break;
                case $categoryNumber:
                    $discountAble = self::findDiscountAbleForEachUser($findVariation, $categoryNumber);
                    $discount = self::has_discount($discountAble, $count_user);
                    break;
            }
            if ($flag > 0 && !empty($discount) && !is_null($discount)) {
                return serialize($discount);
            }

            return $discount;
        }
    }
    /* end check has discount */

    /* check user used discount */
    public function checkUsedUserDiscount($coupon, $user_id)
    {
        $discount = unserialize($coupon);
        $discount_id = $discount->id;
        $checkDiscount = $discount->discountUser;

        foreach ($checkDiscount as $itemDiscountUser) {
            if ($itemDiscountUser->pivot->discount_id == $discount_id && $itemDiscountUser->pivot->user_id == $user_id) {
                return false;
            }
        }
        return true;
    }

    /* find discountAble for each user */
    private static function findDiscountAbleForEachUser($findVariation, $discountType)
    {
        $discount = Discount::with('disable')->
        where('user_id', $findVariation->user_id)->
        where('discountable_type', $discountType)->first();
//&& ($discount->count_user > 0 || is_null($discount->count_user)
        if (isset($discount) && !empty($discount) && isset($discount->disable) && !empty($discount->disable)) {
            $discountAble = $discount->disable;
            return $discountAble;
        } else {
            return false;
        }
    }

    private static function checkHasDiscountType($variation)
    {
        $discountVariation = $variation->discount;
        $discountBrand = $variation->product->brand->discount;
        $discountCategory = $variation->product->categories[0]->discount;
        if (isset($discountVariation) && !empty($discountVariation) && $discountVariation->count() > 0) {
            return DiscountType::product;
        } elseif (isset($discountBrand) && !empty($discountBrand) && $discountBrand->count() > 0) {
            return DiscountType::brand;
        } elseif (isset($discountCategory) && !empty($discountCategory) && $discountCategory->count() > 0) {
            return DiscountType::category;
        }
    }

    /* find discount buy */
    private function findDiscountCountBuy($variation, $qty)
    {
        $findDiscount = isset($variation->discount[0]) ? $variation->discount[0]->discount : false;
        $discountCountBuyPrice = 0;
        $discountCountBuyCent = 0;
        if (isset($findDiscount) && $findDiscount != false && $findDiscount->type == DiscountType::COUNTBUY) {
            if ($qty > $findDiscount->count_buy) {
                if ($findDiscount->baseon == DiscountType::price) {
                    $discountCountBuyPrice = $findDiscount->cent;
                    //$discountCountBuy = ($qty * $variation->price) - $findDiscount->cent;
                } elseif ($findDiscount->baseon == DiscountType::cent) {
                    $discountCountBuyCent = $findDiscount->cent;
                    $cal = (($qty * $variation->price) * $discountCountBuyCent) / 100;
                    $discountCountBuyCent = ($qty * $variation->price) - $cal;
                    $discountCountBuyCent = ($qty * $variation->price) - $discountCountBuyCent;
                }
                return [
                    'discountCountBuyPrice' => $discountCountBuyPrice,
                    'discountCountBuyCent' => $discountCountBuyCent
                ];
            }
        }

    }

    /* whichDiscountCountBuy */
    private function whichDiscountCountBuy($discountCountBuy, $discount)
    {
        if (isset($discountCountBuy) && is_array($discountCountBuy) && !is_null($discountCountBuy)) {
            $finish = 0;
            foreach ($discountCountBuy as $itemDiscountBuy) {
                if (isset($itemDiscountBuy['discountCountBuyPrice']) && $itemDiscountBuy['discountCountBuyPrice'] > 0) {
                    $finish += $itemDiscountBuy['discountCountBuyPrice'];
                } elseif (isset($itemDiscountBuy['discountCountBuyCent'])) {
                    $finish += $itemDiscountBuy['discountCountBuyCent'];
                }
            }
            return $discount - $finish;
        }
    }
}
