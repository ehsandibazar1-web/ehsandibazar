<?php

namespace App\Http\Controllers\Api\v1\site;

use App\Model\Product;
use App\Model\Systeminfmanage;
use App\Model\Variation;
use App\User;
use App\Utility\DiscountType;
use App\Utility\Status;
use App\Utility\unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SEO;

class ProductController extends Controller
{
    public function index()
    {

        // nothing
    }

   public function products($slug = null)
    {
        if (!empty($slug)) {
            $product = Product::with(['image', 'categories', 'attributevalues', 'comments', 'variations', 'user'])->whereSlug($slug)->whereStatus(1)->first();
            if (!isset($product)) {
                abort(404);
            }


            $comments = $product->comments()->with('user')->where('status', 1)->where('parent_id', 0)->latest()->get();


            /* validation  product */
            Product::with(['image', 'categories', 'attributevalues', 'comments', 'variations', 'user'])->findOrFail($product->id);

            /* Is Color */
            $isColor = $this->isColorAttribute($product);

            /* Is Size */
            $isSize = $this->isSizeAttribute($product);


            /* similar products */
            $similarProducts = Product::where('category_id', $product->categoryproduct->id)->where('id', '!=', $product->id)->where('status', 1)->limit(10)->get();
            /* product has attribute and attribute value not null */
            $arrayProductAttribute = [];
            $arrayProductAttributeGroup = [];
            foreach ($product->attributevalues as $itemAttributeValue) {
                $arrayProductAttribute[] = $itemAttributeValue->attribute_id;

                /* attributeValue - attribute - attributeGroup relation ship */
                $arrayProductAttributeGroup [] = $itemAttributeValue->attribute->attributeGroup->id;
            }
            $productAttribute = array_unique($arrayProductAttribute);
            $productAttributeGroup = array_unique($arrayProductAttributeGroup);


            /* return - conditions */
            $return_condition = Systeminfmanage::where('systeminf_id', 11)->whereStatus(1)->first();

            $product->increment('viewCount');

            $count = 0;
            foreach ($product->variations->toArray() as $itemVariationCount) {
                $count += $itemVariationCount['count'];
            }

           $sortVariation = $product->variations()->with('user')->get();
            $sortVariation = collect($sortVariation)->sortBy('price');

 $productss = Product::with(['image'])->whereStatus(1)->paginate(2);
            return response([
                'status' => 200,
                'data' => [
                    'product' => $product,
                    'return_condition' => $return_condition,
                    'similarProducts' => $similarProducts,
                    'productAttribute' => $product->attributevalues,
                    'productAttributeGroup' => $productAttributeGroup,
                    'isColor' => $isColor,
                    'isSize' => $isSize,
                    'comments' => $comments,
                    'count' => $count,
                    'sortVariation' => $sortVariation,
                    'productss' => $productss

                ],
                'message' => 'success',
            ]);


        } else {

            SEO::setTitle('product');
            $products = Product::with('image')->whereStatus(1)->paginate(12);
            $category = "";
            return response([
                'status' => 200,
                'data' => [
                    'products' => $products,
                    'category' => $category,
                ],
                'message' => 'success',
            ]);
        }
    }

    public function ajaxSeller(Request $request)
    {
        $product_id = $request->input('productId');
        $user_id = $request->input('user_id');

        if (isset($product_id) && isset($user_id) && !empty($user_id) && !empty($product_id) && is_numeric($user_id) && is_numeric($product_id)) {

            /* start  validation */
            $findProduct = Product::whereStatus(1)->findOrFail($product_id);
            $user_id = User::whereActive(1)->findOrFail($user_id);
            /* end  validation */

            //$findVariation = Variation::where('user_id', $user_id->id)->where('product_id', $findProduct->id)->where('status', Status::active)->first();
            $findVariation = Variation::where('user_id', $user_id->id)->where('product_id', $findProduct->id)->where('status', Status::active)->where('count', ">", 0)->first();

            // todo check when status 0
            /* todo bedone attribute hard code dare estefade mishe ba  id 3 => ke yani bedone khososiat */
            if ($findVariation->attributeTypeValue->attribute_type_id == 3) {

                /* check discount price */
                if ($findVariation->discountPrice == null) {
                    $discountPrice = "";
                } else {
                    $discountPrice = unit::unit($findVariation->discountPrice);
                }

                $price = unit::unit($findVariation->price);
                return response([
                    'status' => 200,
                    'data' => [
                        'variety' => 0,
                        'price' => $price,
                        'description' => $findVariation->description,
                        'priceDiscount' => $discountPrice
                    ],
                    'message' => 'success',
                ]);

            } else {

                $product = $findProduct;

                /* Is Color */
                $isColor = $this->isColorAttribute($product);

                /* Is Size */
                $isSize = $this->isSizeAttribute($product);


                $view = view('site.product.ajax.ajax-variety', compact('user_id', 'product', 'isColor', 'isSize'))->render();
  return response()->json([
                    'status' => 200,
                    'data' => [
                        'variety' => 1,
                        'price' => null,
                        'description' => null,
                        'priceDiscount' => null
                    ],
                    'message' => 'success',
                    'html' => $view
                ]);            }

        }
    }

    /* ajax variation :  color */
    public function ajaxVariationColor(Request $request)
    {
        $color = $request->input('variationColor');
        $productId = $request->input('productId');
        $user_id = $request->input('user_id');

        if (isset($productId) && !empty($productId) && is_numeric($productId) &&
            isset($user_id) && !empty($user_id) && is_numeric($user_id)) {

            $user_id = User::whereActive(1)->findOrFail($user_id);
            $product = Product::whereStatus(1)->findOrFail($productId);

            if (isset($color) && !empty($color) && is_numeric($color)) {

                $countSize = $this->isColor($product, $color, $user_id->id);

                if ($countSize <= 0) {
                    /* just color and no size */
                    $result = $this->getPriceSingle($product, $color, $user_id->id);
                    return [
                        'price' => $result['price'],
                        'description' => $result['description'],
                        'priceDiscount' => $result['priceDiscount'],
                    ];

                } else {
                    /* size for price lower */
                    $sizeLowerPrice = $this->selectedSize($color, $user_id->id, $product->id);
                    $view = view('site.product.ajax.ajax-show-size', compact('user_id', 'product', 'color', 'countSize', 'sizeLowerPrice'))->render();
                }
                return response()->json(
                    [
                        'html' => $view,
                        'price' => $sizeLowerPrice['price'],
                        'priceDiscount' => $sizeLowerPrice['priceDiscount'],
                        'description' => $sizeLowerPrice['description']
                    ]
                );
            }
        }
    }

    /* ajax variation :  size and color and just size*/
    public function ajaxVariationSize(Request $request)
    {
        $size = $request->input('variationSize');
        $productId = $request->input('productId');
        $color = $request->input('variationColors');
        $user_id = $request->input('user_id');
        if (isset($productId) && !empty($productId) && is_numeric($productId) && isset($user_id) && !empty($user_id) && is_numeric($user_id)) {

            $user_id = User::whereActive(1)->findOrFail($user_id);
            $product = Product::whereStatus(1)->findOrFail($productId);

            if (isset($size) && isset($color) && !empty($size) && !empty($color) && is_numeric($size) && is_numeric($color)) {

                $result = $this->getPrice($product, $color, $size, $user_id->id);
                return $result;

            } elseif (isset($size) && !empty($size) && is_numeric($size)) {

                $result = $this->getPriceSize($product, $size, $user_id->id);
                return $result;
            }
        }
    }


    //============================= extra function =========================
    private function isColorAttribute($product)
    {
        /* get color */
        $arrayColorVariations = [];
        foreach ($product->variations as $itemVariation) {
            if ($itemVariation->attributeTypeValue->attribute_type_id == \App\Utility\Variation::COLOR) {
                $arrayColorVariations [] = $itemVariation->attributeTypeValue->id;
            }
        }
        $isColorVariation = count($arrayColorVariations);
        return $isColorVariation;
    }

    private function isSizeAttribute($product)
    {
        $arraySizeVariations = [];
        foreach ($product->variations as $itemVariation) {
            if ($itemVariation->attributeTypeValue->attribute_type_id == \App\Utility\Variation::SIZE) {
                $arraySizeVariations [] = $itemVariation->attributeTypeValue->id;
            }
        }
        return $isSizeVariation = count($arraySizeVariations);
    }

    private function isColor($product, $color, $user_id)
    {
        $sizeCount = [];
        foreach ($product->variations as $itemVariation) {
            if ($itemVariation->attributeTypeValue->id == $color && $itemVariation->user_id == $user_id) {
                foreach ($itemVariation->relatedvariations as $itemRelationVariation) {
                    $sizeCount[] = $itemRelationVariation->attributeTypeValue->id;
                }
            }
        }
        return count($sizeCount);
    }

    /* get price when have size and color */
    private function getPrice($product, $color, $size, $user_id)
    {
        $price = "";
        $discountPrice = "";
        $description = "";
        foreach ($product->variations as $itemVariation) {
            if ($itemVariation->attributeTypeValue->id == $color && $itemVariation->user_id == $user_id) {
                foreach ($itemVariation->relatedvariations as $itemRelationVariation) {
                    if ($itemRelationVariation->attributeTypeValue->id == $size) {
                        $price = $itemRelationVariation->variation->price;
                        $discountPrice = $itemRelationVariation->variation->discountPrice;
                        $description = $itemRelationVariation->variation->description;
                    }
                }
            }
        }

        /* check discount price */
        if ($discountPrice == null) {
            $discountPrice = "";
        } else {
            $discountPrice = unit::unit($discountPrice);
        }

        $price = unit::unit($price);
        return [
            'description' => $description,
            'price' => $price,
            'priceDiscount' => $discountPrice
        ];
    }

    /* get price when have just size */
    private function getPriceSize($product, $size, $user_id)
    {
        $price = "";
        $description = "";
        $discountPrice = "";
        foreach ($product->variations as $itemVariation) {
            if ($itemVariation->attributeTypeValue->id == $size && $itemVariation->user_id == $user_id) {
                $description = $itemVariation->description;
                $price = $itemVariation->price;
                $discountPrice = $itemVariation->discountPrice;
            }
        }

        /* check discount price */
        if ($discountPrice == null) {
            $discountPrice = "";
        } else {
            $discountPrice = unit::unit($discountPrice);
        }

        $price = unit::unit($price);
        return [
            'description' => $description,
            'price' => $price,
            'priceDiscount' => $discountPrice
        ];
    }

    /* get price single => no size */
    private function getPriceSingle($product, $color, $user_id)
    {
        $price = "";
        $description = "";
        $discountPrice = "";
        foreach ($product->variations as $itemVariation) {
            if ($itemVariation->attributeTypeValue->id == $color && $itemVariation->user_id == $user_id) {
                $description = $itemVariation->description;
                $price = $itemVariation->price;
                $discountPrice = $itemVariation->discountPrice;
            }
        }

        /* check discount price */
        if ($discountPrice == null) {
            $discountPrice = "";
        } else {
            $discountPrice = unit::unit($discountPrice);
        }

        $price = unit::unit($price);
        return [
            'price' => $price,
            'description' => $description,
            'priceDiscount' => $discountPrice
        ];
    }

    /* selected size default by color and user_id */
    private function selectedSize($color, $user_id, $product_id)
    {
        $findVariation = Variation::where('attribute_type_value_id', $color)
            ->where('user_id', $user_id)
            ->where('product_id', $product_id)->get();

        $sortPrice = collect($findVariation)->sortBy('price');
        $sortPrice = $sortPrice->first();
        $sizeLowerPrice = $sortPrice->relatedvariations[0]->attribute_type_value_id;
//        $discountPrice = $this->discountProduct($findVariation[0]->id, $user_id);
//        $discountPrice = unit::unit($discountPrice);
        $price = unit::unit($findVariation[0]->price);

        /* check discount price */
        if ($findVariation[0]->discountPrice == null) {
            $discountPrice = "";
        } else {
            $discountPrice = unit::unit($findVariation[0]->discountPrice);
        }

        return [
            'sizeLower' => $sizeLowerPrice,
            'priceDiscount' => $discountPrice,
            'price' => $price,
            'description' => $sortPrice->description
        ];
    }


    /* ======================================  extra function (discount) old ======================================= */
    /*  discount single product  */
    private function discountProduct($variationId, $userId)
    {
        /* single product */
        $findVariation = Variation::with('discount')->where('id', $variationId)->where('user_id', $userId)->where('status', Status::active)->where('count', ">", 0)->first();
        if (isset($findVariation->discount[0])) {

            $discountSingleProduct = $findVariation->discount[0]->discount;

            $resultDiscountTime = $this->checkTypeDiscount($discountSingleProduct);
            if ($resultDiscountTime == true) {
                if ($discountSingleProduct->baseon == DiscountType::price) {
                    $priceProduct = $findVariation->price = $findVariation->price - $discountSingleProduct->cent;
                } else {
                    $priceProduct = $findVariation->price - (($findVariation->price * $discountSingleProduct->cent) / 100);
                }
                /* price have discount */
                return $priceProduct;

            } else {
                /* default price */
                return $findVariation->price;
            }
        } else {
            /* default price */
            return $findVariation->price;
        }
    }

    /* check discount Type */
    private function checkTypeDiscount($discount)
    {
        $discountCountUser = $this->checkCountUsedDiscount($discount);
        if (($discount->type == DiscountType::discountTime || $discount->type == DiscountType::amazing) && $discountCountUser == true) {
            return $this->checkExpire($discount);
        } elseif (($discount->type == DiscountType::discountSimple) && $discountCountUser == true) {
            return true;
        } else {
            return false;
        }
    }

    /* check count discount for used user */
    private function checkCountUsedDiscount($discount)
    {
        if (!empty($discount->count_user) && $discount->count_user > 0) {
            return true;
        } else {
            return false;
        }
    }

    /* check expire time for discount */
    private function checkExpire($discount)
    {
        $expire = $discount->discountTime[0]->expire_date;
        if ($expire >= Carbon::now()->timestamp) {
            return true;
        } else {
            return false;
        }
    }
}
