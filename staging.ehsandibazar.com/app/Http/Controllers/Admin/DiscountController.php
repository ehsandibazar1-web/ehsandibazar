<?php

namespace App\Http\Controllers\Admin;

use App\Events\E_deleteSetNullForVariationInDiscount;
use App\Events\E_findVariationInDiscount;
use App\Events\E_updateSetNullForVariationInDiscount;
use App\Events\EventUpdateDiscountPrice;
use App\Model\Brand;
use App\Model\Category;
use App\Model\Discount;
use App\Model\Discountable;
use App\Model\Product;
use App\Model\Role;
use App\Model\Variation;
use App\Services\discountServices\DiscountServices;
use App\User;
use App\Utility\DiscountType;
use App\Utility\Level;
use App\Utility\Message;
use App\Utility\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Hekmatinasser\Verta\Verta;
use SEO;

class DiscountController extends Controller
{
    protected $user;
    protected $title;
    public $discount;
    protected $AllCategory;
    protected $AllBrand;
    protected $AllProduct;
    protected $AllUser;
    protected $AllRole;
    protected $users;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });

        $this->discount = Discount::with(['disable'])->latest()->paginate(self::countOfRender);
        $this->AllCategory = Category::whereType(Product::class)->whereStatus(1)->latest()->get();
        $this->AllBrand = Brand::whereStatus(1)->latest()->get();
//        $this->AllProduct = Product::with('variations')->whereStatus(1)->latest()->get();
        $this->AllUser = User::latest()->get();
        $this->AllRole = Role::latest()->get();
        $this->title = Lang::get('cms.discount');

        /* get user */
        $users = User::whereActive(1)->whereIn('level', [Level::SELLER, Level::ADMIN, Level::SUPER_ADMIN])->get();
        $collection = collect($users);
        $this->users = $collection->sortBy('level', SORT_DESC);
    }

    // Show All Discount
    public function index()
    {

        $title = $this->title;
        $discounts = $this->discount;
        SEO::setTitle($title);
        return view('panel.discount.index', compact('title', 'discounts'));
    }

    //Discount create form
    public function create()
    {
        $users = $this->users;
        $title = $this->title;
        SEO::setTitle($title);
        return view('panel.discount.create', compact('title', 'users'));
    }

    // Save OR Insert Discount In DataBase
    public function store(Request $request)
    {
        /* validation */
        $this->validate($request, [
            'title' => 'required',
            'baseon' => 'required|integer',
            'cent' => 'required|numeric',
            'discount_type' => 'required|numeric',
            'discountable_type' => 'required|numeric',
//            'status' => 'required|integer|between:0,1',
            'user_id' => 'required'
        ]);

        // Get discount types
        $typeSimple = \App\Utility\DiscountType::discountSimple;
        $typeCode = \App\Utility\DiscountType::discountCode;
        $typeCodeTime = \App\Utility\DiscountType::discountCodeTime;
        $typeTime = \App\Utility\DiscountType::discountTime;
        $coupon = \App\Utility\DiscountType::coupon;
        $amazing = \App\Utility\DiscountType::amazing;
        $countBuy = \App\Utility\DiscountType::COUNTBUY;

        //Get the class that we want to apply to that discount
        $categoryNumber = \App\Utility\DiscountType::category;
        $brandNumber = \App\Utility\DiscountType::brand;
        $productNumber = \App\Utility\DiscountType::product;
        $userNumber = \App\Utility\DiscountType::user;
        $roleNumber = \App\Utility\DiscountType::role;

        // Get Request From Input
        $title = $request->input('title');
        $description = $request->input('description');
        $baseon = $request->input('baseon');
        $cent = $request->input('cent');
        $type = $request->input('discount_type');
        $discountableId = $request->input('discountable_id');
        $discountableType = $request->input('discountable_type');
        $status = $request->input('status', 1);
        $count_user = $request->input('count_user');
        $count_buy = $request->input('count_buy');
        $user_id = $request->input('user_id');

        /* start validation user id */
        $userFind = User::whereActive(1)->findOrFail($user_id);
        /* end validation user id */

        if (is_null($count_user) || $count_user > 0) {
            $count_user = $request->input('count_user');
        } else {
            toast()->error("تعداد کاربر نباید 0 باشد", Lang::get('cms.error'));
            return back();
        }
        if (isset($description) && $description != "") {
            $description = $request->input('description');
        } else {
            $description = null;
        }
        $code = $request->input('code');
        $expireDate = $request->input('expire_date');
        $startDate = $request->input('start_date');
        $startDateData = $this->convertToMiladi($startDate);

        //Checking was empty and isset a type of discount

        if (isset($type) && $type != "") {

            $createDiscount = DiscountServices::create_discount($userFind, $title, $description,
                $baseon, $cent, $type, $discountableType, $count_user, $count_buy, $status);


            $break = [];
            foreach ($discountableId as $item) {
                //Start Of check And Get Class Model
                if ($discountableType == $categoryNumber) {

                    $results = event(new E_findVariationInDiscount(Category::class, $user_id, $item, $discountableType, $baseon, $cent));

                    $count = 0;
                    $messageCategory = false;
                    if ($results[0]['apply']) {
                        foreach ($results[0]['apply'] as $itemApply) {
                            if ($itemApply == false) {
                                $count++;
                            }
                        }
                    }
                    $resultApply = $results[0]['apply'];
                    $find = $results[0]['find'];
                    if (empty($resultApply) || is_null($resultApply)) {
                        $break  [] = true;
                        break;
                    } else {
                        $break  [] = false;
                    }

                } elseif ($discountableType == $brandNumber) {

                    $results = event(new E_findVariationInDiscount(Brand::class, $user_id, $item, $discountableType, $baseon, $cent));

                    $count = 0;
                    $messageCategory = false;
                    if ($results[0]['apply']) {
                        foreach ($results[0]['apply'] as $itemApply) {
                            if ($itemApply == false) {
                                $count++;
                            }
                        }
                    }

                    $resultApply = $results[0]['apply'];
                    $find = $results[0]['find'];
                    if (empty($resultApply) || is_null($resultApply)) {
                        $break  [] = true;
                    } else {
                        $break  [] = false;
                    }

                } elseif ($discountableType == $productNumber && empty($count_buy)) {
                    $messageCategory = false;
                    $find = \App\Model\Variation::with('discount')->findOrfail($item);
                    $discountCountBuy = self::HasDiscountCountBuy($find->id, $type);
                    if ($discountCountBuy == false && $startDateData <= Carbon::now()->timestamp) {
                        $updateDiscountPriceForVariation = DiscountServices::update_discountPriceVariation($find, $baseon, $cent, $discountableType);
                    }
                    if (isset($updateDiscountPriceForVariation) && $updateDiscountPriceForVariation == false) {
                        toast()->error('مقدار تخفیف بشتر از قیمت محصول می باشد', Lang::get('cms.error'));
                        return back();
                    }

                } elseif ($discountableType == $productNumber && !empty($count_buy)) {
                    $find = \App\Model\Variation::with('discount')->findOrfail($item);
                    $discountCountBuy = self::HasDiscountCountBuy($find->id, $type);
                    if ($discountCountBuy == true) {
                        $break  [] = true;
                        break;
                    } else {
                        $break  [] = false;
                    }

                } elseif ($discountableType == $userNumber) {
                    $find = User::with('discount')->findOrfail($item);
                } elseif ($discountableType == $roleNumber) {
                    $find = Role::with('discount')->findOrfail($item);
                } else {
                    toast()->error(Message::errorMessageCreate, Lang::get('cms.error'));
                    return back();
                }
                //End Of check And Get Class Model

                /* create in discountable */

                $createDiscountAble = DiscountServices::create_discountable($find, $createDiscount);
                if (!$createDiscountAble instanceof Discountable) {
                    toast()->error('خطا در انجام عملیات', Lang::get('cms.error'));
                    return back();
                }
            }


            /* delete dsicount */
            $hasDiscountable = $createDiscount->disable;
            if (isset($break) && !in_array(false, $break) && isset($hasDiscountable) && count($hasDiscountable) <= 0) {
                $createDiscount->delete();
                toast()->error(Message::errorMessageCreate, Lang::get('cms.error'));
                return redirect()->route('panel.discount.index');
            }

            if ($type == $typeCode && isset($code) && $code != "") {
                $createDiscount->discountCode()->create(['code' => $code]);
            } elseif ($type == $typeCodeTime && isset($expireDate, $code) && !empty([$code, $expireDate])) {
                $discountCode = $createDiscount->discountCode()->create(['code' => $code]);
                $date = $this->convertToMiladi($expireDate);
                $discountCode->discountCodeTime()->create(['expire_date' => $date]);
            } elseif ($type == $typeTime && isset($expireDate) && !empty($expireDate)) {
                $date = $this->convertToMiladi($expireDate);
                $createDiscount->discountTime()->create(['expire_date' => $date]);
            } elseif ($type == $coupon && isset($expireDate, $code) && !empty([$code, $expireDate])) {
                $date = $this->convertToMiladi($expireDate);
                $createDiscount->coupon()->create(['expire_date' => $date, 'code' => $code]);
            } elseif ($type == $amazing && isset($expireDate, $startDate) && !empty($expireDate)) {
                $date = $this->convertToMiladi($expireDate);
                $createDiscount->discountTime()->create(['expire_date' => $date, 'start_date' => $startDateData]);
            } else {
                //hichi OR Count Buy...
            }

            if (isset($break) && !in_array(false, $break) && isset($results) && ($count == count($results[0]['apply']))) {
                $createDiscount->delete();
                $messageCategory = true;
            }

            if (isset($break) && !in_array(false, $break) && $find == false || (isset($messageCategory) && $messageCategory == true)) {
                toast()->error('تخفیف شما اعمال نشد', Lang::get('cms.error'));
                return redirect()->route('panel.discount.index');
            } else {
                toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
                return redirect()->route('panel.discount.index');
            }
        }

        toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
        return redirect()->route('panel.discount.index');
    }

    //Edit Discount
    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = $this->title;
            $users = $this->users;
            SEO::setTitle($title);
            $discount = Discount::with(['discountCode', 'discountTime', 'coupon', 'user'])->owner()->findOrfail($id);
            return view('panel.discount.create', compact('title', 'discount', 'users'));
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    //Update Discount
    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            /* Start of validation */
            $this->validate($request, [
                'title' => 'required',
                'baseon' => 'required',
                'cent' => 'required|numeric',
                'discount_type' => 'required|numeric',
//                'status' => 'required|integer|between:0,1',
                'discountable_type' => 'required|numeric',
                'user_id' => 'required'
            ]);
            /* End Of validation */


            //Satart Of Get discount types
            $typeSimple = \App\Utility\DiscountType::discountSimple;
            $typeCode = \App\Utility\DiscountType::discountCode;
            $typeCodeTime = \App\Utility\DiscountType::discountCodeTime;
            $typeTime = \App\Utility\DiscountType::discountTime;
            $coupon = \App\Utility\DiscountType::coupon;
            $amazing = \App\Utility\DiscountType::amazing;
            $countBuy = \App\Utility\DiscountType::COUNTBUY;

            //End Of Get discount types

            // Start Of  Get Request From Input
            $title = $request->input('title');
            $description = $request->input('description');
            $baseon = $request->input('baseon');
            $cent = $request->input('cent');
            $type = $request->input('discount_type');
            $discountableId = $request->input('discountable_id');
            $discountableType = $request->input('discountable_type');
            $status = $request->input('status', 1);
            $typeOld = $request->input('type_old');
            $count_user = $request->input('count_user');
            $count_buy = $request->input('count_buy');
            $user_id = $request->input('user_id');
            $startDate = $request->input('start_date');
            $startDateData = $this->convertToMiladi($startDate);

            /* start validation user */
            User::whereActive(1)->findOrFail($user_id);
            /* end validation user */

            $updateData = [
                'title' => $title,
                'baseon' => $baseon,
                'cent' => $cent,
                'type' => $type,
                'discountable_type' => $discountableType,
                'count_buy' => $count_buy,
                'status' => $status,
                'user_id' => $user_id
            ];

            // find Discount
            $discount = Discount::with('disable')->findOrfail($id);


            if ((is_null($count_user) || $count_user > 0)) {
                $updateData['count_user'] = $request->input('count_user');
            } else {
                event(new EventUpdateDiscountPrice($discount));
//                $updateData['count_user'] = $count_user = 0;
                toast()->error("تعداد کاربر نباید 0 باشد", Lang::get('cms.error'));
                return back();
            }

            if (isset($description) && $description != "") {
                $updateData['description'] = $request->input('description');
            } else {
                $updateData['description'] = null;
            }
            $code = $request->input('code');
            $expireDate = $request->input('expire_date');
            // End Of  Get Request From Input

            //Get the class that we want to apply to that discount
            $categoryNumber = \App\Utility\DiscountType::category;
            $brandNumber = \App\Utility\DiscountType::brand;
            $productNumber = \App\Utility\DiscountType::product;
            $userNumber = \App\Utility\DiscountType::user;
            $roleNumber = \App\Utility\DiscountType::role;


            //Checking was empty and isset a type of discount
            if (isset($type) && $type != "" && isset($typeOld) && $typeOld != "") {
                // Delete Product Or Category Or Brand On Discountable Table
                foreach ($discount->disable()->get() as $item) {

                    /* set null for discountPrice in variation */

                    /*  type not change */
                    $updateVariation = "";
                    if ($discount->discountable_type == $productNumber && empty($count_buy)) {
                        $findVariation = \App\Model\Variation::whereStatus(1)->find($item->discountable_id);
                        if ($findVariation) {
                            $updateVariation = $findVariation->update(
                                [
                                    'discountPrice' => null,
                                    'discountActive' => null,
                                ]
                            );
                        }

                    }
                    if ($discount->discountable_type == $productNumber && !empty($count_buy)) {
                        $item->delete();
                    }

                    if ($discount->discountable_type == $brandNumber) {
//                        $findVariation = DiscountServices::updateRelationCategoryOrBrandWithVariation(Brand::class,
//                            $user_id, $item, 1);
                        $updateVariation = event(new E_updateSetNullForVariationInDiscount(Brand::class, $user_id,
                            $item, 1));
                    }

                    if ($discount->discountable_type == $categoryNumber) {
//                        $findVariation = DiscountServices::updateRelationCategoryOrBrandWithVariation(Categoryproduct::class, $user_id, $item, 1);
                        $updateVariation = event(new E_updateSetNullForVariationInDiscount(Category::class, $user_id,
                            $item, 1));
                    }

                    if ($discount->discountable_type != $userNumber && $discount->discountable_type != $roleNumber) {
                        DB::beginTransaction();
                        if ($updateVariation) {
                            DB::commit();
                            $item->delete();
                        } else {
                            DB::rollBack();
                        }
                    } else {
                        $item->delete();
                    }

                }
                if ($type != $typeOld) {
                    if ($typeOld == $typeCode) {
                        $data = $discount->discountCode()->delete();
                    } elseif ($typeOld == $typeCodeTime) {
                        $data = $discount->discountCode()->get();
                        $data[0]->discountCodeTime[0]->delete();
                        $data = $discount->discountCode()->delete();
                    } elseif ($typeOld == $typeTime) {
                        $data = $discount->discountTime()->delete();

                    } elseif ($typeOld == $coupon) {
                        $data = $discount->coupon()->delete();
                    } elseif ($typeOld == $amazing) {
                        $data = $discount->discountTime()->delete();
                    } else {
                        // Discount is Simple OR Count Buy
                        // delete on Discountable Table
                    }
                    //insert New Data... on Base Type request And Insert Into  Discountable Table
                    $this->CreateDiscountBaseOnType($discountableId,
                        $discountableType, $categoryNumber,
                        $brandNumber, $productNumber, $userNumber,
                        $roleNumber, $discount, $type, $typeCode,
                        $typeCodeTime, $typeTime, $coupon, $amazing,
                        $code, $expireDate, $baseon, $cent, $count_buy, $count_user, $user_id);

                    if ($discount->update($updateData)) {
                        toast()->success(Message::successMessageEdit, 'موفقیت آمیز');
                        return redirect(route('panel.discount.index'));
                    } else {
                        toast()->error(Message::illegalError, 'خطا');
                        return back();
                    }

                } else {

                    foreach ($discountableId as $item) {

                        //Start Of check And Get Class Model
                        if ($discountableType == $categoryNumber) {

                            $results = DiscountServices::findVariationCategoryOrBrand(Category::class,
                                $user_id, $item, $discountableType, $baseon, $cent, 1);
                            $count = 0;
                            $messageCategory = false;
                            if ($results['apply']) {
                                foreach ($results['apply'] as $itemApply) {
                                    if ($itemApply == false) {
                                        $count++;
                                    }
                                }
                            }
                            $find = $results['find'];

                        } elseif ($discountableType == $brandNumber) {

                            $results = DiscountServices::findVariationCategoryOrBrand(Brand::class, $user_id, $item, $discountableType, $baseon, $cent, 1);
                            $count = 0;
                            $messageCategory = false;
                            if ($results['apply']) {
                                foreach ($results['apply'] as $itemApply) {
                                    if ($itemApply == false) {
                                        $count++;
                                    }
                                }
                            }
                            $find = $results['find'];

                        } elseif ($discountableType == $productNumber && empty($count_buy)) {

                            $messageCategory = false;
                            $find = \App\Model\Variation::with('discount')->findOrfail($item);

                            //todo event And listener...
//                            $updateDiscountPriceForVariation = DiscountServices::update_discountPriceVariation($find, $baseon, $cent, $discountableType);
//                            if ($updateDiscountPriceForVariation == false) {
//                                toast()->error('مقدار تخفیف بشتر از قیمت محصول می باشد', Lang::get('cms.error'));
//                                return back();
//                            }
                            if (($count_user > 0 || is_null($count_user)) && $startDateData <= Carbon::now()->timestamp) {
                                $updateDiscountPriceForVariation = DiscountServices::update_discountPriceVariation($find, $baseon, $cent, $discountableType);
                                if ($updateDiscountPriceForVariation == false) {
                                    toast()->error('مقدار تخفیف بشتر از قیمت محصول می باشد', Lang::get('cms.error'));
                                    return back();
                                }
                            }

                        } elseif ($discountableType == $productNumber && !empty($count_buy)) {
                            $find = \App\Model\Variation::with('discount')->findOrfail($item);
                        } elseif ($discountableType == $userNumber) {
                            $find = User::findOrfail($item);
                        } elseif ($discountableType == $roleNumber) {
                            $find = Role::findOrfail($item);
                        } else {
                            /*toast()->error(Message::errorMessageCreate, 'خطا در اعمال');
                            return back();*/
                        }
                        //End Of check And Get Class Model
                        $createDiscountAble = DiscountServices::create_discountable($find, $discount);
                        if (!$createDiscountAble instanceof Discountable) {
                            toast()->error(Message::errorMessageCreate, 'خطا در اعمال');
                            return back();
                        }

                    }

                    if (isset($results) && ($count == count($results['apply']))) {

                        $createDiscountAble->delete();
                        $messageCategory = true;
                    }

                    if ($find == false || (isset($messageCategory) && $messageCategory == true)) {
                        toast()->error('تخفیف شما اعمال نشد', Lang::get('cms.error'));
                        return redirect()->route('panel.discount.index');
                    }


                    $updateOtherTableDiscount = $this->UpdateDiscountAssociatedTable($type, $typeCode, $typeSimple, $discount, $typeCodeTime, $typeTime, $coupon, $amazing, $code, $expireDate, $countBuy, $startDateData);
                    if ($updateOtherTableDiscount) {
                        if ($discount->update($updateData)) {
                            toast()->success(Message::successMessageEdit, 'موفقیت آمیز');
                            return redirect(route('panel.discount.index'));
                        } else {
                            toast()->error(Message::illegalError, 'خطا');
                            return back();
                        }
                    } else {
                        toast()->error(Message::illegalError, 'خطا');
                        return back();
                    }

                }
            }


        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    //Delete Discount
    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Discount::with(['disable'])->findOrFail($id);
            foreach ($find->disable as $itemDiscountable) {
                if ($find->discountable_type == DiscountType::product) {
                    if (isset($itemDiscountable->discountable) && !empty($itemDiscountable->discountable)) {
                        /* variation discountPrice is update => set null */
                        $updateVariation = $itemDiscountable->discountable->update(
                            [
                                'discountPrice' => null,
                                'discountActive' => null
                            ]
                        );
                        if (!$updateVariation) {
                            toast()->error(Message::errorMessageDelete, Lang::get('cms.error'));
                            return back();
                        }
                    }
                } elseif ($find->discountable_type == DiscountType::brand || $find->discountable_type == DiscountType::category) {
                    $discountActivity = 2;
                    $model = Brand::class;
                    $discountActivity = DiscountType::brand;
                    if ($find->discountable_type == DiscountType::category) {
                        $model = Category::class;
                        $discountActivity = DiscountType::category;
                    }
                    $findVariation = $model::with(['discount'])->findOrfail($itemDiscountable->discountable_id);

                    /* update variation discount_price and discount_active to set null */
                    $resultUpdate = event(new E_deleteSetNullForVariationInDiscount($findVariation, $discountActivity));
                    if ($resultUpdate == false) {
                        toast()->error(Message::errorMessageDelete, Lang::get('cms.error'));
                        return back();
                    }
//                    foreach ($findVariation->products as $itemProduct) {
//                        if ($itemProduct instanceof Product) {
//                            foreach ($itemProduct->variations as $itemVariation) {
//                                if (!empty($itemVariation->discountActive) && !is_null($itemVariation->discountActive) && ($itemVariation->discountActive == $discountActivity)) {
//                                    $itemVariation->update(
//                                        [
//                                            'discountPrice' => null,
//                                            'discountActive' => null
//                                        ]
//                                    );
//                                    if (!$itemVariation) {
//                                        // todo transaction
//                                        toast()->error(Message::errorMessageDelete, Lang::get('cms.error'));
//                                        return back();
//                                    }
//                                }
//                            }
//                        }
//                    }
                }
            }

            $deleteDiscount = $find->delete();
            if ($deleteDiscount) {
                toast()->success(Message::successMessageDelete, Lang::get('cms.success'));
                return back();
            } else {
                toast()->error(Message::errorMessageDelete, Lang::get('error'));
                return back();
            }

        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    //change status Discount
    public function status($id)
    {
        if (is_numeric($id)) {
            $find = Discount::findOrFail($id);

            $productNumber = \App\Utility\DiscountType::product;
            if ($find->status == 0) {
                return redirect()->route('panel.discount.edit', $find->id);
                $data = [
                    'status' => 1
                ];
            } elseif ($find->status == 1) {
                $data = [
                    'status' => 0
                ];
                foreach ($find->disable()->get() as $item) {
                    if ($find->discountable_type == $productNumber) {
                        $findVariation = Variation::whereStatus(1)->find($item->discountable_id);
                        if ($findVariation) {
                            $updateVariation = $findVariation->update(
                                [
                                    'discountPrice' => null,
                                    'discountActive' => null,
                                ]
                            );
                        }
                    }
                }
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

    //change On discount type,include : Category,Brand,Product,user,role
    public function GetCategory(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        $category = \App\Utility\DiscountType::category;
        $brand = \App\Utility\DiscountType::brand;
        $product = \App\Utility\DiscountType::product;
        $user = \App\Utility\DiscountType::user;
        $role = \App\Utility\DiscountType::role;
        $amazing = \App\Utility\DiscountType::amazing;
        $sellerId = $request->input('sellerId');

        if (isset($id) && is_numeric($id)) {

            $discount = Discount::findOrfail($id);
            $discountable = $discount->disable;
            $data = array();
            foreach ($discountable as $discount) {
                if (isset($discount->discountable) && !empty($discount->discountable)) {
                    array_push($data, $discount->discountable->id);

                }
            }

            if ($type == $category) {
                $AllCategory = DiscountType::getCategoryOrBrandBaseOnUser($type, $sellerId);
                $view = view('panel.discount.ajax.category', compact('AllCategory', 'data'))->render();
            } elseif ($type == $brand) {
                $AllBrand = DiscountType::getCategoryOrBrandBaseOnUser($type, $sellerId);
                $view = view('panel.discount.ajax.brand', compact('AllBrand', 'data'))->render();

            } elseif ($type == $product) {
                $AllProduct = \App\Utility\Variation::findVariationBaseOnUserGroup($sellerId);
                $view = view('panel.discount.ajax.product', compact('data', 'AllProduct'))->render();
            } elseif ($type == $user) {
                $AllUser = $this->AllUser;
                $view = view('panel.discount.ajax.users', compact('data', 'AllUser'))->render();
            } elseif ($type == $role) {
                $AllRole = $this->AllRole;
                $view = view('panel.discount.ajax.roles', compact('data', 'AllRole'))->render();
            } else {
                return Lang::get('cms.alert');
            }
        } else {
            /* validation user */
            User::whereActive(1)->findOrFail($sellerId);
            if ($type == $category) {
                $AllCategory = DiscountType::getCategoryOrBrandBaseOnUser($type, $sellerId);
                $view = view('panel.discount.ajax.category', compact('AllCategory'))->render();
            } elseif ($type == $brand) {
                $AllBrand = DiscountType::getCategoryOrBrandBaseOnUser($type, $sellerId);
                $view = view('panel.discount.ajax.brand', compact('AllBrand'))->render();

            } elseif ($type == $product) {

                $AllProduct = \App\Utility\Variation::findVariationBaseOnUserGroup($sellerId);
                $view = view('panel.discount.ajax.product', compact('AllProduct'))->render();
            } elseif ($type == $user) {
                $AllUser = $this->AllUser;
                $view = view('panel.discount.ajax.users', compact('AllUser'))->render();
            } elseif ($type == $role) {
                $AllRole = $this->AllRole;
                $view = view('panel.discount.ajax.roles', compact('AllRole'))->render();
            } else {
                return Lang::get('cms.alert');
            }
        }
        return response()->json(['html' => $view]);
    }

    //change discount Type
    public function GetChangeType(Request $request)
    {
        $type = $request->input('type');
        $sellerId = $request->input('sellerId');
        $simple = \App\Utility\DiscountType::discountSimple;
        $code = \App\Utility\DiscountType::discountCode;
        $codeTime = \App\Utility\DiscountType::discountCodeTime;
        $time = \App\Utility\DiscountType::discountTime;
        $coupon = \App\Utility\DiscountType::coupon;
        $amazing = \App\Utility\DiscountType::amazing;
        $countBuy = \App\Utility\DiscountType::COUNTBUY;


        $id = $request->input('id');
        if (isset($id) && is_numeric($id)) {
            $discount = Discount::findOrfail($id);

            $discountable = $discount->disable;
            $typeOn = $discountable[0]->discountable_type;

            if ($type == $code) {

                $data = $discount->discountCode[0]->code;
                $view = view('panel.discount.ajax.discount-type.code', compact('data', 'typeOn'))->render();
            } elseif ($type == $simple) {

                $view = view('panel.discount.ajax.discount-type.simple', compact('typeOn'))->render();
            } elseif ($type == $codeTime) {
                $code = $discount->discountCode[0]->code;
                $time = $discount->discountCode[0]->discountCodeTime[0]->expire_date;
                $view = view('panel.discount.ajax.discount-type.code-time', compact('code', 'time', 'typeOn'))->render();
            } elseif ($type == $time) {
                $data = $discount->discountTime[0]->expire_date;
                $view = view('panel.discount.ajax.discount-type.time', compact('data', 'typeOn'))->render();
            } elseif ($type == $coupon) {
                $code = $discount->coupon[0]->code;
                $time = $discount->coupon[0]->expire_date;
                $coupon = 1;
                $view = view('panel.discount.ajax.discount-type.coupon', compact('code', 'time', 'typeOn', 'coupon'))->render();
            } elseif ($type == $amazing) {
                $data = $discount->discountTime[0]->expire_date;
                $startDate = $discount->discountTime[0]->start_date;
                $view = view('panel.discount.ajax.discount-type.amazing', compact('data', 'typeOn', 'startDate'))->render();
            } elseif ($type == $countBuy) {
                $view = view('panel.discount.ajax.discount-type.count-buy', compact('typeOn', 'discount'))->render();
            } else {
                $view = view('panel.discount.ajax.discount-type.index')->render();
            }
        } else {
            if ($type == $code) {
                $view = view('panel.discount.ajax.discount-type.code')->render();
            } elseif ($type == $simple) {
                $view = view('panel.discount.ajax.discount-type.simple')->render();
            } elseif ($type == $codeTime) {
                $view = view('panel.discount.ajax.discount-type.code-time')->render();
            } elseif ($type == $time) {
                $view = view('panel.discount.ajax.discount-type.time')->render();
            } elseif ($type == $coupon) {
                $coupon = 1;
                $view = view('panel.discount.ajax.discount-type.coupon', compact('coupon'))->render();
            } elseif ($type == $amazing) {
                $view = view('panel.discount.ajax.discount-type.amazing')->render();
            } elseif ($type == $countBuy) {
                $view = view('panel.discount.ajax.discount-type.count-buy')->render();
            } else {
                $view = view('panel.discount.ajax.discount-type.index')->render();
            }
        }

        return response()->json(['html' => $view]);

    }

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
        return Verta::createTimestamp($date)->format('Y/m/j H:i:s');
    }

    // get timestamp and convert to Miladi
    public static function TimestampToMiladi($unix_time_stamp)
    {
        $converted = Carbon::createFromTimestamp($unix_time_stamp, 'Asia/Tehran')
            ->toDateTimeString();
        return $converted;
    }

    public static function TimestampToMiladiSoon($unix_time_stamp)
    {
        $converted = Carbon::createFromTimestamp($unix_time_stamp, 'Asia/Tehran')
            ->toDateTimeString();

        return str_replace(" ","T",$converted);
    }



    // Creating values in the tables associated with the discount table, based on their type
    public function CreateDiscountBaseOnType($discountableId, $discountableType,
                                             $categoryNumber, $brandNumber, $productNumber,
                                             $userNumber, $roleNumber, $createDiscount,
                                             $type, $typeCode, $typeCodeTime, $typeTime,
                                             $coupon, $amazing, $code, $expireDate,
                                             $baseon = null, $cent = null, $count_buy, $count_user, $user_id)
    {
        foreach ($discountableId as $item) {
            //Start Of check And Get Class Model
            if ($discountableType == $categoryNumber) {

//                $results = DiscountServices::findVariationCategoryOrBrand(Categoryproduct::class,
//                    $user_id, $item, $discountableType, $baseon, $cent);
                $results = event(new E_findVariationInDiscount(Category::class, $user_id, $item,
                    $discountableType, $baseon, $cent));


                $count = 0;
                $messageCategory = false;
                if ($results[0]['apply']) {
                    foreach ($results[0]['apply'] as $itemApply) {
                        if ($itemApply == false) {
                            $count++;
                        }
                    }
                }
                $find = $results[0]['find'];

            } elseif ($discountableType == $brandNumber) {

//                $results = DiscountServices::findVariationCategoryOrBrand(Brand::class, $user_id, $item, $discountableType, $baseon, $cent);
                $results = event(new E_findVariationInDiscount(Category::class, $user_id, $item,
                    $discountableType, $baseon, $cent));

                $count = 0;
                $messageCategory = false;
                if ($results[0]['apply']) {
                    foreach ($results[0]['apply'] as $itemApply) {
                        if ($itemApply == false) {
                            $count++;
                        }
                    }
                }

                $find = $results[0]['find'];

            } elseif ($discountableType == $productNumber && empty($count_buy)) {

                $messageCategory = false;
                $find = \App\Model\Variation::with('discount')->findOrfail($item);
                //todo event And listener...
                $updateDiscountPriceForVariation = DiscountServices::update_discountPriceVariation($find, $baseon, $cent, $discountableType);
                if ($updateDiscountPriceForVariation == false) {
                    toast()->error('مقدار تخفیف بشتر از قیمت محصول می باشد', Lang::get('cms.error'));
                    return back();
                }


                if ($count_user > 0 || is_null($count_user)) {
                    $updateDiscountPriceForVariation = DiscountServices::update_discountPriceVariation($find, $baseon, $cent, $discountableType);
                }
            } elseif ($discountableType == $productNumber && !empty($count_buy)) {
                $find = \App\Model\Variation::with('discount')->findOrfail($item);
            } elseif ($discountableType == $userNumber) {
                $find = User::findOrfail($item);
            } elseif ($discountableType == $roleNumber) {
                $find = Role::findOrfail($item);
            } else {
//                toast()->error(Message::errorMessageCreate, 'خطا در اعمال');
//                return back();
            }
            //End Of check And Get Class Model

            $createDiscountAble = DiscountServices::create_discountable($find, $createDiscount);

        }


        if (isset($results) && ($count == count($results[0]['apply']))) {
            $createDiscountAble->delete();
            $messageCategory = true;
        }

        if (isset($find) && $find == false || isset($messageCategory) && $messageCategory == true) {
            toast()->error('تخفیف شما اعمال نشد', Lang::get('cms.error'));
            return redirect()->route('panel.discount.index');
        }


        if ($type == $typeCode && isset($code) && $code != "") {
            $createDiscount->discountCode()->create(['code' => $code]);
        } elseif ($type == $typeCodeTime && isset($expireDate, $code) && !empty([$code, $expireDate])) {
            $discountCode = $createDiscount->discountCode()->create(['code' => $code]);
            $date = $this->convertToMiladi($expireDate);
            $discountCode->discountCodeTime()->create(['expire_date' => $date]);
        } elseif ($type == $typeTime && isset($expireDate) && !empty($expireDate)) {
            $date = $this->convertToMiladi($expireDate);
            $createDiscount->discountTime()->create(['expire_date' => $date]);
        } elseif ($type == $coupon && isset($expireDate, $code) && !empty([$code, $expireDate])) {
            $date = $this->convertToMiladi($expireDate);
            $createDiscount->coupon()->create(['expire_date' => $date, 'code' => $code]);
        } elseif ($type == $amazing && isset($expireDate) && !empty($expireDate)) {
            $date = $this->convertToMiladi($expireDate);
            $createDiscount->discountTime()->create(['expire_date' => $date]);
        } else {
            toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
            return redirect()->route('panel.discount.index');
        }
    }

    //To update the table values associated with the discount table, based on the type of discount
    public function UpdateDiscountAssociatedTable($type, $typeCode, $typeSimple, $createDiscount, $typeCodeTime, $typeTime, $coupon, $amazing, $code, $expireDate, $countBuy, $startDate = null)
    {


        $update = false;
        if ($type == $typeCode && isset($code) && $code != "") {
            $update = $createDiscount->discountCode()->update(['code' => $code]);
        } elseif ($type == $typeCodeTime && isset($expireDate, $code) && !empty([$code, $expireDate])) {
            $discountCode = $createDiscount->discountCode()->update(['code' => $code]);
            $date = $this->convertToMiladi($expireDate);
            $update = $discountCode->discountCodeTime()->update(['expire_date' => $date]);
        } elseif ($type == $typeTime && isset($expireDate) && !empty($expireDate)) {
            // dd($createDiscount->discountTime[0]);
            $date = $this->convertToMiladi($expireDate);
            $update = $createDiscount->discountTime()->update(
                ['expire_date' => $date]
            );
        } elseif ($type == $coupon && isset($expireDate, $code) && !empty([$code, $expireDate])) {
            if (!empty($expireDate) && !empty($code)) {
                $date = $this->convertToMiladi($expireDate);
                $update = $createDiscount->coupon()->update(['expire_date' => $date, 'code' => $code]);
            }

        } elseif ($type == $amazing && isset($expireDate) && !empty($expireDate)) {
            $date = $this->convertToMiladi($expireDate);
            $update = $createDiscount->discountTime()->update(['expire_date' => $date, 'start_date' => $startDate]);
        } elseif ($type == $typeSimple) {
            $update = true;
        } elseif ($type == $countBuy) {
            return true;
        } else {
            return false;
        }

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    /* vaziate takhfif  */
        public static function statusApplyDiscount($discount = null, $key = null, $itemDiscount)
    {

        $user_id = $itemDiscount->user_id;
        $resultApplyDiscount = [];
        if ($itemDiscount->discountable_type == DiscountType::category) {
            $disCountAbleAll = $itemDiscount->disable;
            $resultApplyDiscount = self::resultCountVariationApplyDiscount($user_id, $disCountAbleAll, DiscountType::category);

        } elseif ($itemDiscount->discountable_type == DiscountType::brand) {
            $disCountAbleAll = $itemDiscount->disable;
            $resultApplyDiscount = self::resultCountVariationApplyDiscount($user_id, $disCountAbleAll, DiscountType::brand);

        } elseif ($itemDiscount->discountable_type == DiscountType::product) {
            $discountable = $itemDiscount->disable;
            //dd($discountable[0]->discountable);
            foreach ($discountable as $itemDiscountAble) {
                if (isset($itemDiscountAble->discountable->user_id)) {
//                    if ($itemDiscountAble->discountable->user_id == $user_id) {

                        if ($itemDiscountAble->discountable->discountActive == DiscountType::product && $itemDiscount->status == Status::active) {
                            $resultApplyDiscount [] = $itemDiscountAble->discountable->id;
                        }
//                    }
                }
            }

        }

        return $resultApplyDiscount;

    }


    /* result vaziate takhfif */
    private static function resultCountVariationApplyDiscount($user_id, $disCountAbleAll, $type)
    {
        $resultApplyDiscount = [];
        foreach ($disCountAbleAll as $itemDiscountCategory) {
            foreach ($itemDiscountCategory->discountable->products as $itemProduct) {
                foreach ($itemProduct->variations as $itemVariation) {
                    if ($itemVariation->user_id == $user_id) {
                        if ($itemVariation->discountActive == $type) {
                            $resultApplyDiscount [] = $itemVariation->id;
                        }
                    }
                }
            }
        }
        return $resultApplyDiscount;
    }

    /* agar discount tedadi bud discounty emal nashavad va ya bar aks */
    public static function HasDiscountCountBuy($variation_id, $discountType)
    {
        $findVariation = Variation::findOrFail($variation_id);

        /* discount on product */
        $findDiscount = $findVariation->discount;

        /* discount price => category or brand or ... */


        if ($discountType == DiscountType::COUNTBUY) {
//            $discountPriceVariation = $findVariation->discountPrice;
//            if (empty($discountPriceVariation)) {
//                return true;
//            }
            $updateVariation = $findVariation->update([
                'discountPrice' => null,
                'discountActive' => null
            ]);
        }

        if (isset($findDiscount, $findDiscount[0], $findDiscount[0]->discount) && !empty($findDiscount)) {
            $discountType = $findDiscount[0]->discount->type;
            if ($discountType == DiscountType::COUNTBUY) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

//        if (empty($discountPriceVariation)) {
//
//        } else {
//            return true;
//        }
    }

}
