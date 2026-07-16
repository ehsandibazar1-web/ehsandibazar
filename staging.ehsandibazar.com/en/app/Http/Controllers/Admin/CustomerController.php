<?php

namespace App\Http\Controllers\Admin;

use App\Model\AttributeTypeValue;
use App\Model\AttributeValue;
use App\Model\Brand;
use App\Model\Category;
use App\Model\Product;
use App\Model\Relatedvariation;
use App\Model\Variation;
use App\Services\catalogServices\catalogServices;
use App\Services\ImageServices\ImageServices;
use App\Services\videoServices\videoServices;
use App\User;
use App\Utility\Level;
use App\Utility\Message;
use App\Utility\ProductType;
use App\Utility\QR;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{

    protected $user;
    public const countOfRender = 9;
    protected $allAttributeTypeValue;
    protected $allUser;

    /*protected $allGuaranty;*/

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
        $this->allAttributeTypeValue = AttributeTypeValue::whereStatus(1)->get();
        $this->allUser = User::whereActive(1)->whereNotIn('level', Level::levelAdmins())->get();

        /*  $this->allGuaranty = Guaranty::whereStatus(1)->get();*/
    }

    public function index($id)
    {
        if (is_numeric($id)) {
            $findProduct = Product::with(['image', 'brand'])->findOrFail($id);
            $variationsFind = Variation::with(['user' => function ($query) {
                $query->whereNotIn('level', Level::getAdmins());
            }])->where('product_id', $id)->latest()->paginate(self::countOfRender);

            // dd($variationsFind[0]->user->id);
            $title = Lang::get('cms.list-request-product');
            $allAttributeTypeValue = $this->allAttributeTypeValue;
            $allUser = $this->allUser;
            return view('panel.customer.index', compact('variationsFind', 'allUser', 'findProduct', 'title', 'allAttributeTypeValue'));
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function create($id)
    {
        if (is_numeric($id)) {
            $findProduct = Product::with(['image', 'brand'])->findOrFail($id);
            $title = Lang::get('cms.create-new-product');
            $allAttributeTypeValue = $this->allAttributeTypeValue;
            $allUser = $this->allUser;
            return view('panel.customer.create', compact('allUser', 'findProduct', 'allAttributeTypeValue', 'title'));
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function store(Request $request)
    {
        /* variation (details) */
        $countes = $request->input('countes');
        $prices = $request->input('prices');
        $attribute_type_value = $request->input('attribute_type_value_id');
        $attribute_type_related = $request->input('attribute_type_id_related');
        $desc = $request->input('desc');
        $product_id = $request->input('product_id');
        $user_id_customer = $request->input('user_id_customer');

        /* validation user id customer */
        if (is_numeric($user_id_customer)) {
            $customerUser = User::whereActive(1)->findOrFail($user_id_customer);
        } else {
            return back()->with(['error' => Lang::get('cms.error-choose-customer')]);
        }

        /* validation product */
        if (is_numeric($product_id)) {
            $findProduct = Product::whereStatus(1)->findOrFail($product_id);
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }

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


        if ($findProduct) {

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
                            'user_id' => $customerUser->id,
                            'product_id' => $findProduct->id,
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
                            'user_id' => $customerUser->id,
                            'product_id' => $findProduct->id,
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
            toast()->success(Message::successMessageCreate, Lang::get('cms.success'));
            return redirect()->route('panel.customer', ['id' => $product_id]);

        } else {
            toast()->error(Message::errorMessageCreate, Lang::get('cms.error'));
            return back();
        }

    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = Lang::get('cms.edit-new-product');
            $allAttributeTypeValue = $this->allAttributeTypeValue;
            $variationFind = Variation::whereStatus(1)->findOrFail($id);
            $allUser = $this->allUser;

            /* get user variation id */
            $userVariationId = $variationFind->user_id;
            /* get product variation id */
            $productVariationId = $variationFind->product_id;

            /* find product id */
            $findProduct = Product::where('id', $productVariationId)->first();

            $findIdProducts = Product::with(['variations' => function ($query) use ($userVariationId) {
                $query->where('user_id', $userVariationId);
            }])->owner()->findOrFail($productVariationId);


            /*  $allGuaranty = $this->allGuaranty;*/
            return view('panel.customer.create', compact('allUser', 'findIdProducts', 'findProduct', 'userVariationId', 'allAttributeTypeValue', 'title'));
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }


    public function update(Request $request, $id)
    {
        $product_id = $request->input('product_id');
        $user_id_customer = $request->input('user_id_customer');
        if (is_numeric($id) && is_numeric($user_id_customer) && isset($user_id_customer) && isset($product_id)) {

            /* validation product */
            $findId = Product::owner()->findOrFail($product_id);

            /* validation user id customer */
            if (is_numeric($user_id_customer)) {
                $customerUser = User::whereActive(1)->findOrFail($user_id_customer);
            } else {
                return back()->with(['error' => Lang::get('cms.error-choose-customer')]);
            }

            /* variation (details) */
            $countes = $request->input('countes');
            $prices = $request->input('prices');
            $attribute_type_value = $request->input('attribute_type_value_id');
            $attribute_type_related = $request->input('attribute_type_id_related');
            $desc = $request->input('desc');


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


            /* delete attribute because change category_id maybe */
            foreach ($findId->attributevalues as $itemAttribute) {
                $itemAttribute->delete();
            }

            // $updateData = $findId->update($requestData);

            if ($findId) {
                /* delete all variation and then create */
                /* delete just on admin user id */
                $deleteVariation = Variation::where('product_id', $findId->id)->where('user_id', $customerUser->id)->delete();

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
                            $resultVariation = Variation::updateOrCreate([
                                'user_id' => $customerUser->id,
                                'product_id' => $findId->id,
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
                            $resultVariation = Variation::updateOrCreate([
                                'user_id' => $customerUser->id,
                                'product_id' => $findId->id,
                                'attribute_type_value_id' => $itemValue,
                                'price' => $itemPrice,
                                'count' => $itemCountes,
                                'description' => $itemDesc,
                                'status' => 1
                            ]);

                            if ($resultVariation) {

                                $resultRelatedVariation = Relatedvariation::updateOrCreate([
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


                $exception = null;


                /* error handler */
                if ($exception == null) {
                    toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                    return redirect()->route('panel.customer', ['id' => $product_id]);
                } else {
                    toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                    return back();
                }

            } else {
                toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return back();
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    /* variation each delete */
    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Variation::owner()->findOrFail($id);
            $deleteData = $find->delete();
            if ($deleteData) {
                toast()->success(Message::successMessageDelete, Lang::get('cms.success'));
                return back();
            } else {
                toast()->error(Message::errorMessageDelete, Lang::get('cms.error'));
                return back();
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    /* all user id in variation
       kolle variation haye karbari ke idish miad pak mishavad
     */
    public function allVariationDelete(Request $request, $id)
    {
        $product_id = $request->input('product_id');
        if (is_numeric($id) && is_numeric($product_id)) {
            $product_id = Product::whereStatus(1)->findOrFail($product_id);
            $findUser = User::whereActive(1)->findOrFail($id);
            $find = Variation::where('user_id', $findUser->id)->
            where('product_id', $product_id->id)->get();

            if (count($find) > 0) {

                foreach ($find as $itemVariation) {
                    $deleteVariationAll = $itemVariation->delete();
                }


                if ($deleteVariationAll) {
                    toast()->success(Message::successMessageDelete, Lang::get('cms.success'));
                    return back();
                } else {
                    toast()->error(Message::errorMessageDelete, Lang::get('cms.error'));
                    return back();
                }
            } else {
                toast()->error(Message::userNotAvailable, Lang::get('cms.error'));
                return back();
            }

        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function status($id)
    {
        if (is_numeric($id)) {
            $find = Product::owner()->findOrFail($id);
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


            $view = view('panel.customer.ajax.allCategoryAttributeSelected', compact('findProductAttribute', 'findCategory', 'arrayAttributesGroup', 'arrayAttributes'))->render();
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
        $user_id = $request->input('user_id');

        /* validation user */
        $userFind = User::whereActive(1)->findOrFail($user_id);

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
            if (isset($mode) && !empty($mode) && isset($product_id) && !empty($product_id) && isset($user_id) && !empty($user_id)) {

                $findIdProducts = Product::with(['variations' => function ($query) use ($userFind) {
                    $query->where('user_id', $userFind->id);
                }])->findOrFail($product_id);
                $view = view('panel.customer.ajax.allAttributeTypeValueEditFirst', compact('allAttributeTypeValue', 'position', 'findIdProducts'))->render();

                /* for each when edit mode in create blade  */
            } elseif (isset($product_id) && !empty($product_id)) {
                $findIdProducts = Product::findOrFail($product_id);

                $view = view('panel.customer.ajax.allAttributeTypeValue', compact('findIdProducts', 'allAttributeTypeValue', 'position'))->render();
            } else {
                $view = view('panel.customer.ajax.allAttributeTypeValue', compact('allAttributeTypeValue', 'position'))->render();
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
                $miladi = Verta::getGregorian($explodeDate[2], $explodeDate[1], $explodeDate[0]); // [2015,12,25]
                $stringMiladi = $miladi[0] . "-" . $miladi[1] . "-" . $miladi[2];
                return $timestamp = strtotime($stringMiladi);
            } else {
                return false;
            }
        }
    }

    // get timestamp and convert to jalali
    public static function convertToJalali($date)
    {
        return Verta::createTimestamp($date)->format('j/m/Y');
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


}
