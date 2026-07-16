<?php

namespace App\Model;

use App\Utility\DiscountType;
use App\Utility\forgetSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Basket extends Model
{
    public $items;
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart)
    {
        if ($oldCart) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    public function add($item, $variation_id)
    {
        $storedItem = ['qty' => 0, 'price' => $item->price, 'item' => $item,
            'discountPrice' => auth()->user()->isColleague() ? $item->price : $item->discountPrice,
            'type' => $item->type
//            'discount_count_buy' => $item->discountPrice
        ];

        if ($this->items) {
            if (array_key_exists($variation_id, $this->items)) {
                $storedItem = $this->items[$variation_id];
            }
        }
        $storedItem['qty']++;
//        dd($storedItem);
//
//        /* add new */
//        $findVariation = Variation::findOrFail($variation_id);
//        $findDiscount = isset($findVariation->discount[0]) && !is_null($findVariation->discount[0]) ? $findVariation->discount[0]->discount : null;
//        if (isset($findDiscount) && $findDiscount->user_id == $findVariation->user_id) {
//            if (isset($findDiscount) && ($findVariation->discountPrice == null) && !is_null($findDiscount) && $findDiscount->type == DiscountType::COUNTBUY && $storedItem['qty'] > $findDiscount->count_buy) {
//
//            }
//        }
        /* add new */


        $storedItem['price'] = $item->price * $storedItem['qty'];
        if (!is_null($item->discountPrice) && trim($item->discountPrice) != null && !empty($item->discountPrice)) {
            $storedItem['discountPrice'] =  auth()->user()->isColleague() ? $item->price * $storedItem['qty'] : $item->discountPrice * $storedItem['qty'];
        }
        $this->items[$variation_id] = $storedItem;

        /* total */
        if (is_null($item->discountPrice) || trim($item->discountPrice) == null || empty($item->discountPrice)) {
            $this->totalPrice += $item->price;
        } else {
            $this->totalPrice += auth()->user()->isColleague() ? $item->price : $item->discountPrice;
        }
        $this->totalQty++;

    }

    public static function addAddress($address, $user_id)
    {
        $storedItem = ['address' => $address, 'user_id' => $user_id];
        return $storedItem;
    }

    public function deleteVariationBasket($variation_id)
    {
        $sessionBasket = Session::get('basket');

        if (isset($sessionBasket) && !empty($sessionBasket) && isset($sessionBasket->items) && !empty($sessionBasket->items)) {
            foreach ($sessionBasket->items as $items) {
                if ($items['item']->variation_id == $variation_id) {
                    if ($items['qty'] > 1) {
                        //2
                        if (array_key_exists($variation_id, $sessionBasket->items)) {

                            // get variation id
                            $object = $items['item'];
                            $discountPrice = $items['item']->discountPrice;
                            $variationQty = $items['qty'] -= 1;

                            /* delete qty count buy */
                            $findVariation = Variation::findOrFail($variation_id);
                            $variationPrice = $items['item']->price * $variationQty;


                            $findDiscount = isset($findVariation->discount[0]) && !is_null($findVariation->discount[0]) ? $findVariation->discount[0]->discount : null;
                            if (isset($findDiscount) && $findDiscount->user_id == $findVariation->user_id) {

                                if (isset($findDiscount) && ($findVariation->discountPrice == null) && !is_null($findDiscount) && $findDiscount->type == DiscountType::COUNTBUY && $variationQty <= $findDiscount->count_buy) {

                                    $finishPrice = $variationPrice;
//                                    foreach ($sessionBasket->items as $items) {
//                                        if ($items['item']->variation_id != $object->variation_id) {
//                                            if (is_null($items['discountPrice']) || $items['discountPrice'] == 0) {
//                                                $finishPrice += $items['price'];
//                                            } else {
//                                                $finishPrice += $items['discountPrice'];
//                                            }
//                                        }
//                                    }

                                    $finishDiscountCountBuy =  $items['discountCountBuy'] = 0;
                                    foreach ($sessionBasket->items as $items) {
                                        if ($items['item']->variation_id != $object->variation_id) {
                                            if (is_null($items['discountPrice']) || $items['discountPrice'] == 0) {

                                                if (isset($items['discountCountBuy']) && !empty($items['discountCountBuy']) && $items['discountCountBuy'] > 0) {
                                                    $finishPrice   +=  $items['discountCountBuy'];
                                                } else {
                                                    $finishPrice += $items['price'];
                                                }

                                            } else {
                                                $finishPrice += $items['discountPrice'];
                                            }
                                        }
                                    }

                                    if ($finishPrice <= 0) {
                                        forgetSession::forgetSession(1);
                                        \session()->save();
                                        return;
                                    }


                                    $sessionBasket->items[$variation_id]['qty'] = $variationQty;
                                    $sessionBasket->items[$variation_id]['price'] = $variationPrice;
                                    $sessionBasket->items[$variation_id]['discountCountBuy'] = $finishDiscountCountBuy;
                                    $sessionBasket->totalQty = $sessionBasket->totalQty - 1;
                                    $sessionBasket->totalPrice = isset($finishPrice) && !empty($finishPrice) ? $finishPrice : $sessionBasket->totalPrice - $items['item']->price;

                                } elseif (isset($findDiscount) && ($findVariation->discountPrice == null) && !is_null($findDiscount) && $findDiscount->type == DiscountType::COUNTBUY && $variationQty > $findDiscount->count_buy) {

                                    if (isset($findDiscount) && $findDiscount->user_id == $findVariation->user_id) {

                                        if ($findDiscount->baseon == DiscountType::cent) {
                                            $cent = $findDiscount->cent;
                                            $variationPrice = $items['item']->price * $variationQty;
                                            $variationPriceCent = ($variationPrice * $cent) / 100;
                                            $finishPrice = $variationPrice - $variationPriceCent;
                                            $finishDiscountCountBuy = $variationPrice - $variationPriceCent;

//                                            foreach ($sessionBasket->items as $items) {
//                                                if ($items['item']->variation_id != $object->variation_id) {
//                                                    if (is_null($items['discountPrice']) || $items['discountPrice'] == 0) {
//                                                        $finishPrice += $items['price'];
//                                                    } else {
//                                                        $finishPrice += $items['discountPrice'];
//                                                    }
//                                                }
//                                            }

                                            foreach ($sessionBasket->items as $items) {
                                                if ($items['item']->variation_id != $object->variation_id) {
                                                    if (is_null($items['discountPrice']) || $items['discountPrice'] == 0) {

                                                        if (isset($items['discountCountBuy']) && !empty($items['discountCountBuy']) && $items['discountCountBuy'] > 0) {
                                                            $finishPrice   +=  $items['discountCountBuy'];
                                                        } else {
                                                            $finishPrice += $items['price'];
                                                        }

                                                    } else {
                                                        $finishPrice += $items['discountPrice'];
                                                    }
                                                }
                                            }

                                            if ($finishPrice <= 0) {
                                                forgetSession::forgetSession(1);
                                                \session()->save();
                                                return;
                                            }

                                            $sessionBasket->items[$variation_id]['discountCountBuy'] = $finishDiscountCountBuy;
                                            $sessionBasket->items[$variation_id]['qty'] = $variationQty;
                                            $sessionBasket->items[$variation_id]['price'] = $variationPrice;
                                            $sessionBasket->totalQty = $sessionBasket->totalQty - 1;

                                            $sessionBasket->totalPrice = isset($finishPrice) && !empty($finishPrice) ? $finishPrice : $sessionBasket->totalPrice - $items['item']->price;

                                        } else {
                                            $price = $findDiscount->cent;
                                            if ($items['item']->discountPrice == null) {
                                                $variationPrice = $items['item']->price * $variationQty;
                                                $finishPrice = $variationPrice - $price;
                                                $finishDiscountCountBuy = $variationPrice - $price;

//                                                foreach ($sessionBasket->items as $items) {
//                                                    if ($items['item']->variation_id != $object->variation_id) {
//                                                        if (is_null($items['discountPrice']) || $items['discountPrice'] == 0) {
//                                                            $finishPrice += $items['price'];
//                                                        } else {
//                                                            $finishPrice += $items['discountPrice'];
//                                                        }
//                                                    }
//                                                }
                                                foreach ($sessionBasket->items as $items) {
                                                    if ($items['item']->variation_id != $object->variation_id) {
                                                        if (is_null($items['discountPrice']) || $items['discountPrice'] == 0) {

                                                            if (isset($items['discountCountBuy']) && !empty($items['discountCountBuy']) && $items['discountCountBuy'] > 0) {
                                                                $finishPrice   +=  $items['discountCountBuy'];
                                                            } else {
                                                                $finishPrice += $items['price'];
                                                            }

                                                        } else {
                                                            $finishPrice += $items['discountPrice'];
                                                        }
                                                    }
                                                }

                                                if ($finishPrice <= 0) {
                                                    forgetSession::forgetSession(1);
                                                    \session()->save();
                                                    return;
                                                }
                                            }
                                            $sessionBasket->items[$variation_id]['discountCountBuy'] = $finishDiscountCountBuy;
                                            $sessionBasket->items[$variation_id]['qty'] = $variationQty;
                                            $sessionBasket->items[$variation_id]['price'] = $variationPrice;
                                            $sessionBasket->totalQty = $sessionBasket->totalQty - 1;
                                            $sessionBasket->totalPrice = isset($finishPrice) && !empty($finishPrice) ? $finishPrice : $sessionBasket->totalPrice - $items['item']->price;
                                        }

                                    }
                                }else{
                                    $variationPrice = $items['item']->price * $variationQty;
                                    $variationDiscountPrice = $items['item']->discountPrice * $variationQty;
                                    $sessionBasket->items[$variation_id]['qty'] = $variationQty;
                                    $sessionBasket->items[$variation_id]['price'] = $variationPrice;
                                    $sessionBasket->items[$variation_id]['discountPrice'] = $variationDiscountPrice;
                                    $sessionBasket->totalQty = $sessionBasket->totalQty - 1;
                                    $sessionBasket->totalPrice = isset($items['item']->discountPrice) && !empty($items['item']->discountPrice) ? $sessionBasket->totalPrice - $items['item']->discountPrice : $sessionBasket->totalPrice - $items['item']->price;
                                }
                            } else {

                                $variationPrice = $items['item']->price * $variationQty;
                                $variationDiscountPrice = $items['item']->discountPrice * $variationQty;
                                $sessionBasket->items[$variation_id]['qty'] = $variationQty;
                                $sessionBasket->items[$variation_id]['price'] = $variationPrice;
                                $sessionBasket->items[$variation_id]['discountPrice'] = $variationDiscountPrice;
                                $sessionBasket->totalQty = $sessionBasket->totalQty - 1;
                                $sessionBasket->totalPrice = isset($items['item']->discountPrice) && !empty($items['item']->discountPrice) ? $sessionBasket->totalPrice - $items['item']->discountPrice : $sessionBasket->totalPrice - $items['item']->price;
                            }
                            /* delete qty count buy */

                            \session()->save();
                            return;
                        }
                    } else {
                        // 1
                        if (array_key_exists($variation_id, $sessionBasket->items)) {

                            $variationQty = $items['qty'];

                            $discountPrice = $items['item']->discountPrice;

                            if (is_null($discountPrice) || $discountPrice == null || empty($discountPrice)) {
                                $variationPrice = $items['item']->price * $variationQty;
                            } else {
                                $variationPrice = $items['item']->discountPrice * $variationQty;
                            }

                            $sessionBasket->items[$variation_id]['qty'] = $variationQty;

                            if (is_null($discountPrice) || $discountPrice == null || empty($discountPrice)) {
                                $sessionBasket->items[$variation_id]['price'] = $variationPrice;
                            } else {
                                $sessionBasket->items[$variation_id]['discountPrice'] = $variationPrice;
                            }

                            $sessionBasket->totalQty = $sessionBasket->totalQty - $variationQty;
                            $sessionBasket->totalPrice = $sessionBasket->totalPrice - $variationPrice;
                            \session()->save();
                            unset($sessionBasket->items[$variation_id]);

                            if (empty($sessionBasket->items)) {
                                forgetSession::forgetSession(1);
                                \session()->save();
                            }
                        }
                        /*\session()->forget('basket');*/
                    }
                }

                // dd("asdasdasd");
                /*if ($items['item']->variation_id != $variation_id && array_key_exists($variation_id, $sessionBasket->items)) {
                    $oldBasket = \session()->has('basket') ? \session()->get('basket') : null;
                    $basket = new Basket($oldBasket);
                    $basket->add($items['item'], $items['item']->variation_id);
                    \session()->put('basket', $basket);
                }*/

            }
        } else {
            forgetSession::forgetSession(1);
            \session()->save();
        }
    }

    public function insertVariationBasket($variation_id)
    {
        $sessionBasket = Session::get('basket');
        if (isset($sessionBasket) && !empty($sessionBasket) && isset($sessionBasket->items) && !empty($sessionBasket->items)) {
            foreach ($sessionBasket->items as $items) {

                if ($items['item']->variation_id == $variation_id) {
                    // insert 1
                    if (array_key_exists($variation_id, $sessionBasket->items)) {

                        // get variation id
                        $object = $items['item'];
                        $variationQty = $items['qty'] += 1;
                        /* qty */
                        $findVariation = Variation::findOrFail($variation_id);
                        $finishPrices = 0;

                        $findDiscount = isset($findVariation->discount[0]) && !is_null($findVariation->discount[0]) ? $findVariation->discount[0]->discount : null;
                        if (isset($findDiscount) && $findDiscount->user_id == $findVariation->user_id) {
                            if (isset($findDiscount) && ($findVariation->discountPrice == null) && !is_null($findDiscount) && $findDiscount->type == DiscountType::COUNTBUY && $items['qty'] > $findDiscount->count_buy) {

                                if ($findDiscount->baseon == DiscountType::cent) {
                                    $cent = $findDiscount->cent;
                                    $variationPrice = $items['item']->price * $variationQty;
                                    $variationPriceCent = ($variationPrice * $cent) / 100;
                                    $finishPrice = $variationPrice - $variationPriceCent;
                                    $finishDiscountCountBuy = $variationPrice - $variationPriceCent;

                                    /* if(isset($sessionBasket->items[$variation_id]['discountCountBuy'])){
                                         dd($sessionBasket->items[$variation_id]['discountCountBuy']);
                                     }*/

                                    foreach ($sessionBasket->items as $items) {
                                        if ($items['item']->variation_id != $object->variation_id) {
                                            if (is_null($items['discountPrice']) || $items['discountPrice'] == 0) {

                                                if (isset($items['discountCountBuy']) && !empty($items['discountCountBuy']) && $items['discountCountBuy'] > 0) {
                                                    $finishPrice += $items['discountCountBuy'];
                                                } else {
                                                    $finishPrice += $items['price'];
                                                }

                                            } else {
                                                $finishPrice += $items['discountPrice'];
                                            }
                                        }
                                    }

                                    if ($finishPrice <= 0) {
                                        forgetSession::forgetSession(1);
                                        \session()->save();
                                        return;
                                    }
                                    // dd($finishPrice);
                                    $sessionBasket->items[$variation_id]['discountCountBuy'] = $finishDiscountCountBuy;
                                    $sessionBasket->items[$variation_id]['qty'] = $variationQty;
                                    $sessionBasket->items[$variation_id]['price'] = $variationPrice;
                                    //Session::put( $sessionBasket->items[$variation_id]['discountCountBuy'] , $finishPrice);

                                    $sessionBasket->totalQty = $sessionBasket->totalQty + 1;


                                    // dd($finishPrice);
                                    $sessionBasket->totalPrice = isset($finishPrice) && !empty($finishPrice) ? $finishPrice : $sessionBasket->totalPrice + $items['item']->price;

                                } else {
                                    $price = $findDiscount->cent;
                                    if ($items['item']->discountPrice == null) {
                                        $variationPrice = $items['item']->price * $variationQty;
                                        $finishPrice = $variationPrice - $price;
                                        $finishDiscountCountBuy = $variationPrice - $price;

                                        foreach ($sessionBasket->items as $items) {
                                            if ($items['item']->variation_id != $object->variation_id) {
                                                if (is_null($items['discountPrice']) || $items['discountPrice'] == 0) {

                                                    if (isset($items['discountCountBuy']) && !empty($items['discountCountBuy']) && $items['discountCountBuy'] > 0) {
                                                        $finishPrice += $items['discountCountBuy'];
                                                    } else {
                                                        $finishPrice += $items['price'];
                                                    }

                                                } else {
                                                    $finishPrice += $items['discountPrice'];
                                                }
                                            }
                                        }

                                        if ($finishPrice <= 0) {
                                            forgetSession::forgetSession(1);
                                            \session()->save();
                                            return;
                                        }
                                    }
                                    $sessionBasket->items[$variation_id]['discountCountBuy'] = $finishDiscountCountBuy;
                                    $sessionBasket->items[$variation_id]['qty'] = $variationQty;
                                    $sessionBasket->items[$variation_id]['price'] = $variationPrice;
                                    $sessionBasket->totalQty = $sessionBasket->totalQty + 1;
                                    $sessionBasket->totalPrice = isset($finishPrice) && !empty($finishPrice) ? $finishPrice : $sessionBasket->totalPrice + $items['item']->price;
                                }

                            } else {
                                $variationPrice = $items['item']->price * $variationQty;
                                $variationDiscountPrice = $items['item']->discountPrice * $variationQty;
                                $sessionBasket->items[$variation_id]['qty'] = $variationQty;
                                $sessionBasket->items[$variation_id]['price'] = $variationPrice;
                                $sessionBasket->items[$variation_id]['discountPrice'] = $variationDiscountPrice;
                                $sessionBasket->totalQty = $sessionBasket->totalQty + 1;
                                $sessionBasket->totalPrice = isset($items['item']->discountPrice) && !empty($items['item']->discountPrice) ? $sessionBasket->totalPrice + $items['item']->discountPrice : $sessionBasket->totalPrice + $items['item']->price;
                            }
                        } else {
                            $variationPrice = $items['item']->price * $variationQty;
                            $variationDiscountPrice = $items['item']->discountPrice * $variationQty;
                            $sessionBasket->items[$variation_id]['qty'] = $variationQty;
                            $sessionBasket->items[$variation_id]['price'] = $variationPrice;
                            $sessionBasket->items[$variation_id]['discountPrice'] = $variationDiscountPrice;
                            $sessionBasket->totalQty = $sessionBasket->totalQty + 1;
                            $sessionBasket->totalPrice = isset($items['item']->discountPrice) && !empty($items['item']->discountPrice) ? $sessionBasket->totalPrice + $items['item']->discountPrice : $sessionBasket->totalPrice + $items['item']->price;
                        }
                        \session()->save();
                        return;
                    }
                }
            }
        } else {
            forgetSession::forgetSession(1);
            \session()->save();
        }
    }
}
