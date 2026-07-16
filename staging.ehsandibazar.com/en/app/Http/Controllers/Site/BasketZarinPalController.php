<?php

namespace App\Http\Controllers\Site;

use App\Events\E_endCountUserDiscountInBasket;
use App\Listeners\L_endCountUserDiscountInBasket;
use App\Model\Coupon;
use App\Model\Discount;
use App\Model\Order;
use App\Model\OrderItem;
use App\Model\Product;
use App\Model\Systeminfmanage;
use App\Model\Variation;
use App\User;
use App\Utility\DiscountType;
use App\Utility\forgetSession;
use App\Utility\incrementVariation;

use App\Utility\Message;
use App\Utility\paymentMethods;
use App\Utility\ProductType;
use App\Utility\SendSms;
use App\Utility\serializeAndUnSerialize;
use App\Utility\Status;
use App\Utility\taxCalculate;
use App\Utility\zarinPall;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Ipecompany\Smsirlaravel\Smsirlaravel;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Payment\Facade\Payment;
use SoapClient;

class BasketZarinPalController extends Controller
{

    protected $urlBack;
    protected $getTax;
    public $user;
    public $sessionAddress;


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->sessionAddress = Session::get('address');
            return $next($request);
        });
        $this->urlBack = route('site.basket.check');
        $this->getTax = Systeminfmanage::where('id', 52)->where('status', 1)->first();
    }

    public function finishBasket(Request $request)
    {
        $this->validate($request, [
            'payment' => "required|integer|between:0,2",
            'postType' => "required|integer|between:1,3",
            'shipping-method' => "nullable|integer|between:172,174",
        ], [
            'payment.between' => 'اطلاعات ارسالی صحیح نمیباشد',
            'postType.between' => 'اطلاعات ارسالی صحیح نمیباشد',
            'shipping-method.between' => 'اطلاعات ارسالی صحیح نمیباشد',
        ]);

        /* ****************** PaymentMethods [0 => Online , 1 => spot , 2 => Wallet] ****************************** */
        $paymentMethods = $request->input('payment');
        $shippingMethod = $request->input('shipping-method', 173);
        $postType = $request->input('postType');


        $totalPrice = 0;
        $totalCount = 0;
        $shippingCost = 0;

        $sessionBasket = Session::get('basket');
        $sessionCoupon = Session::get('coupon');

        if (!isset($this->sessionAddress) && empty($this->sessionAddress)) {
            if (isset(Auth::user()->address[0]) && !empty(Auth::user()->address[0]) && isset(Auth::user()->address[0]->fullAddress) && !empty(Auth::user()->address[0]->fullAddress)) {
                Session::put('address', Auth::user()->address[0]);
                $sessionAddress = Session::get('address');
            } else {
                alert()->success('آدرسی یافت نشد , لطفا از قسمت پروفایل آدرس خود را ثبت نمایید.', Lang::get('cms.error'))->showConfirmButton('بستن');
                return redirect()->route('users.panel.address');
            }
        } else {
            $sessionAddress = $this->sessionAddress;
        }

        $totalWeight = CheckoutController::sumWeightBasket($sessionBasket);
        $shippingCost = CheckoutController::calculatorShippingCost($shippingMethod, $postType, $totalWeight, 0, $sessionBasket);


        /* start validate session with table variations */
        $resultVariation = $this->validationSessionWithVariation($sessionBasket, $sessionCoupon);

        $this->validationSessionWithAddress($sessionAddress);

        if (isset($resultVariation['status']) && $resultVariation['status'] == 101) {
            alert()->error('موجودی محصول ' . $resultVariation['product'] . ' کمتر از مقدار انتخابی می باشد.', 'خطا!')->showConfirmButton('بستن');
            return back();
        } elseif (isset($resultVariation['status']) && $resultVariation['status'] == 103) {
            alert()->error($resultVariation['message'], Lang::get('cms.error'))->showConfirmButton(Lang::get('cms.close'));
            return back();
        } elseif (isset($resultVariation['status']) && $resultVariation['status'] == 104) {
            alert()->error($resultVariation['message'], Lang::get('cms.error'))->showConfirmButton('بستن');
            return back();
        } else {
            $totalPrice = $resultVariation['totalPrice'];
            /* finishPrice */
            $totalDiscount = $resultVariation['finishPrice'];
            $totalCount = $resultVariation['totalCount'];
            $coupon = $resultVariation['coupon'];

            $discountCountBuy = $resultVariation['discountCountBuy'];
        }

        /* check used discount */
        if (isset($coupon) && !empty($coupon)) {
            $discountCoupon = unserialize($coupon);
            if ($discountCoupon->count_user === 0) {
                alert()->error(Lang::get('cms.coupon_is_not_available'), Lang::get('cms.error'));
                return back();
            }
            $resultCheck = $this->checkUsedUserDiscount($coupon, $this->user->id);
            if (!$resultCheck) {
                alert()->error(Lang::get('cms.discount-coupon-used'), Lang::get('cms.error'));
                return back();
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
            if ($totalPrice < 0) {
                forgetSession::forgetSession();
                return redirect()->route('site.index');
            }

            if ($totalDiscount > 0 && !empty($totalDiscount)) {

                if ($coupon === 0 || is_null($coupon) || empty($coupon)) {
                    $discountCountBuy = array_filter($discountCountBuy);
                    $totalDiscount = $this->whichDiscountCountBuy($discountCountBuy, $totalDiscount);
                    if ($totalDiscount <= 0) {
                        forgetSession::forgetSession();
                        return redirect()->route('site.index');
                    }
                    $totalDiscount = taxCalculate::taxCalculate($totalDiscount, 1);
                } else {
                    $totalDiscount = taxCalculate::taxCalculate($totalDiscount, 1);
                    if ($totalDiscount <= 0) {
                        forgetSession::forgetSession();
                        return redirect()->route('site.index');
                    }
                }

            } else {
                $totalDiscount = 0;
            }
        }


        $saveItem = [
            'user_id' => $this->user->id,
            'total_amount' => $totalPrice,
            'total_discount' => (int)round($totalDiscount),
            'coupon' => $coupon,
            'item_count' => count($sessionBasket->items),
            'shippingCost' => $shippingCost,
            'tracking_code' => time(),
            'shipping_method_id' => $shippingMethod,
            'payment_method_id' => $paymentMethods,
            'status' => (isset($paymentMethods) && $paymentMethods == paymentMethods::DELIVARY) || $paymentMethods == paymentMethods::WALLET ? Status::WAITING : Status::PENDING,
            'user_info' => serializeAndUnSerialize::serializeAndUnSerializeInfoUser($this->user, $sessionAddress),
            'expire' => Carbon::now()->addMinutes(10)->timestamp
        ];

        // Start Of If gateway and Payment With Wallet
        if ($paymentMethods == paymentMethods::WALLET && (int)$this->user->wallet <= 0) {
            alert()->warning("موجودی کیف پول شما کافی نیست", "متاسفیم!")->showConfirmButton(Lang::get('cms.close'));
            return back();
        }
        // End Of If gateway and Payment With Wallet

        $orderCreate = Order::create($saveItem);
        if ($orderCreate instanceof Order) {

            /* start Of save discount coupon for user Or Role */
            if (isset($coupon) && !empty($coupon)) {
                $coupon = unserialize($coupon);
                $coupon->discountUser()->attach($this->user->id);
                $coupon->decrement('count_user', 1);
            }
            /* End Of save discount coupon for user Or Role */

            $array_key = array_keys($sessionBasket->items);
            $count = count($array_key);
            $orderItemCreate = "";
            $arrayDiscount = [];
            $arrayVariationId = [];
            for ($i = 0; $i < $count; $i++) {

                $carts = $sessionBasket->items[$array_key[$i]];

                /* validation variation */
                $variationFind = Variation::findOrFail($carts['item']->variation_id);

                $arrayVariationId [] = $carts['item']->variation_id;

                $discountSerilize = BasketZarinPalController::checkHasDiscount($carts['item']->variation_id, 1);

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
                    'product_id' => \App\Utility\Variation::findProduct($carts['item']->variation_id),
                    'amount' => $carts['item']->price,
                    'amount_discount' => $totalDiscount == 0 ? 0 : $carts['item']->discountPrice,
                    'discount' => $discountSerilize,
                    'details' => serialize($carts),
                    'itemCount' => $carts['qty']
                ];

                $orderItemCreate = OrderItem::create($saveOrderItem);
                $updateVariation = "";
                $updateDiscount_count_user = "";
                if (isset($orderItemCreate) && !empty($orderItemCreate)) {

                    /* start decrement from variation table -> count */
                    $variation = Variation::where('id', $carts['item']->variation_id)->first();
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
                            alert()->error('با عرض پوزش , سفارش شما ثبت نشد.', Lang::get('cms.error'))->showConfirmButton(Lang::get('cms.close'));
                            return back();
                        } else {
                            alert()->error('با عرض پوزش , سفارش شما ثبت نشد. شماره پیگیری :‌ ' . $orderCreate->tracking_code, Lang::get('cms.error'))->showConfirmButton(Lang::get('cms.close'));
                            return back();
                        }
                    }

                } else {
                    /* delete order item */
                    $deleteOrderCreate = Order::find($orderCreate->id)->delete();

                    if ($deleteOrderCreate) {
                        alert()->error('با عرض پوزش , سفارش شما ثبت نشد.', Lang::get('cms.error'))->showConfirmButton(Lang::get('cms.close'));
                        return back();
                    } else {
                        alert()->error('با عرض پوزش , سفارش شما ثبت نشد. شماره پیگیری :‌ ' . $orderCreate->tracking_code, Lang::get('cms.error'))->showConfirmButton(Lang::get('cms.close'));
                        return back();
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

            // Start Of If gateway and Payment With Wallet
            if ($paymentMethods == paymentMethods::WALLET) {
                // Start Of Check Wallet And  Inventory
                if (isset($this->user->wallet) && (int)$this->user->wallet > 0) {
                    if (isset($totalDiscount) && !empty($totalDiscount) && $totalDiscount > 0) {
                        $walletPrice = $totalDiscount + $shippingCost;
                    } else {
                        $walletPrice = $totalPrice + $shippingCost;
                    }
                    if ((int)$this->user->wallet < $walletPrice) {
                        $orderCreate->delete();
                        alert()->warning("موجودی کیف پول شما کافی نیست", "متاسفیم!")->showConfirmButton(Lang::get('cms.close'));
                        return back();
                    }
                } else {
                    $orderCreate->delete();
                    alert()->warning("موجودی کیف پول شما کافی نیست", "متاسفیم!")->showConfirmButton(Lang::get('cms.close'));
                    return back();
                }
                // End Of Check Wallet And  Inventory
                forgetSession::forgetSession();
                $this->user->update([
                    'wallet' => $this->user->wallet - $walletPrice
                ]);
                alert()->success(Lang::get('cms.success'),Lang::get('cms.order-save-success') . Lang::get('cms.tracking-code') . $orderCreate->tracking_code)->showConfirmButton(Lang::get('cms.close'));
                return redirect()->route('site.index');
            }
            // End Of If gateway and Payment With Wallet


            if ($paymentMethods == paymentMethods::DELIVARY) {
                forgetSession::forgetSession();
                SendSms::sms($orderCreate->tracking_code, 83324, Auth::user()->mobile);
                alert()->success(Lang::get('cms.success'),Lang::get('cms.order-save-success') . Lang::get('cms.tracking-code') . $orderCreate->tracking_code)->showConfirmButton(Lang::get('cms.close'));
                return redirect()->route('site.index');
            } else {

                /* start zarin pal */
//                if (isset($totalDiscount) && $totalDiscount >= 1 && !Auth::user()->isColleague()) {
                if (isset($totalDiscount) && $totalDiscount > 0) {
                    $totalPrice = $totalDiscount + $shippingCost;
                } else {
                    $totalPrice = $totalPrice + $shippingCost;
                }

                if (isset($orderItemCreate) && !empty($orderItemCreate)) {

                    if (Auth::user()->isColleague()) {
                        $orderCreate->update(['total_discount' => $totalPrice]);
                    }
                    Session::forget('basket');
                    if (isset($totalPrice) && $totalPrice > 0){
                        return zarinPall::zarinPal(env('MERCHANTID'), env('URLPAY'), env('URLCHECK'), $this->user->email
                            , $this->urlBack, $totalPrice, $orderCreate->id);
                    }else{
                        forgetSession::forgetSession();
                        SendSms::sms($orderCreate->tracking_code, 83324, Auth::user()->mobile);
                        alert()->success(Lang::get('cms.success'),Lang::get('cms.order-save-success') . Lang::get('cms.tracking-code') . $orderCreate->tracking_code)->showConfirmButton(Lang::get('cms.close'));
                        return redirect()->route('site.index');
                    }

                } else {
                    alert()->error('با عرض پوزش , سفارش شما ثبت نشد. شماره پیگیری :‌ ' . $orderCreate->tracking_code, 'خطا!')->showConfirmButton('بستن');
                    return back();
                }
                /* end zarin pal */
            }


        } else {
            return back()->with(['error' => "خطا در انجام عملیات , لطفا چند لحظه بعد امتحان فرمایید."]);
        }
    }

    public function checkBasket()
    {

        $Authority = request('Authority');
        $findOrder = Order::where('rest_number', $Authority)->firstOrFail();
        if (isset($findOrder->total_discount) && !empty($findOrder->total_discount)) {
            $totalPrice = $findOrder->total_discount + $findOrder->shippingCost;
        } else {
            $totalPrice = $findOrder->total_amount + $findOrder->shippingCost;
        }

        $data = array("merchant_id" => env('MERCHANTID'), "authority" => $Authority, "amount" => ($totalPrice * 10));
        $jsonData = json_encode($data);
        $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/verify.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        if ($err) {
            alert()->error($err, Lang::get('cms.error'))->persistent(Lang::get('cms.close'));
            return redirect()->route('site.index');
        } else {
            if (isset($result['data']) && isset($result['data']['code']) && $result['data']['code'] == 100) {
                $updateOrder = $findOrder->update([
                    'status' => Status::PAID,
                    'ref_id' => $result['data']['ref_id'],
                    'tracking_code' => $result['data']['ref_id'],
                ]);


                /*  start Of update sold variation */
                foreach ($findOrder->orderItem as $orderItem) {
                    $detail = unserialize($orderItem->details);
                    $variationId = $detail['item']->variation_id;
                    $productId = $detail['item']->product_id;
                    Variation::find($variationId)->increment('sold');
                    $product = Product::find($productId);
                    $product->increment('soldCount');


                    /* start Of Add Product Into Product_user Table*/
                    if (in_array($product->type, [ProductType::VOICE, ProductType::PDF, ProductType::VIDEO])) {
                        if (isset(Auth::user()->production[0]) && !empty(Auth::user()->production[0])) {
                            $productUser = Auth::user()->production->pluck('id')->toArray();
                            if (!in_array($product->id, $productUser)) {
                                $product->users()->attach([Auth::user()->id]);
                            }
                        } else {
                            $product->users()->attach([Auth::user()->id]);
                        }
                    }
                    /* end Of Add Product Into Product_user Table*/
                }
                /*  end Of update sold variation */
                $tracking_code = $findOrder->tracking_code;
                if ($updateOrder > 0) {
                    SendSms::sms($tracking_code, 83324, $findOrder->user->mobile);
                    alert()->success('پرداخت موفق!','با تشکر از انتخاب شما. شماره پیگیری :‌ ' . $tracking_code)->persistent('بستن');
                    return redirect()->route('site.index');
                } else {
                    alert()->error('با عرض پوزش , سفارش شما ثبت نشد, لطفا از طریق پشتیبانی پیگیری فرمایید. شماره پیگیری :‌ ' . $tracking_code, 'خطا!')->persistent('بستن');
                    return redirect()->route('site.index');
                }
            } else {
                $findOrder->update([
                    'status' => Status::CANCELED
                ]);
                incrementVariation::incrementVariations($findOrder->id);
                $msg = isset($result['errors']['message']) && !empty($result['errors']['message']) ?  $result['errors']['message'] : "خطایی رخ داده لطفا به پشتیبان اطلاع دهید";
                alert()->error($msg, Lang::get('cms.error'))->persistent(Lang::get('cms.close'));
                return redirect()->route('site.index');

            }
        }


    }



    /*=============================== validation ====================================*/
    private
    function validationSessionWithVariation($sessionBasket, $sessionCoupon)
    {
        if (isset($sessionBasket) && !empty($sessionBasket) && isset($sessionBasket->items) && !empty($sessionBasket->items)) {

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
            foreach ($sessionBasket->items as $itemSession) {
                /* start qty session */
                $qty = $itemSession['qty'];
                /* end qty session */

                $variation_id = $itemSession['item']->variation_id;

                $variationsFind = \App\Utility\Variation::findVariation($variation_id);

                if (is_null($variationsFind)) {
                    return [
                        'status' => '101',
                        'product' => 'انتخابی'
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
                'finishPrice' => !isset($finishPrice) && $finishPrice < 0 ? $totalDiscountPrice : $finishPrice,
                'totalCount' => $totalCount,
                'coupon' => isset($findSessionCoupon) && isset($findSessionCoupon->discount) ? serialize($findSessionCoupon->discount) : 0,
                'discountCountBuy' => $discountCountBuy,
            ];
        } else {
            return [
                'status' => 103,
                'message' => 'سبد خرید شما خالی می باشد.'
            ];
        }
    }

    private
    function validationSessionWithAddress($sessionAddress)
    {
        $addressSession = $sessionAddress->user_id;
        User::findOrFail($addressSession);
    }

    /*=============================== extra function ====================================*/

    /* start check has discount */
    private
    static function has_discount($hasDiscount, $hasCountUser = null)
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

    public
    static function checkHasDiscount($variationId, $flag = 0, $count_user = null)
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
    public
    function checkUsedUserDiscount($coupon, $user_id)
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
    private
    static function findDiscountAbleForEachUser($findVariation, $discountType)
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

    private
    static function checkHasDiscountType($variation)
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
    private
    function findDiscountCountBuy($variation, $qty)
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
//        return [
//            'discountCountBuyPrice' => 0,
//            '$discountCountBuyCent' => 0
//        ];
    }

    /* whichDiscountCountBuy */
    private
    function whichDiscountCountBuy($discountCountBuy, $discount)
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
