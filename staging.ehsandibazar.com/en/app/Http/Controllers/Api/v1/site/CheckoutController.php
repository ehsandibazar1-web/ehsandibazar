<?php

namespace App\Http\Controllers\Api\v1\site;

use App\Model\Systeminfmanage;
use App\User;
use App\Utility\forgetSession;
use App\Utility\unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use SEO;

class CheckoutController extends Controller
{
    protected $user;
    public $showDetails;
    public $sessionAddress;
    public $tax;
    public $userDetails;
    public $basket;
    public $shippingCost;

    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->sessionAddress = \auth()->user()->basket[0]->address;
            $this->basket = \auth()->user()->basket[0]->session;
            /* session */
            $this->shippingCost = self::checkSessionShippingCost();
            return $next($request);
        });
        /* when state in checkout hidden basket details */
        $this->showDetails = "hiddenDetails";
        $this->tax = Systeminfmanage::whereId(33)->where('status', 1)->first();

    }

    public function index()
    {

        /* start check and apply coupon on basket */
        $this->applyCouponOnBasket();
        /* end check and apply coupon on basket */

        /* dont show cart */
        $showDetails = $this->showDetails;

        /* tax */
        $tax = $this->tax;
        /* get user */
        $user = User::with(['address' => function ($q) {
            $q->with(['province', 'city']);
        }])->where('id', $this->user->id)->first();

        /* basket */
        $basket = $this->basket;
        $resultBasket = $this->isEmptyBasket($basket);
        if ($resultBasket == false) {
            return response([
                'status' => 110,
                'message' => 'Your cart is empty and return index app',
            ]);
        }

        if ($basket['totalPrice']) {
            $shippingCost = $this->checkSessionShippingCost($basket['totalPrice']);
        }

        $resultCal = $this->calBasket($basket, $tax);

        return response([
            'status' => 200,
            'data' => [
                'user' => $user,
                'tax' => $tax,
                'basket' => $basket,
                'resultCal' => $resultCal,
                'shipping' => unit::unit($shippingCost),
            ],
            'message' => 'success',
        ]);

    }

    public function address()
    {


        $user = User::with(['address' => function ($q) {
            $q->with(['province', 'city']);
        }])->where('id', $this->user->id)->first();

        /* set session */
        if (!isset($this->sessionAddress) && empty($this->sessionAddress)) {
            if (isset($user->address[0]) && !empty($user->address[0]) && isset($user->address[0]->fullAddress) && !empty($user->address[0]->fullAddress)) {
                Auth::user()->basket[0]->update(['address' => $user->address[0]]);
                $sessionAddress = Auth::user()->basket[0]->address;
            } else {
                return response([
                    'status' => 102,
                    'message' => 'No address found, please register your address in the profile section.'
                ]);
            }
        } else {
            $sessionAddress = $this->sessionAddress;
        }

        /* basket */
        $basket = $this->basket;
        $resultBasket = $this->isEmptyBasket($basket);
        if ($resultBasket == false) {
            return response([
                'status' => 110,
                'message' => 'Your cart is empty and return index app',
            ]);
        }
        $tax = $this->tax;
        $resultCal = $this->calBasket($basket, $tax);

        if ($basket['totalPrice']) {
            $shippingCost = $this->checkSessionShippingCost($basket['totalPrice']);
        }
        return response([
            'status' => 200,
            'data' => [
                'user' => $user,
                'resultCal' => $resultCal,
                'shipping' => unit::unit($shippingCost)
            ],
            'message' => 'success'
        ]);
    }

    public function review()
    {
        /* session */
        $shippingCost = $this->checkSessionShippingCost();


        /* tax */
        $tax = $this->tax;

        /* get User */
        $user = User::with(['address' => function ($q) {
            $q->with(['province', 'city']);
        }])->where('id', $this->user->id)->first();

        /* set session */
        if (!isset($this->sessionAddress) && empty($this->sessionAddress)) {
            if (isset($user->address[0]) && !empty($user->address[0]) && isset($user->address[0]->fullAddress) && !empty($user->address[0]->fullAddress)) {
                Auth::user()->basket[0]->update(['address' => $user->address[0]]);
                $sessionAddress = Auth::user()->basket[0]->address;
            } else {
                return response([
                    'status' => 110,
                    'message' => 'No address found, please register your address in the profile section.'
                ]);
            }
        } else {
            $sessionAddress = $this->sessionAddress;
        }

        /* basket */
        $basket = $this->basket;
        $resultBasket = $this->isEmptyBasket($basket);
        if ($resultBasket == false) {
            return response([
                'status' => 110,
                'message' => 'Your cart is empty and return index app',
            ]);
        }
        $resultCal = $this->calBasket($basket, $tax);

        return response([
            'status' => 200,
            'data' => [
                'user' => $user,
                'sessionAddress' => $sessionAddress,
                'tax' => $tax,
                'basket' => $basket,
                'resultCal' => $resultCal,
                'shipping' => $shippingCost,
            ],
            'message' => 'success'
        ]);

    }

    /* ======================= extra function  ======================= */

    private function isEmptyBasket($basket)
    {
        if (!isset($basket) || empty($basket)) {
            return false;
        }
        return true;
    }

    /* check and apply coupon on basket */
    private function applyCouponOnBasket()
    {

        $session = \auth()->user()->basket[0]->session;
        $sessionPercent = \auth()->user()->basket[0]->percent;
        $sessionPrice = \auth()->user()->basket[0]->prices;

        if (isset($session)) {
            if (isset($sessionPercent) && !empty($sessionPercent)) {
                $finishPrice = $session['totalPrice'] - ((($session['totalPrice'] * $sessionPercent) / 100));
                if ($finishPrice > 0) {
                    Auth::user()->basket[0]->update(['finishPrice' => $finishPrice]);
                } else {
                    Auth::user()->basket[0]->delete();
                    return response([
                        'status' => 110,
                        'message' => 'Delete Basket And Return user index App'
                    ]);
                }

            } else {
                $finishPrice = $session['totalPrice'] - $sessionPrice;
                if ($finishPrice > 0) {
                    Auth::user()->basket[0]->update(['finishPrice' => $finishPrice]);
                } else {
                    Auth::user()->basket[0]->delete();
                    return response([
                        'status' => 110,
                        'message' => 'Delete Basket And Return user index App'
                    ]);
                }
            }
        }


    }

    // hazine ersal va hade aghal kharid baraye ersale raygan
    public static function shippingCost($totalPrice)
    {
        $freeShipping = Systeminfmanage::where('id', 20)->whereStatus(1)->first()->code;
        $shippingCost = Systeminfmanage::where('id', 21)->whereStatus(1)->first()->code;

        if (isset($shippingCost) && $shippingCost > 0) {
            if (isset($freeShipping) && $totalPrice > $freeShipping) {
                \auth()->user()->basket[0]->update(['shippingCost' => null]);
                return $totalPrice;
            } elseif (isset($freeShipping) && $totalPrice <= $freeShipping) {
                \auth()->user()->basket[0]->update(['shippingCost' => $shippingCost]);
                return $totalPrice + $shippingCost;
            } else {
                \auth()->user()->basket[0]->update(['shippingCost' => $shippingCost]);
                return $totalPrice + $shippingCost;
            }
        }

    }

    /* private check session shipping cost */
    public static function checkSessionShippingCost($shippingCostSession = false)
    {
        if ($shippingCostSession == false) {
            return $shippingCostSession = \auth()->user()->basket[0]->shippingCost;
        }

        $freeShipping = Systeminfmanage::where('id', 20)->whereStatus(1)->first()->code;
        $shippingCost = Systeminfmanage::where('id', 21)->whereStatus(1)->first()->code;

        if (isset($shippingCost) && $shippingCost > 0) {
            if (isset($shippingCostSession) && !empty($shippingCostSession)) {
                if (isset($freeShipping) && $shippingCostSession > $freeShipping) {
                    return $shippingCost = 0;
                } elseif (isset($freeShipping) && $shippingCostSession <= $freeShipping) {
                    \auth()->user()->basket[0]->update(['shippingCost' => $shippingCost]);
                    return $shippingCost;
                } else {
                    \auth()->user()->basket[0]->update(['shippingCost' => $shippingCost]);
                    return $shippingCost;
                }
            }
        } else {
            $shippingCost = 0;
        }
        return $shippingCost;
    }

    public function calBasket($basket, $tax)
    {
        if (isset($basket) && !empty($basket)) {
            $priceDiscount = \auth()->user()->basket[0]->finishPrice;

            if (isset($tax) && isset($tax->code) && !empty($tax->code)) {
                $showTax = $tax->code . "%" . Lang::get('cms.tax');
                if (isset($priceDiscount) && !empty($priceDiscount)) {
                    $total = $priceDiscount;
                    if ($total <= 0) {
                        \auth()->user()->basket[0]->delete();
                        return [
                            'status' => 110,
                            'message' => 'redirect user index app and Remove basket'
                        ];
                    }

                    $priceDiscount = \App\Utility\taxCalculate::taxCalculate($total, 1);

                    $prices = \App\Utility\unit::unit($this->shippingCost($priceDiscount));
                } else {
                    $total = $basket['totalPrice'];

                    if ($total <= 0) {
                        \auth()->user()->basket[0]->delete();
                        return [
                            'status' => 110,
                            'message' => 'redirect user index app and Remove basket'
                        ];
                    }

                    $totalPrice = \App\Utility\taxCalculate::taxCalculate($total, 1);

                    $prices = \App\Utility\unit::unit($this->shippingCost($totalPrice));
                }
                return [
                    'showTax' => $showTax,
                    'prices' => $prices
                ];
            } else {
                if (isset($priceDiscount) && !empty($priceDiscount)) {
                    $total = $priceDiscount;
                    if ($total <= 0) {
                        \auth()->user()->basket[0]->delete();
                        return [
                            'status' => 110,
                            'message' => 'redirect user index app and Remove basket'
                        ];
                    }
                    $prices = isset($basket) && !empty($basket) ? \App\Utility\unit::unit($this->shippingCost($total)) : 0;
                } else {
                    $total = $basket['totalPrice'];
                    if ($total <= 0) {
                        \auth()->user()->basket[0]->delete();
                        return [
                            'status' => 110,
                            'message' => 'redirect user index app and Remove basket'
                        ];
                    }

                    $prices = isset($basket) && !empty($basket) ? \App\Utility\unit::unit($this->shippingCost($total)) : 0;
                }

                return [
                    'prices' => $prices
                ];
            }

        }


    }

}
