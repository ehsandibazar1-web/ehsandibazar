<?php

namespace App\Http\Controllers\Site;

use App\Model\Address;
use App\Model\Basket;
use App\Model\Coupon;
use App\Model\Discount;
use App\Model\Product;
use App\Model\Variation;
use App\User;
use App\Utility\checkSellerStock;
use App\Utility\DiscountType;
use App\Utility\forgetSession;
use App\Utility\ProductType;
use App\Utility\stdObject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;

class BasketController extends Controller
{
    protected $user;
    protected $olderSession;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
        $this->olderSession = Session::get('basket');
    }

    /* ajax - save to session */
    public function index(Request $request)
    {
        $productId = $request->input('productId');
        $color = $request->input('color');
        $size = $request->input('size');
        $sizeSingle = $request->input('sizeSingle');
        $sellerID = $request->input('sellerUserId');
        $single = $request->input('single');
        $object = new stdObject();
        $item = "";


        /* user login or not */
        if (!isset($this->user)) {
            return [
                'status' => 403,
                'message' => 'user not login'
            ];
        }

        /* validation user */
        $sellerUser = User::whereActive(1)->findOrFail($sellerID);

        /* validation product id */
        $findProduct = Product::with('variations')->findOrFail($productId);

        /* check variations */
        // color & size - elseif => no variation
        if (isset($color) && !empty($color) && $color != "" && isset($size) && !empty($size) && $size != "") {

            $isTrue = $this->colorAndSize($object, $sellerUser, $findProduct, $color, $size);
            /* check product found or not found */
            if ($isTrue != false) {
                $item = $isTrue['object'];
                $variation = $isTrue['variation_id'];

                /*  $this->olderSession = $;

                  if($item->count > ){

                  }*/

            } else {
                return [
                    'status' => 404,
                    'message' => 'not found product'
                ];
            }
        } elseif (empty($color) && $color == "" && empty($size) && $size == "" && $sizeSingle == "") {
            /* no variation just price */
            $isTrue = $this->noVariations($object, $sellerUser, $findProduct);
            if ($isTrue != false) {
                $item = $isTrue['object'];
                $variation = $isTrue['variation_id'];
            } else {
                return [
                    'status' => 404,
                    'message' => 'not found product'
                ];
            }
        }

        /* just color */
        if (isset($color) && !empty($color) && $color != "" && empty($size) && $sizeSingle == "" && $size == "") {
            /* no variation just price */
            $isTrue = $this->justColor($object, $sellerUser, $findProduct, $color);
            if ($isTrue != false) {
                $item = $isTrue['object'];
                $variation = $isTrue['variation_id'];
            } else {
                return [
                    'status' => 404,
                    'message' => 'not found product'
                ];
            }
        }

        /* just size */
        if (isset($sizeSingle) && !empty($sizeSingle) && $sizeSingle != "" && empty($color) && $color == "" && $size == "") {

            $isTrue = $this->justSize($object, $sellerUser, $findProduct, $sizeSingle);
            if ($isTrue != false) {
                $item = $isTrue['object'];
                $variation = $isTrue['variation_id'];
            } else {
                return [
                    'status' => 404,
                    'message' => 'not found product'
                ];
            }
        }


        /* validation one more time add product in basket  (single product :)  */
        if (isset($single) && $single == true) {
            $result = $this->oneMoreTimeAddToBasket($productId, $variation);
            if (in_array(false, $result)) {
                return [
                    'status' => 110
                ];
            }
        }


        $oldBasket = Session::has('basket') ? Session::get('basket') : null;
        if (isset($oldBasket, $oldBasket->items) && !empty($oldBasket)) {
            $checkAndLimitedBasket = self::TypeOfProduct(reset($oldBasket->items)['type'], $findProduct->type);
            if ($checkAndLimitedBasket['status'] == false) {
                return [
                    'status' => 102,
                    'message' => $checkAndLimitedBasket['message']
                ];
            }
        }
        $basket = new Basket($oldBasket);

        $basket->add($item, $variation);
        $request->session()->put('basket', $basket);

        $checkStock = checkSellerStock::checkSellerStock($item->variation_id);
        if ($checkStock == false) {
            return [
                'status' => 102,
                'message' => "موجودی محصول برای تعداد انتخابی شما کافی نمی باشد"
            ];
        }

        return [
            'status' => 200,
            'message' => 'add to basket',
        ];
    }

    /* delete from basket */
    public function deleteFromBasket(Request $request)
    {

        $variation_id = $request->input('variation_id');

        if (isset($variation_id) && !empty($variation_id) && is_numeric($variation_id)) {

            /* validation variation */
            $findVariation = Variation::whereStatus(1)->findOrFail($variation_id);

            $session = \Illuminate\Support\Facades\Session::get('basket');

            $basket = new \App\Model\Basket($session);

            $tt = $basket->deleteVariationBasket($findVariation->id);

            //return $tt;
            return [
                'status' => 200,
                'message' => 'success deleted'
            ];

        } else {
            return [
                'status' => 100,
                'message' => 'product not found',
            ];
        }
    }

    /* insert from basket */
    public function insertFromBasket(Request $request)
    {
        $variation_id = $request->input('variationID');


        if (isset($variation_id) && !empty($variation_id) && is_numeric($variation_id)) {
            /* validation variation */
            $findVariation = Variation::whereStatus(1)->findOrFail($variation_id);

            $session = \Illuminate\Support\Facades\Session::get('basket');
            $basket = new \App\Model\Basket($session);

            $basket->insertVariationBasket($findVariation->id);

            $checkStock = checkSellerStock::checkSellerStock($variation_id);
            if ($checkStock == false) {
                return [
                    'status' => 102,
                    'message' => "stock is not enough"
                ];
            }

            return [
                'status' => 200,
                'message' => 'success deleted'
            ];

        } else {
            return [
                'status' => 100,
                'message' => 'product not found',
            ];
        }
    }

    /* check address in basket */
    public function addressCheck(Request $request)
    {
        $addressId = $request->input('address');
        if (isset($addressId) && $addressId != null && is_numeric($addressId)) {
            $findAddress = Address::where('user_id', $this->user->id)->findOrFail($addressId);
            /* $address = Basket::addAddress($findAddress,$this->user->id);*/
            Session::put('address', $findAddress);
            return [
                'status' => 200,
                'message' => Session::get('address')
            ];
        } else {
            return [
                'status' => 100,
                'message' => 'آدرسی یافت نشد.'
            ];
        }

    }

    /* check coupon discount */
    public function checkCoupon(Request $request)
    {
        $discountPercent = "";
        $discountPrices = "";
        $coupon = $request->input('coupon');
        $user_id = $request->input('userId');
        $session = Session::get('basket');
        $isUsedCoupon = Session::get('coupon');
        if (isset($user_id) && is_numeric($user_id) && !empty($session)) {
            if (!empty($coupon)) {
                if (empty($isUsedCoupon) || $isUsedCoupon != $coupon) {
                    $findUser = User::findOrFail($user_id);
                    /* check used discount */
                    $resultCheck = $this->checkUsedUserDiscount($coupon, $this->user->id, "capacity");
                    if ($resultCheck === "capacity" || $resultCheck === "noQuery") {
                        return [
                            'status' => 100,
                            'message' => Lang::get('cms.coupon_is_not_available')
                        ];
                    } elseif (!$resultCheck) {
                        return [
                            'status' => 100,
                            'message' => Lang::get('cms.discount-coupon-used')
                        ];
                    }
                    /* check used discount */
              
                    if (isset($findUser->discount[0])) {
                        if (isset($findUser->discount[0]->discount)) {
                            $discount = $findUser->discount[0]->discount;
                            if (isset($findUser->discount[0]->discount->coupon[0])) {
                                $find = $findUser->discount[0]->discount->coupon[0];
                                $code = $find->code;
                                $expireDate = $find->expire_date;

                                /************/
                                if ($code != $coupon || $expireDate < Carbon::now()->timestamp || $findUser->discount[0]->is_used == 1) {
                                    return [
                                        'status' => 100,
                                        'message' => 'تخفیفی با این کد یافت نشد!'
                                    ];
                                } else {
                                    $totalPrice = $session->totalPrice;
                                    if ($discount->baseon == DiscountType::cent) {
                                        $calPrice = $totalPrice - ((($totalPrice * $discount->cent) / 100));
                                        $discountPercent = $discount->cent;
                                    } elseif ($discount->baseon == DiscountType::price) {
                                        $calPrice = $totalPrice - $discount->cent;
                                        $discountPrices = $discount->cent;
                                    } else {
//                                        Session::forget('basket');
//                                        Session::forget('coupon');
//                                        Session::forget('percent');
//                                        Session::forget('prices');
                                        forgetSession::forgetSession(0);

                                        return [
                                            'status' => 100,
                                            'message' => 'لطفا بعدا تلاش فرمایید!'
                                        ];
                                    }

//                                    if ($calPrice > 0) {
                                        // $session->totalPrice = $calPrice;
                                        if (isset($discountPercent) && !empty($discountPercent)) {
                                            Session::put('percent', $discountPercent);
                                        } else {
                                            Session::put('prices', $discountPrices);
                                        }
                                        Session::put('coupon', $coupon);
                                        Session::save();
                                        return [
                                            'status' => 200,
                                            'message' => 'تخفیف شما با موفقیت اعمال شد.'
                                        ];
//                                    }

                                    return [
                                        'status' => 100,
                                        'message' => 'تخفیف غیر مجاز میباشد !'
                                    ];

                                }
                                /************/
                            }
                        }
                    }
//                     elseif (isset($findUser->roles[0]) && isset($findUser->roles[0]->discount[0])) {
//                         $discount = $findUser->roles[0]->discount[0]->discount;
//                         if (isset($findUser->roles[0]->discount[0]->discount) && isset($findUser->roles[0]->discount[0]->discount->coupon[0])) {
//                             $find = $findUser->roles[0]->discount[0]->discount->coupon[0];
//                             $code = $find->code;
//                             $expireDate = $find->expire_date;

//                             /************/

//                             if ($code != $coupon || $expireDate < Carbon::now()->timestamp || $findUser->roles[0]->discount[0]->is_used == 1) {
//                                 return [
//                                     'status' => 100,
//                                     'message' => 'تخفیفی با این کد یافت نشد!'
//                                 ];
//                             } else {
//                                 $totalPrice = $session->totalPrice;
//                                 if ($discount->baseon == DiscountType::cent) {
//                                     $calPrice = $totalPrice - (($totalPrice * $discount->cent) / 100);
//                                     $discountPercent = $discount->cent;
//                                 } elseif ($discount->baseon == DiscountType::price) {
//                                     $calPrice = $totalPrice - $discount->cent;
//                                     $discountPrices = $discount->cent;
//                                 } else {
// //                                    Session::forget('basket');
// //                                    Session::forget('coupon');
// //                                    Session::forget('percent');
// //                                    Session::forget('prices');
//                                     forgetSession::forgetSession();
//                                     return [
//                                         'status' => 100,
//                                         'message' => 'لطفا بعدا تلاش فرمایید!'
//                                     ];
//                                 }

// //                                if ($calPrice > 0) {
//                                 //$session->totalPrice = $calPrice;
//                                 if (isset($discountPercent) && !empty($discountPercent)) {
//                                     Session::put('percent', $discountPercent);
//                                 } else {
//                                     Session::put('prices', $discountPrices);
//                                 }
//                                 Session::put('coupon', $coupon);
//                                 Session::save();
//                                 return [
//                                     'status' => 200,
//                                     'message' => 'تخفیف شما با موفقیت اعمال شد.'
//                                 ];
// //                                }

//                                 return [
//                                     'status' => 100,
//                                     'message' => 'تخفیف غیر مجاز میباشد !'
//                                 ];

//                             }
//                             /************/

//                         } else {
//                             return [
//                                 'status' => 100,
//                                 'message' => 'کد تخفیف مورد نظر یافت نشد.'
//                             ];
//                         }
//                     }
                    
                    //start test
                    elseif (isset($findUser->roles[0]) && isset($findUser->roles[0]->discount)) {
                        $c=[];
                         
                        foreach($findUser->roles[0]->discount as $dis){
                       
                            $c[]=$dis->discount->coupon[0]->code;
                        

                            if(in_array($coupon,$c)){
                                $discount=$dis->discount;
                                break;
                             
                            }
                        }
 
                        if (isset($discount) && isset($discount->coupon[0])) {
                            
                            $find = $discount->coupon[0];
                            $code = $find->code;
                            $expireDate = $find->expire_date;
    
                            /************/
    
                            if ($code != $coupon || $expireDate < Carbon::now()->timestamp || $discount->is_used == 1) {
                                return [
                                    'status' => 100,
                                    'message' => 'تخفیفی با این کد یافت نشد!'
                                ];
                            } else {
                                $totalPrice = $session->totalPrice;
                                if ($discount->baseon == DiscountType::cent) {
                                    $calPrice = $totalPrice - (($totalPrice * $discount->cent) / 100);
                                    $discountPercent = $discount->cent;
                                } elseif ($discount->baseon == DiscountType::price) {
                                    $calPrice = $totalPrice - $discount->cent;
                                    $discountPrices = $discount->cent;
                                } else {
    //                                    Session::forget('basket');
    //                                    Session::forget('coupon');
    //                                    Session::forget('percent');
    //                                    Session::forget('prices');
                                    forgetSession::forgetSession();
                                    return [
                                        'status' => 100,
                                        'message' => 'لطفا بعدا تلاش فرمایید!'
                                    ];
                                }
    
    //                                if ($calPrice > 0) {
                                //$session->totalPrice = $calPrice;
                                if (isset($discountPercent) && !empty($discountPercent)) {
                                    Session::put('percent', $discountPercent);
                                } else {
                                    Session::put('prices', $discountPrices);
                                }
                                Session::put('coupon', $coupon);
                                Session::save();
                                return [
                                    'status' => 200,
                                    'message' => 'تخفیف شما با موفقیت اعمال شد.'
                                ];
    //                                }
    
                                return [
                                    'status' => 100,
                                    'message' => 'تخفیف غیر مجاز میباشد !'
                                ];
    
                            }
                            /************/
    
                        } else {
                            return [
                                'status' => 100,
                                'message' => 'کد تخفیف مورد نظر یافت نشد.'
                            ];
                        }
                    }
                    //end test
                    else {
                        return [
                            'status' => 100,
                            'message' => 'کد تخفیف مورد نظر یافت نشد.'
                        ];
                    }
                } else {
                    return [
                        'status' => 100,
                        'message' => 'شما از این کد تخفیف استفاده کرده اید!'
                    ];
                }
            } else {
                return [
                    'status' => 100,
                    'message' => 'کوپن خود را وارد نمایید'
                ];
            }
        } else {
//            Session::forget('basket');
//            Session::forget('coupon');
//            Session::forget('percent');
//            Session::forget('prices');
            forgetSession::forgetSession();
            alert()->success('لطفا وارد حساب کاربری خود شوید.', Lang::get('error'));
            return redirect()->route('site.index');
        }
    }

    /* ======================  extra function =====================*/

    /* check user used discount */
    private function checkUsedUserDiscount($coupon, $user_id, $capacity = "capacity", $noQuery = "noQuery")
    {
     
        $findSessionCoupon = Coupon::with(['discount'])->where('code', $coupon)->where('expire_date', ">", \Illuminate\Support\Carbon::now()->timestamp)->first();

        if (!$findSessionCoupon) {
            return $noQuery;
        }
        $discount_id = $findSessionCoupon->discount->id;
        $discount = $findSessionCoupon->discount;
       
        if ($discount->count_user === 0) {
            return $capacity;
        }
        $checkDiscount = $discount->discountUser;

        foreach ($checkDiscount as $itemDiscountUser) {
            if ($itemDiscountUser->pivot->discount_id == $discount_id && $itemDiscountUser->pivot->user_id == $user_id) {
                return false;
            }
        }
        return true;
    }

    /* product has color and size */
    public function colorAndSize($object, $sellerUser, $findProduct, $color, $size)
    {
        $variation = Variation::where(
            [
                'user_id' => $sellerUser->id,
                'product_id' => $findProduct->id,
                'attribute_type_value_id' => $color
            ]
        )->first();
        // return $variation;
        if (isset($variation) && !empty($variation)) {
            if ($variation->relatedVariations[0]->attribute_type_value_id == $size) {
                $object->stock = $variation->count;
                $object->id = $variation->product->id;
                $object->title = $variation->product->title;
                $object->product_id = $findProduct->id;
                $object->seller = $sellerUser->id;
                $object->sellerName = $sellerUser->name;
                $object->sellerFamily = $sellerUser->family;
                $object->variation_id = $variation->id;
                $object->AttributeValue = $variation->attributeTypeValue->value;
                $object->relatedVariation = $variation->relatedVariations[0]->id;
                $object->relatedVariationValue = $variation->relatedVariations[0]->attributeTypeValue->value;
                $object->image = $variation->product->image[0]->url;
                $object->price = $variation->price;
                /* discount Price */
                $object->discountPrice = $variation->discountPrice;
                return [
                    'object' => $object,
                    'variation_id' => $variation->id
                ];
            }
        } else {
            return false;
        }
    }

    /* product doesn't color and size , product no variations  */
    public function noVariations($object, $sellerUser, $findProduct)
    {
        $variation = Variation::where(
            [
                'user_id' => $sellerUser->id,
                'product_id' => $findProduct->id,
                'attribute_type_value_id' => \App\Utility\Variation::NO_ATTRIBUTE
            ]
        )->first();
        // return $variation;
        if (isset($variation) && !empty($variation)) {
            $object->id = $variation->product->id;
            $object->title = $variation->product->title;
            $object->weight = $variation->product->weight;
            $object->shipping_cost = $variation->product->shipping_cost;
            $object->product_id = $findProduct->id;
            $object->type = $findProduct->type;
            $object->seller = $sellerUser->id;
            $object->variation_id = $variation->id;
            $object->image = $variation->product->image[0]->url;
            $object->price = $variation->price;

            $object->sellerName = $sellerUser->name;
            $object->sellerFamily = $sellerUser->family;


            /* discount Price */
            $object->discountPrice = $variation->discountPrice;
            return [
                'object' => $object,
                'variation_id' => $variation->id
            ];
        } else {
            return false;
        }
    }

    /* product just has color */
    public function justColor($object, $sellerUser, $findProduct, $color)
    {
        $variation = Variation::where(
            [
                'user_id' => $sellerUser->id,
                'product_id' => $findProduct->id,
                'attribute_type_value_id' => $color
            ]
        )->first();
        // return $variation;
        if (isset($variation) && !empty($variation)) {
            $object->id = $variation->product->id;
            $object->title = $variation->product->title;
            $object->product_id = $findProduct->id;
            $object->seller = $sellerUser->id;
            $object->variation_id = $variation->id;
            $object->image = $variation->product->image[0]->url;
            $object->price = $variation->price;

            $object->sellerName = $sellerUser->name;
            $object->sellerFamily = $sellerUser->family;
            $object->AttributeValue = $variation->attributeTypeValue->value;
            // $object->relatedVariationValue = $variation->relatedVariations[0]->attributeTypeValue->value;
            /* discount Price */
            $object->discountPrice = $variation->discountPrice;
            return [
                'object' => $object,
                'variation_id' => $variation->id
            ];
        } else {
            return false;
        }
    }

    /* product just has size */
    public function justSize($object, $sellerUser, $findProduct, $sizeSingle)
    {
        $variation = Variation::where(
            [
                'user_id' => $sellerUser->id,
                'product_id' => $findProduct->id,
                'attribute_type_value_id' => $sizeSingle
            ]
        )->first();
        // return $variation;
        if (isset($variation) && !empty($variation)) {
            $object->id = $variation->product->id;
            $object->title = $variation->product->title;
            $object->product_id = $findProduct->id;
            $object->seller = $sellerUser->id;
            $object->variation_id = $variation->id;
            $object->image = $variation->product->image[0]->url;
            $object->price = $variation->price;

            $object->sellerName = $sellerUser->name;
            $object->sellerFamily = $sellerUser->family;
            $object->AttributeValue = $variation->attributeTypeValue->value;
            /* discount Price */
            $object->discountPrice = $variation->discountPrice;
            return [
                'object' => $object,
                'variation_id' => $variation->id
            ];
        } else {
            return false;
        }
    }

    /* validation add-cart in  */
    private function oneMoreTimeAddToBasket($product_id, $variation_id)
    {
        $itemBasket = \session()->get('basket');
        $array = [];

        if (isset($itemBasket->items)) {
            foreach ($itemBasket->items as $item) {
                if ($item['item']->variation_id == $variation_id && $item['item']->product_id == $product_id) {
                    $array [] = false;
                } else {
                    $array [] = true;
                }
            }
        } else {
            $array [] = true;
        }

        return $array;
    }

    public static function TypeOfProduct($basketType = ProductType::SIMPLE, $typeProduct = ProductType::SIMPLE)
    {
        $digitalTypeOf = array(ProductType::PDF, ProductType::VIDEO, ProductType::VOICE);
        if (($typeProduct == $basketType) || (in_array($basketType, $digitalTypeOf) && in_array($typeProduct, $digitalTypeOf))) {
            return ['status' => true, 'message' => 'موفقیت آمیز'];
        } elseif ($basketType == ProductType::SIMPLE && in_array($typeProduct, $digitalTypeOf)) {
            return ['status' => false, 'message' => 'ابتدا محصول فیزیکی خود را سفارش دهید ، سپس اقدام به خرید محصول دیجیتال نمایید'];
        } elseif (in_array($basketType, $digitalTypeOf) && $typeProduct == ProductType::SIMPLE) {
            return ['status' => false, 'message' => 'ابتدا محصول دیجیتال خود را سفارش دهید ، سپس اقدام به خرید محصول فیزیکی نمایید'];
        }else{
            return ['status' => false, 'message' => 'ابتدا سبد خرید خود را نهایی کرده و سپس اقدام به خرید این محصول نمایید'];
        }

    }

}
