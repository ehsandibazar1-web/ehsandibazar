<?php

namespace App\Http\Controllers\Site;

use App\Model\Product;
use App\Model\Systeminf;
use App\Model\Systeminfmanage;
use App\Model\Variation;
use App\User;
use App\Utility\DiscountType;
use App\Utility\Message;
use App\Utility\ProductType;
use App\Utility\Status;
use App\Utility\unit;
use Carbon\Carbon;
use http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use SEOMeta;
use OpenGraph;
use Twitter;
use Artesaos\SEOTools\Facades\SEOTools;

## or
use SEO;
use willvincent\Rateable\Rating;

class ProductController extends Controller
{
    public function products($slug = null)
    {
      
        if (!empty($slug)) {

            $product = Product::with(['image','attributevalues', 'comments', 'variations', 'user'])->whereSlug($slug)->whereStatus(1)->firstOrFail();
 
            if(!empty($seo)){
                $seo=\App\Model\Seo::where([
                ['seoable_id',$product->id],
                ['seoable_type','product'],
                ])->first();
                SEOMeta::setTitle($seo->title);
                SEOMeta::setDescription($seo->description);
                SEOMeta::addKeyword($seo->keyword);
                OpenGraph::setTitle($seo->title);
                OpenGraph::setDescription($seo->description);
                Twitter::setTitle($seo->title);
                Twitter::setDescription($seo->description);
            }else{
                SEOTools::setTitle($product->title);
                SEOTools::setDescription($product->description);
            }
    

            $comments = $product->comments()->where('status', 1)->where('parent_id', 0)->latest()->paginate(5);
            /* Is Color */
            $isColor = $this->isColorAttribute($product);

            /* Is Size */
            $isSize = $this->isSizeAttribute($product);


            /* similar products */
            $similarProducts = $product->related;
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

            $deactiveBasket = Systeminfmanage::where('systeminf_id', 45)->first();

            $product->increment('viewCount');
            createMetaSite($product);
            SEO::opengraph()->setUrl(Url(''));
            OpenGraph::addImage(['url' => Url($product->image[0]->url), 'size' => 300]);
            SEO::opengraph()->addProperty('type', 'product');

            if ($product->type == ProductType::AUCTION) {
                if (Auth::check()) {
                    $latestSuggestion = $product->auction->suggestion->sortByDesc('amount')->first();
                    $latestSuggestion = isset($latestSuggestion) && !empty($latestSuggestion) ? Carbon::parse($latestSuggestion->created_at)->addSeconds(12)->timestamp : Carbon::now()->timestamp;
                    return view('site.product.auction', compact('latestSuggestion', 'product', 'return_condition', 'similarProducts', 'productAttribute', 'productAttributeGroup', 'isColor', 'isSize', 'comments'));
                } else {
                    alert()->warning("برای شرکت در حراجی ابتدا وارد شوید", "وارد شوید")->showConfirmButton("بستن");
                    return redirect()->route('login');
                }
            } else {
                return view('site.product.product', compact('product', 'return_condition', 'similarProducts', 'productAttribute', 'productAttributeGroup', 'isColor', 'isSize', 'comments', 'deactiveBasket'));
            }


        } else {
            $seo = Systeminf::find(50);
            createMetaSite($seo);
            $similarProducts = Product::whereType(ProductType::SIMPLE)->where('status', 1)->limit(10)->orderBy('viewCount', 'desc')->get();
            $products = Product::with('image')->whereType(ProductType::SIMPLE)->whereStatus(1)->paginate(12);
            $category = false;
            return view('site.product.product-list', compact('products', 'similarProducts', 'category'));
        }
    }

    public function compare()
    {
        SEO::setTitle('مقایسه محصول');
        if (session::has('compare')) {
            $products = Session::get('compare');
            $firstKey = array_key_first($products);
            $category = $products[$firstKey]->categories[0];
            $similarProducts = Product::whereStatus(1)->whereHas('categories', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            })->latest()->get();
            $categoryAttribute = $category->attributes->pluck('id')->toArray();
            $attributeGroup = $category->attributes->map(function ($q) use ($categoryAttribute, $category) {
                return $q->attributeGroup->with(['attributes' => function ($query) use ($categoryAttribute, $category) {
                    $query->whereIn('id', $categoryAttribute);
                    $query->with(['attributevalue' => function ($qAttr) use ($category) {
                        $qAttr->where('category_id', $category->id);
                    }]);
                }])->get();
            });
            $attributeGroup = isset($attributeGroup->unique()[0]) && !empty($attributeGroup->unique()[0]) ? $attributeGroup->unique()[0]: $attributeGroup;
            return view('site.product.compare', compact('products', 'category', 'attributeGroup', 'similarProducts'));
        } else {
            alert()->warning("لیست مقایسه شما خالی میباشد", "کاربر عزیز")->showConfirmButton('بستن');
            return redirect()->back();
        }
    }

    public function addCompare(Request $request)
    {
        $id = $request->input('id');
        $reload = $request->input('reload');
        $product = Product::with(['categories', 'attributevalues'])->findOrFail($id);

        if (session::has('compare')) {
            $compare = session::get('compare');
            $firstKey = array_key_first($compare);
            $category = $compare[$firstKey]->categories[0];
            $categoryTitle = $category->title;
            if ($category->id == $product->categories[0]->id) {
                if (count($compare) <= 3) {
                    if (array_key_exists($id, $compare)) {
                        // return redirect compare page ...
                        return [
                            'status' => 150,
                            'message' => 'محصول انتخابی در لیست مقایسه شما وجود دارد',
                        ];
                    } else {
                        $compare[$id] = $product;
                        Session::put('compare', $compare);
                    }
                } else {
                    return [
                        'status' => 150,
                        'message' => 'ظرفیت مقایسه محصولات ۴ محصول میباشد',
                    ];
                }
            } else {
                return [
                    'status' => 150,
                    'message' => "محصول انتخابی باید از دسته بندی $categoryTitle باشد ",
                ];
            }

        } else {
            $compare = array();
            $compare[$id] = $product;
            session::put('compare', $compare);
        }
        return [
            'status' => 200,
            'count' => count($compare),
            'reload' => $reload,
            'message' => 'محصول انتخابی به لیست مقایسه اضافه گردید',
        ];


    }

    public function removeCompare(Request $request)
    {
        $id = $request->input('id');
        $compare = Session::get('compare');
        if (isset($compare) && count($compare) <= 1) {
            Session::forget('compare');
            Session::save();
            return [
                'status' => 200,
                'message' => 'محصول مورد نظر از لیست مقایسه حذف گردید',
            ];
        }
        if (array_key_exists($id, $compare)) {
            unset($compare[$id]);
            Session::forget('compare');
            Session::save();
            Session::put('compare', $compare);
            Session::save();
            return [
                'status' => 200,
                'message' => 'محصول مورد نظر از لیست مقایسه حذف گردید',
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'محصول مورد نظر در لیست مقایسه وجود ندارد!',
            ];
        }


    }

    public function rating(Request $request)
    {
        $rate = $request->input('rate');
        $id = $request->input('id');
        $model = Product::findOrFail($id);
        if (isset($rate, $id) && $rate <= 5 || $rate >= 1) {
            $userRates = Auth::user()->rates()->pluck('rateable_id')->toArray();
            if (!in_array($model->id, $userRates)) {
                $rating = new Rating();
                $rating->rating = $rate;
                $rating->user_id = Auth::user()->id;
                $model->ratings()->save($rating);
                return [
                    'status' => 200,
                    'message' => "با تشکر امتیاز شما ثبت گردید",
                ];
            } else {
                return [
                    'status' => 100,
                    'message' => "شما قبلا امتیاز خود را ثبت کرده اید",
                ];
            }

        } else {
            return [
                'status' => 100,
                'message' => "امتیاز نا معتبر میباشد",
            ];
        }
    }

    public function does_url_exists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }


    public function getSignedUrl($id, $side = 1)
    {
        $url = URL::temporarySignedRoute('UserDownloadFile', now()->addHours(1), ['id' => $id, 'side' => $side, 'user' => Auth::user()->id]);
        return redirect($url);
    }

    public function getFile($id, $side = 1)
    {
        $find = Product::findOrFail($id);
        $file_path = $find->catalog[$side]->url;
        if ($this->does_url_exists($file_path)) {
            $assetPath = $file_path;
//            header("Cache-Control: public");
//            header("Content-Description: File Transfer");
//            header("Content-Disposition: attachment; filename=" . basename($assetPath));
//            header("Content-Type: " . get_headers($file_path, 1)['Content-Type']);
//                return readfile($assetPath);
            return response()->download($file_path);

        } else {
            exit('فایل در حال به روز رسانی می باشد و فعلا قابل دانلود نمی باشد.');
        }
    }

    public function loadF(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $sideInput = $request->input('side', 0);
        $side = 0;
        if (isset($sideInput) && $sideInput == 2) {
            $side = 1;
        }
        if (isset($product->catalog[$side]) && !empty($product->catalog[$side])) {
            $context = stream_context_create(array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            ));
            $address = $side == 1 ? $product->catalog[$side]->url : asset('public'.$product->catalog[$side]->url);

            $url = chunk_split(base64_encode(file_get_contents($address)));
            return response(['status' => 200, 'url' => $url]);
        } else {
            return response(['status' => 100, 'data' => false]);
        }
    }

    public function player(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $sideInput = $request->input('side', 0);
        $side = 0;
        if (isset($sideInput) && $sideInput == 2) {
            $side = 1;
        }
        ini_set("memory_limit", "-1");

        if (isset($product->video[$side]) && !empty($product->video[$side])) {
            $address = $side == 1 ? $product->video[$side]->url : asset('public/'.$product->video[$side]->url);
            $url = chunk_split(base64_encode(file_get_contents($address)));
            $view = view('site.product.partials.audio', compact('url'))->render();
            return response(['status' => 200, 'view' => $view]);
        } else {
            return response(['status' => 100, 'data' => false]);
        }
    }


    public function specialProducts()
    {
        SEO::setTitle('تخفیفات ویژه');
        $similarProducts = Product::whereType(ProductType::SIMPLE)->where('status', 1)->limit(10)->orderBy('viewCount', 'desc')->get();
        $products = Product::where('special', 1)->whereType(ProductType::SIMPLE)->with('image')->whereStatus(1)->paginate(12);
        $category = false;
        return view('site.product.product-list', compact('products', 'similarProducts', 'category'));

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
                return [
                    'variety' => 0,
                    'price' => $price,
                    'description' => $findVariation->description,
                    'priceDiscount' => $discountPrice
                ];
            } else {

                $product = $findProduct;

                /* Is Color */
                $isColor = $this->isColorAttribute($product);

                /* Is Size */
                $isSize = $this->isSizeAttribute($product);


                $view = view('site.product.ajax.ajax-variety', compact('user_id', 'product', 'isColor', 'isSize'))->render();
                return response()->json(['html' => $view]);
            }

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

                    if (isset($itemRelationVariation->attributeTypeValue->id)) {
                        $sizeCount[] = $itemRelationVariation->attributeTypeValue->id;
                    }

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
