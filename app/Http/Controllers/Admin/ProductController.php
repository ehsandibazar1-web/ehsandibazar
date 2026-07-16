<?php

namespace App\Http\Controllers\Admin;

use App\Model\AttributeTypeValue;
use App\Model\Auction;
use App\Model\Brand;


use App\Model\Category;
use App\Model\Related;
use App\Model\Relatedvariation;
use App\Model\Variation;
use App\Repositories\Repository;
use App\Services\catalogServices\catalogServices;
use App\Services\ImageServices\ImageServices;
use App\Model\AttributeValue;
use App\Model\Product;
use App\Services\videoServices\videoServices;
use App\User;
use App\Utility\Level;
use App\Utility\Message;
use App\Utility\ProductType;
use App\Utility\QR;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Spatie\PdfToImage\Pdf;
use SEO;

class ProductController extends Controller
{
    protected $user;
    public const countOfRender = 9;
    protected $allCategoryProducts;
    protected $allProduct;
    protected $allAttributeTypeValue;
    protected $allBrand;
    public $repository;


    /*protected $allGuaranty;*/

    public function __construct(Product $product)
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
        $this->allCategoryProducts = Category::query()->whereType(Product::class)->whereStatus(1)->get();
        $this->allProduct = Product::whereStatus(1)->get();
        $this->allAttributeTypeValue = AttributeTypeValue::whereStatus(1)->get();
        $this->allBrand = Brand::whereStatus(1)->get();
        $this->repository = new Repository($product);

    }

    public function index()
    {
        $title = Lang::get('cms.products');
        SEO::setTitle($title);
        $products = Product::with(['image', 'brand', 'auction'])->latest()->paginate(self::countOfRender);
        return view('panel.product.index', compact('products', 'title'));
    }

    public function create()
    {
        $title = Lang::get('cms.create-new-product');
        SEO::setTitle($title);
        $allProduct = $this->allProduct;
        $allCategoryProducts = $this->allCategoryProducts;
        $allAttributeTypeValue = $this->allAttributeTypeValue;
        $allBrand = $this->allBrand;
        return view('panel.product.create', compact('allCategoryProducts', 'allProduct', 'allAttributeTypeValue', 'title', 'allBrand'));
    }

    public function store(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $category_id = $request->input('category_id');
        $status = $request->input('status');
        $images = $request->input('filepath');
        $related = $request->input('related');
        $attributes = $request->input('attributes');
        $code = $request->input('code');
        $videos = $request->input('video');
        $videoTitle = $request->input('video_title');
        $catalog = $request->input('catalog');
        $brand = $request->input('brand');
        $score = $request->input('score');
        $weight = $request->input('weight');
        /* $guaranty = $request->input('guaranty');*/


        /* variation (details) */
        $countes = $request->input('countes');
        $prices = $request->input('prices');
        $attribute_type_value = $request->input('attribute_type_value_id');
        $attribute_type_related = $request->input('attribute_type_id_related');
        $desc = $request->input('desc');

        /* special && monetary */
        $special = $request->input('special');
        $sales = $request->input('sales');
        $momentary = $request->input('momentary');
        $selected_brand = $request->input('selected_brand');
        $type = $request->input('type');


        /* validation */
        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'category_id' => 'required|integer',
            'type' => 'required|integer',
            'status' => 'required|integer|between:0,1',
            // 'code' => [
            //     Rule::unique('products', 'code')->where(function ($query) {
            //         $query->where('deleted_at', null);
            //     }),
            // ],
            'brand' => 'required',
        ]);


        /* validation same details */
        $sameDetails = $this->validationVariationSameDetails($attribute_type_value, $attribute_type_related);
        if ($sameDetails == false) {
            return back()->with(['error' => Lang::get('cms.error-insert-same-product')]);
        }

        /* validation details [count , price , attribute type value ] */
        $result = $this->validationAttributeDetails($countes, $prices, $attribute_type_value);
        if ($result == false) {
            return back()->with(['error' => Message::Details]);
        }


        /* validation array images => tasvire shakhes */
        if (isset($images)) {
            foreach ($images as $key => $image) {
                if ($key == 0 && $image == null) {
                    return back()->with(['error' => 'لطفا تصویر شاخص را انتخاب نمایید.']);
                }
            }
        }
        /* validation array image - validation array video */


        /* validation brand */
        $findBrandId = Brand::whereStatus(1)->findOrFail($brand);

        $requestData = [
            'title' => $title,
            'slug' => $request->input('slug'),
            'user_id' => $this->user->id,
            'description' => $description,
            'type' => $type,
            'status' => $status,
            'code' => isset($code) ? $code : null,
            'weight' => isset($weight) && !empty($weight) ? $weight : 0,
            'special' => isset($special) && !empty($special) ? true : false,
            'sales' => isset($sales) && !empty($sales) ? true : false,
            'momentary' => isset($momentary) && !empty($momentary) ? true : false,
            'selected_brand' => isset($selected_brand) && !empty($selected_brand) ? true : false,
            'brand_id' => $findBrandId->id,
            'sorting' => $request->input('sorting', 0),
            'package_detail' => $request->input('package_detail'),
            'shipping_cost' => $request->input('shipping_cost'),

        ];


        /* validation score */
        if (isset($score)) {
            $result = $this->validationScoreProduct($score);
            if ($result == true) {
                $requestData['score'] = $score;
            } else {
                $requestData['score'] = 0;
            }
        } else {
            $requestData['score'] = 0;
        }


        $saveData = Product::create($requestData);

        if ($saveData instanceof Product) {

            $this->repository->sync($saveData, 'categories', [$category_id]);


            /* Start Of Save Auction*/
            if ($type == ProductType::AUCTION) {
                $auctionCal = $this->auctionCal($request->all());
                DB::beginTransaction();

                $saveAuction = Auction::create([
                    'product_id' => $saveData->id,
                    'start_date' => $this->convertToMiladi($request->input('start_date')),
                    'start_price' => $request->input('start_price'),
                    'end_price' => $request->input('end_price'),
                    'click_count' => $auctionCal['clickCount'],
                    'every_click_price' => $request->input('every_click_price'),
                    'every_click_price_for_pay' => $auctionCal['everyClickPriceForPay'],
                    'participant_count' => $request->input('participant_count'),
                ]);
                if ($saveAuction instanceof Auction) {
                    DB::commit();
                } else {
                    DB::rollBack();
                }
            }

            /* End Of Save Auction*/

            /* insert tags */
            $getDataTages = $request->input('tags');
            if ($getDataTages != null) {
                $saveData->tags()->sync($getDataTages);
            }

            /* insert step by step to variation and related-variation table */
            if (isset($attribute_type_value) && !empty($attribute_type_value[0])) {

                foreach ($attribute_type_value as $itemValueKey => $itemValue) {
                    $itemRelated = null;
                    if (isset($attribute_type_related) && !empty($attribute_type_related[$itemValueKey])) {
                        $itemRelated = $attribute_type_related[$itemValueKey];
                    }
                    $itemPrice = $prices[$itemValueKey];
                    $itemCountes = $countes[$itemValueKey];
                    $itemDesc = !empty($desc[$itemValueKey]) ? $desc[$itemValueKey] : "";
                    if ($itemRelated == null) {
                        // insert with out related
                        $resultVariation = Variation::create([
                            'user_id' => $this->user->id,
                            'product_id' => $saveData->id,
                            'attribute_type_value_id' => $itemValue,
                            'price' => $itemPrice,
                            'count' => $itemCountes,
                            'description' => $itemDesc,
                            'status' => 1
                        ]);
                        if (!$resultVariation) {
                            return back()->with(['error' => Message::illegalError]);
                        }
                    } else {
                        // insert with related
                        $resultVariation = Variation::create([
                            'user_id' => $this->user->id,
                            'product_id' => $saveData->id,
                            'attribute_type_value_id' => $itemValue,
                            'price' => $itemPrice,
                            'count' => $itemCountes,
                            'description' => $itemDesc,
                            'status' => 1
                        ]);

                        if ($resultVariation) {

                            $resultRelatedVariation = Relatedvariation::create([
                                /* mishod az rel estefade kard */
                                'variation_id' => $resultVariation->id,
                                'attribute_type_value_id' => $itemRelated,
                            ]);

                            if (!$resultRelatedVariation) {
                                return back()->with(['error' => Message::illegalError]);
                            }

                        } else {
                            return back()->with(['error' => Message::illegalError]);
                        }

                    }
                }
            }

            /* create Qr-code */
            QR::QRCode($saveData->slug);

            AdminController::createSeo($request,$saveData);

            $exception = null;

            /* start insert image */
            if ($images && !empty($images) && count($images) > 0) {
                /* first all delete image */
                ImageServices::delete_images($saveData);

                foreach ($images as $item) {
                    if ($item != null) {
                        ImageServices::arrayCreate_images($saveData, $item, $this->user->id);
                    }
                }
            } else {
                ImageServices::delete_images($saveData);
            }
            /* end insert image */


            /* start insert related */
            if ($related != null) {
                foreach ($related as $itemRelated) {
                    Related::create([
                        'relateable_id' => $saveData->id,
                        'relateable_type' => "product",
                        'related_id' => $itemRelated,
                    ]);
                }
            }

            /* end insert related */


            /* start insert video */
            if ($videos && !empty($videos) && count($videos) > 0) {
                /* first all delete video */
                videoServices::delete_videos($saveData);
                foreach ($videos as $key => $item) {
                    if ($item != null) {
                        videoServices::arrayCreate_videos($saveData, $item, $this->user->id, $videoTitle[$key]);
                    }
                }
            } else {
                videoServices::delete_videos($saveData);
            }
            /* end insert video */

            /* start insert catalog */
            if ($catalog && !empty($catalog) && count($catalog) > 0) {
                /* first all delete catalog */
                catalogServices::delete_catalog($saveData);
                foreach ($catalog as $key => $item) {
                    if ($item != null) {
                        $catalogCreate = catalogServices::create_catalog($saveData, $item, $this->user->id);
                        /* Start Of Convert Pdf To Image */
                        // $this->convertPdfToImage($item, $saveData->id, $catalogCreate, $key);
                        /* End Of Convert Pdf To Image */
                    }
                }
            } else {
                catalogServices::delete_catalog($saveData);
            }

            /* end insert catalog */


            /* start insert attributes to attributes_value */
            if ($exception == null) {
                $keyAttributesValue = [];
                /* add attribute and attribute extra  */
                if (isset($attributes) && !empty($attributes)) {
                    foreach ($attributes as $key => $value) {
                        if ($value != null) {
                            foreach ($value as $val) {
                                if ($val != null) {
                                    /* save to attribute value table */
                                    $saveToAttributeValue = AttributeValue::create([
                                        'attribute_id' => $key,
                                        'category_id' => $category_id,
                                        'user_id' => $this->user->id,
                                        'value' => $val
                                    ]);

                                    /* start attribute_value for sync to attribute_value_product */
                                    $keyAttributesValue[] = $saveToAttributeValue->id;
                                    /* end  attribute_value for sync to attribute_value_product */
                                }
                            }
                            if (isset($saveToAttributeValue) && !empty($saveToAttributeValue) && !$saveToAttributeValue instanceof AttributeValue) {
                                $exception = 1;
                            }
                        }
                    }
                }
            } else {
                $saveData->delete();
                toast()->error(Message::errorMessageCreate, 'خطا!');
                return back();
            }
            /* end insert attributes to attributes_value */


            /* start insert to attribute_value_product */
            if ($exception == null) {
                $syncAttributeValueProduct = $saveData->attributevalues()->sync($keyAttributesValue);
                if (!$syncAttributeValueProduct) {
                    $exception = 1;
                }
            }
            /* end insert to attribute_value_product */


            /* error handler */
            if ($exception == null) {
                toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
                return redirect()->route('panel.product.index');
            } else {
                toast()->error(Message::errorMessageCreate, 'خطا!');
                return back();
            }


        } else {
            toast()->error(Message::errorMessageCreate, 'خطا');
            return back();
        }

    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = Lang::get('cms.edit-new-product');
            SEO::setTitle($title);
            $allProduct = $this->allProduct;
            $findIdProducts = Product::with(['image', 'video', 'catalog', 'variations'])->findOrFail($id);
            $allCategoryProducts = $this->allCategoryProducts;
            $allAttributeTypeValue = $this->allAttributeTypeValue;
            $allBrand = $this->allBrand;
            /*  $allGuaranty = $this->allGuaranty;*/
            return view('panel.product.create', compact('allAttributeTypeValue', 'findIdProducts', 'allCategoryProducts', 'allProduct', 'title', 'allBrand'));
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {

            $findId = Product::findOrFail($id);

            $title = $request->input('title');
            $description = $request->input('description');
            /*   $price = $request->input('price');*/
            $category_id = $request->input('category_id');
            $status = $request->input('status');
            $images = $request->input('filepath');
            $attributes = $request->input('attributes');
            $code = $request->input('code');
            $videos = $request->input('video');
            $videoTitle = $request->input('video_title');
            $catalog = $request->input('catalog');
            $brand = $request->input('brand');
            $score = $request->input('score');
            $weight = $request->input('weight');
            $variationUserId = $request->input('variationUserId');

            /*  $guaranty = $request->input('guaranty');*/

            /* variation (details) */
            $countes = $request->input('countes');
            $prices = $request->input('prices');
            $attribute_type_value = $request->input('attribute_type_value_id');
            $attribute_type_related = $request->input('attribute_type_id_related');
            $desc = $request->input('desc');

            /* special && monetary */
            $special = $request->input('special');
            $sales = $request->input('sales');
            $momentary = $request->input('momentary');
            $selected_brand = $request->input('selected_brand');

            $type = $request->input('type');

            $this->validate($request, [
                'title' => 'required',
                'description' => 'required',
                'slug' => 'required',
                'category_id' => 'required|integer',
                'status' => 'required|integer|between:0,1',
                // 'code' => [
                //     Rule::unique('products', 'code')->where(function ($query) use ($id) {
                //         $query->where('deleted_at', null)->where('id', '!=', $id);
                //     }),
                // ],
                'brand' => 'required',
            ]);


            /* validation same details */
            $sameDetails = $this->validationVariationSameDetails($attribute_type_value, $attribute_type_related);
            if ($sameDetails == false) {
                return back()->with(['error' => 'خطا در وارد کردن جزییات محصول ، جزییات محصول مشابه می باشند.']);
            }

            /* validation details [count , price , attribute type value ] */
            $result = $this->validationAttributeDetails($countes, $prices, $attribute_type_value);
            if ($result == false) {
                return back()->with(['error' => Message::Details]);
            }


            /* validation array images => tasvire shakhes */
            if (isset($images)) {
                foreach ($images as $key => $image) {
                    if ($key == 0 && $image == null) {
                        return back()->with(['error' => 'لطفا تصویر شاخص را انتخاب نمایید.']);
                    }
                }
            }
            /* validation array image - validation array video */


            /* validation brand */
            $findBrandId = Brand::whereStatus(1)->findOrFail($brand);

            /* request data */
            $requestData = [
                'title' => $title,
                'slug' => $request->input('slug'),
                'description' => $description,
                'type' => $type,
                'status' => $status,
                'code' => isset($code) ? $code : null,
                'weight' => isset($weight) && !empty($weight) ? $weight : 0,
                'special' => isset($special) && !empty($special) ? true : false,
                'sales' => isset($sales) && !empty($sales) ? true : false,
                'selected_brand' => isset($selected_brand) && !empty($selected_brand) ? true : false,
                'momentary' => isset($momentary) && !empty($momentary) ? true : false,
                'brand_id' => $findBrandId->id,
                'sorting' => $request->input('sorting'),
                'package_detail' => $request->input('package_detail'),
                'shipping_cost' => $request->input('shipping_cost'),
            ];


            /* validation score */
            if (isset($score)) {
                $result = $this->validationScoreProduct($score);
                if ($result == true) {
                    $requestData['score'] = $score;
                } else {
                    $requestData['score'] = 0;
                }
            } else {
                $requestData['score'] = 0;
            }

            /* delete attribute because change category_id maybe */
            foreach ($findId->attributevalues as $itemAttribute) {
                $itemAttribute->delete();
            }

            $updateData = $findId->update($requestData);

            if ($updateData) {

                $this->repository->sync($findId, 'categories', [$category_id]);

                if ($type == ProductType::AUCTION) {
                    $auctionCal = $this->auctionCal($request->all());
                    /* Start Of Update Auction*/
                    $findId->auction->update([
                        'start_date' => $this->convertToMiladi($request->input('start_date')),
                        'start_price' => $request->input('start_price'),
                        'end_price' => $request->input('end_price'),
                        'every_click_price' => $request->input('every_click_price'),
                        'participant_count' => $request->input('participant_count'),
                        'click_count' => $auctionCal['clickCount'],
                        'every_click_price_for_pay' => $auctionCal['everyClickPriceForPay'],
                    ]);
                    /* End Of Update Auction*/
                }

                /* update tags morph */
                $getDataTages = $request->input('tags');
                if ($getDataTages != null) {
                    $findId->tags()->sync($getDataTages);
                }

                /* start insert related */
                $related = $request->input('related');

                $findId->related()->delete();
                if (isset($related) && !empty($related)) {
                    foreach ($related as $itemRelated) {
                        Related::create([
                            'relateable_id' => $findId->id,
                            'relateable_type' => "product",
                            'related_id' => $itemRelated,
                        ]);
                    }

                }

                AdminController::createSeo($request,$findId);


                /* end insert related */

                /* delete all variation and then create */
                /* delete just on admin user id */
                $deleteVariation = Variation::where('product_id', $findId->id)->delete();

                /* insert step by step to variation and related-variation table */
                if (isset($attribute_type_value) && !empty($attribute_type_value[0])) {

                    foreach ($attribute_type_value as $itemValueKey => $itemValue) {

                        $itemRelated = null;
                        if (isset($attribute_type_related) && !empty($attribute_type_related[$itemValueKey])) {
                            $itemRelated = $attribute_type_related[$itemValueKey];
                        }
                        $itemPrice = $prices[$itemValueKey];
                        $itemCountes = $countes[$itemValueKey];
                        $itemDesc = !empty($desc[$itemValueKey]) ? $desc[$itemValueKey] : "";
                        if ($itemRelated == null) {

                            // insert with out related
                            $resultVariation = Variation::withTrashed()->updateOrCreate(
                                [
                                    'product_id' => $findId->id,
                                    'user_id' => $findId->user_id,
                                    'attribute_type_value_id' => $itemValue,
                                ],
                                [
                                    'user_id' => $findId->user_id,
                                    'price' => $itemPrice,
                                    'count' => $itemCountes,
                                    'description' => $itemDesc,
                                    'discountPrice' => null,
                                    'discountActive' => null,
                                    'deleted_at' => null,
                                    'status' => 1
                                ]);
                            if (!$resultVariation) {
                                return back()->with(['error' => Message::illegalError]);
                            }
                        } else {
                            // insert with related
                            $resultVariation = Variation::withTrashed()->updateOrCreate(
                                [
                                    'product_id' => $findId->id,
                                    'user_id' => $findId->user_id,
                                    'attribute_type_value_id' => $itemValue,
                                ],
                                [
                                    'user_id' => $findId->user_id,
                                    'price' => $itemPrice,
                                    'count' => $itemCountes,
                                    'description' => $itemDesc,
                                    'discountPrice' => null,
                                    'discountActive' => null,
                                    'deleted_at' => null,
                                    'status' => 1
                                ]);

                            if ($resultVariation) {

                                $resultRelatedVariation = Relatedvariation::updateOrCreate([
                                    'variation_id' => $resultVariation->id,
                                    'attribute_type_value_id' => $itemRelated,
                                ]);

                                if (!$resultRelatedVariation) {
                                    return back()->with(['error' => Message::illegalError]);
                                }

                            } else {
                                return back()->with(['error' => Message::illegalError]);
                            }

                        }
                    }

                }

                /* update Qr-code */
                QR::QRCode($findId->slug);
                /* $file = public_path()."/upload/qr/".$findId->slug.".png";
                 QRCode::URL(env('webSiteAddress')."products/".$findId->slug)->setOutFile($file)->png();*/

                $exception = null;

                /* start insert image */
                if ($images && !empty($images) && count($images) > 0) {
                    /* first all delete image */
                    ImageServices::delete_images($findId);

                    foreach ($images as $item) {
                        if ($item != null) {
//                            if ($this->is_formatValid($item, config('whiteList.validImage'))) {
//                                if ($item != null) {
                            ImageServices::arrayCreate_images($findId, $item, $this->user->id);
//                                }
//                            } else {
//                                return back()->with(['error' => 'لطفا تصویر مناسب را وارد نمایید']);
//                            }
                        }
                    }
                } else {
                    ImageServices::delete_images($findId);
                }
                /* end insert image */

                /* start insert video */
                if ($videos && !empty($videos) && count($videos) > 0) {
                    /* first all delete video */
                    videoServices::delete_videos($findId);
                    foreach ($videos as $key => $item) {
                        if ($item != null) {
                            videoServices::arrayCreate_videos($findId, $item, $this->user->id, $videoTitle[$key]);
                        }
                    }
                } else {
                    videoServices::delete_videos($findId);
                }
                /* end insert video */

                /* start update catalog */
                if ($catalog && !empty($catalog) && count($catalog) > 0) {
                    /* first all delete catalog */
                    catalogServices::delete_catalog($findId);
                    foreach ($catalog as $key => $item) {
                        if ($item != null) {
                            $catalogCreate = catalogServices::create_catalog($findId, $item, $this->user->id);
                            /* Start Of Convert Pdf To Image */
                            // $this->convertPdfToImage($item, $findId->id, $catalogCreate, $key);
                            /* End Of Convert Pdf To Image */
                        }
                    }
                } else {
                    catalogServices::delete_catalog($findId);
                }
                /* end update catalog */


                /* start insert attributes to attributes_value */
                if ($exception == null) {
                    $keyAttributesValue = [];

                    /* add attribute and attribute extra  */
                    if ($attributes && !empty($attributes)) {
                        foreach ($attributes as $key => $value) {
                            if ($value != null) {
                                foreach ($value as $val) {
                                    if ($val != null) {
                                        /* save to attribute value table */
                                        $saveToAttributeValue = AttributeValue::create([
                                            'attribute_id' => $key,
                                            'category_id' => $category_id,
                                            'user_id' => $this->user->id,
                                            'value' => $val
                                        ]);
                                        /* start attribute_value for sync to attribute_value_product */
                                        $keyAttributesValue[] = $saveToAttributeValue->id;
                                        /* end  attribute_value for sync to attribute_value_product */
                                    }
                                }
                                if (isset($saveToAttributeValue) && !empty($saveToAttributeValue) && !$saveToAttributeValue instanceof AttributeValue) {
                                    $exception = 1;
                                }
                            }
                        }
                    }
                } else {
                    toast()->error(Message::errorMessageCreate, 'خطا!');
                    return back();
                }
                /* end insert attributes to attributes_value */


                /* start insert to attribute_value_product */
                if ($exception == null) {
                    $syncAttributeValueProduct = $findId->attributevalues()->sync($keyAttributesValue);
                    if (!$syncAttributeValueProduct) {
                        $exception = 1;
                    }
                }
                /* end insert to attribute_value_product */


                /* error handler */
                if ($exception == null) {
                    toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                    return redirect()->route('panel.product.index');
                } else {
                    toast()->error(Message::errorMessageEdit, 'خطا!');
                    return back();
                }

            } else {
                toast()->error(Message::errorMessageEdit, 'خطا');
                return back();
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Product::owner()->findOrFail($id);
            /* delete qr-code */
            $QRCODE = public_path() . "/upload/qr/" . $find->slug . ".png";;
            @unlink($QRCODE);
            $deleteData = $find->delete();

            /* delete guaranty */
            /* if ($deleteData) {
                 $find->guaranties()->detach();
             }*/

            if ($deleteData) {
                toast()->success(Message::successMessageDelete, 'موفقیت آمیز!');
                return back();
            } else {
                toast()->error(Message::errorMessageDelete, 'خطا');
                return back();
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function status($id)
    {
        if (is_numeric($id)) {
            $find = Product::findOrFail($id);
            if ($find->status == 0) {
                $data = [
                    'status' => 1
                ];

            } elseif ($find->status == 1) {
                $data = [
                    'status' => 0
                ];
            }
            $update = $find->update($data);
            if ($update) {
                toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                return back();
            } else {
                toast()->error(Message::errorMessageEdit, 'خطا');
                return back();
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function ajax_attributes(Request $request)
    {
        $category_id = $request->input('id');
        $product_id = $request->input('Product_id');

        if (is_numeric($category_id)) {
            $findCategory = Category::with('attributes')->findOrFail($category_id);

            $findProductAttribute = "";
            if (isset($product_id) && !empty($product_id)) {
                $findProductAttribute = Product::findOrFail($product_id);
                $findProductAttribute = collect($findProductAttribute->attributevalues)->unique('attribute_id');
            }

            /* uniq categoryAttributeGroup */
            $arrayAttributes = [];
            $arrayAttributesGroup = [];
            foreach ($findCategory->attributes as $item) {
                $arrayAttributesGroup [] = $item->attributeGroup;
                $arrayAttributes [] = $item;
            }
            $arrayAttributesGroup = array_unique($arrayAttributesGroup);


            $view = view('panel.product.ajax.allCategoryAttributeSelected', compact('findProductAttribute', 'findCategory', 'arrayAttributesGroup', 'arrayAttributes'))->render();
            return response()->json(['html' => $view]);
        } else {
            return "لطفا دسته بندی خود را انتخاب نمایید.";
        }
    }

    /* ajax attribute type value */
    public function ajax_attributes_type_value(Request $request)
    {
        $attributeTypeValue = $request->input('attributeTypeValue');
        $position = $request->input('position');
        $mode = $request->input('mode');
        $product_id = $request->input('product_id');
        /*$allGuaranty = $this->allGuaranty;*/

        // return $attributeTypeValue . " " .$position;
        if (isset($attributeTypeValue) && isset($position) && is_numeric($attributeTypeValue) && is_numeric($position)) {

            /* validation */
            $attributeGetFromAjax = AttributeTypeValue::whereStatus(1)->findOrFail($attributeTypeValue);

            /* get attribute */
            if ($attributeGetFromAjax->attribute_type_id == 1) {
                $allAttributeTypeValue = AttributeTypeValue::whereStatus(1)
                    ->where('attribute_type_id', '!=', $attributeGetFromAjax->attribute_type_id)
                    ->get();
            } else {
                $allAttributeTypeValue = "";
            }

            /* first input in details */
            if (isset($mode) && !empty($mode) && isset($product_id) && !empty($product_id)) {

                $findIdProducts = Product::with(['variations'])->findOrFail($product_id);
                $view = view('panel.product.ajax.allAttributeTypeValueEditFirst', compact('allAttributeTypeValue', 'position', 'findIdProducts'))->render();

                /* for each when edit mode in create blade  */
            } elseif (isset($product_id) && !empty($product_id)) {
                $findIdProducts = Product::findOrFail($product_id);

                $view = view('panel.product.ajax.allAttributeTypeValue', compact('findIdProducts', 'allAttributeTypeValue', 'position'))->render();
            } else {
                $view = view('panel.product.ajax.allAttributeTypeValue', compact('allAttributeTypeValue', 'position'))->render();
            }
            return response()->json(['html' => $view]);
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    /* ajax color or size */
    public function ajax_attributes_variations(Request $request)
    {
        $variations = $request->input('variations');
        if (isset($variations) && !empty($variations)) {
            $findVariations = AttributeTypeValue::whereStatus(1)->findOrFail($variations);
            return $findVariations->attribute_type_id;
        }
    }

    /*============================== extra function ================================*/

    public function auctionCal($request)
    {
        $startPrice = $request['start_price'];
        $endPrice = $request['end_price'];
        $participantCount = $request['participant_count'];
        $everyClickPrice = $request['every_click_price'];
        $clickCount = ((($endPrice - $startPrice) / $participantCount) / ($everyClickPrice));
        $everyClickPriceForPay = (($everyClickPrice * 10) / 100) * $clickCount;

        return [
            'clickCount' => $clickCount,
            'everyClickPriceForPay' => $everyClickPriceForPay,
        ];
    }

    /* isFormat valid */
    private function is_formatValid($file, $whiteList)
    {
        if (isset($file) && !empty($file)) {
            if ($file != null) {
                $explodeFile = explode(".", $file);
                if ($explodeFile && count($explodeFile) == 2) {
                    $fileReceived = strtolower($explodeFile[1]);
                    if (in_array($fileReceived, $whiteList)) {

                        if (!file_exists(trim(base_path() . $file))) {

                            return false;
                        }

                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
    }

    /* validation first */
    private function is_validFileArray($file, $whiteList, $notFountMessage, $whiteListMessage, $notFileFormat)
    {
        if (isset($file) && !empty($file)) {
            foreach ($file as $item) {
                if ($item != null) {
                    $explodeFile = explode(".", $item);
                    if ($explodeFile && count($explodeFile) == 2) {
                        $fileReceived = strtolower($explodeFile[1]);
                        if (in_array($fileReceived, $whiteList)) {
                            if (!file_exists(trim(base_path() . $item))) {
                                return $notFountMessage;
                            }
                        } else {
                            return $whiteListMessage;
                        }
                    } else {
                        return $notFileFormat;
                    }
                }
            }
        }
    }

    // get jalali date and convert to miladi
    private function convertToMiladi($date)
    {
        if (isset($date) && !empty($date)) {
            $explodeDate = explode("/", $date);
            if (count($explodeDate) == 3) {
                $times = explode(" ", $explodeDate[2]);
                $year = $this->convert2english($explodeDate[0]);
                $month = $this->convert2english($explodeDate[1]);
                $day = $this->convert2english($times[0]);

                $miladi = Verta::getGregorian($year, $month, $day); // [2015,12,25]

                $stringMiladi = $miladi[0] . "-" . $miladi[1] . "-" . $miladi[2] . " " . $this->convert2english($times[1]);
                return $timestamp = strtotime($stringMiladi);
            } else {
                return false;
            }
        }
    }

    public function convert2english($string)
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $string = str_replace($persianDecimal, $newNumbers, $string);
        $string = str_replace($arabicDecimal, $newNumbers, $string);
        $string = str_replace($arabic, $newNumbers, $string);
        return str_replace($persian, $newNumbers, $string);
    }


    // get timestamp and convert to jalali
    public static function convertToJalali($date)
    {
        return (new Verta($date))->format('Y/m/j H:i:s');
    }

    // get timestamp and convert to Miladi
    public static function TimestampToMiladi($unix_time_stamp)
    {
        $converted = Carbon::createFromTimestamp($unix_time_stamp, 'Asia/Tehran')
            ->toDateTimeString();
        return $converted;
    }

    /* check expire date => not today */
    private function checkExpireDate($date)
    {
        $timestampNow = time();
        if ($date <= $timestampNow) {
            return false;
        } else {
            return true;
        }
    }

    /* validation variation (details) */
    private function validationAttributeDetails($count, $price, $attribute_type_value)
    {
        $countes = 0;
        $prices = 0;
        $typeValue = 0;

        if (isset($count) && isset($price) && isset($attribute_type_value)) {

            foreach ($count as $itemCount) {
                $countes = 0;
                if ($itemCount == null) {
                    $countes = 1;
                    return false;
                }
            }

            if ($countes != 1) {
                foreach ($price as $itemPrice) {
                    $prices = 0;
                    if ($itemPrice == null) {
                        $prices = 1;
                        return false;
                    }
                }


            } else {
                return false;
            }

            if ($prices != 1) {

                foreach ($attribute_type_value as $itemTypeValue) {
                    $typeValue = 0;
                    if ($itemTypeValue == null) {
                        $typeValue = 1;
                        return false;
                    }
                }

            } else {
                return false;
            }

            return true;
        } else {
            return true;
        }
    }

    /* validation variation when same detail insert */
    private function validationVariationSameDetails($attribute_type_value, $attribute_type_related)
    {
        $array = [];
        $count = 0;
        if (isset($attribute_type_value) && isset($attribute_type_related)) {

            foreach ($attribute_type_value as $itemKey => $itemValue) {

                //dump(array_key_exists($itemKey,$attribute_type_related));

                if (array_key_exists($itemKey, $attribute_type_related)) {
                    $array[] = $itemValue . "," . $attribute_type_related[$itemKey];
                }
            }

            $count = count($array);

            if ($count > count(array_unique($array))) {
                return false;
            } else {

                return true;
            }
        } else {
            return true;
        }
    }

    private function validationScoreProduct($score)
    {
        if (is_numeric($score)) {
            if ($score > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function convertPdfToImage($pdfPath, $productId, $catalog, $key)
    {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes

        $pdfPath = "public" . $pdfPath;
        $pdf = new Pdf($pdfPath);
        $pdf->setOutputFormat('png');
        $pdf->setResolution(300);
        $pdf->setCompressionQuality(100);
        $pages = $pdf->getNumberOfPages();
        $dir = isset($key) && $key != 0 ? "/pdf-images/" . $productId . "/" : "/pdf-images/" . $productId . "/preview/";

        if (!file_exists(public_path() . $dir)) {
            File::makeDirectory(public_path() . '/' . $dir, 0777, true);
        }

        foreach (range(1, $pages) as $key => $page) {
            $storePath = $dir . $page;
            $pdf->setPage($page)->saveImage(public_path() . $storePath);
            ImageServices::arrayCreate_images($catalog, $storePath, $this->user->id);
        }
        return true;
    }

}
