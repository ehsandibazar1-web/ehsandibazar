<?php

namespace App\Http\Controllers\Admin;

use App\Model\Attribute;
use App\Model\AttributeGroup;
use App\Model\Category;
use App\Model\Product;
use App\Services\ImageServices\ImageServices;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class CategoryProductController extends Controller
{
    protected $user;
    public const countOfRender = 9;
    protected $allCategoryProducts;
    protected $allAttributeGroup;
    protected $arrayOfAttributes;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
        $this->allCategoryProducts = Category::whereType(Product::class)->whereStatus(1)->get();
        $this->allAttributeGroup = AttributeGroup::with('attributes')->whereStatus(1)->get();
    }

    public function index()
    {
        $title = Lang::get('cms.category-product');
        $categoryProduct = Category::query()->whereType(Product::class)->with('attributes', 'image')->latest()->get();
        return view('panel.category-products.index', compact('categoryProduct', 'title'));
    }

    public function create()
    {
        $title = Lang::get('cms.category-product-create');
        $allAttributeGroup = $this->allAttributeGroup;
        $allCategoryProducts = $this->allCategoryProducts;
        return view('panel.category-products.create', compact('allAttributeGroup', 'allCategoryProducts', 'title'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $title = $request->input('title');
        $description = $request->input('description');
        $status = $request->input('status');
        $parent_id = $request->input('parent_id');
        $sort = $request->input('sort');
        $attributes = $request->input('attributes');
        $images = $request->input('filepath');
        $showpage = $request->input('showpage');
        $showtab = $request->input('showtab');


        $this->validate($request, [
            'title' => "required",
            'parent_id' => 'required',
            'status' => 'required|integer|between:0,1',
            'sort' => 'required|integer|min:0',
            'attributes' => 'required'
        ]);


        /* validation array Image */
//        if ($message = $this->is_validFileArray($images, config('whiteList.validImage'),
//            Message::notFoundImage,
//            Message::inValidImage,
//            Message::imageNotCorrectly)) {
//            return back()->with(['error' => $message]);
//        }


        $requestData = [
            'title' => $title,
            'description' => $description,
            'status' => $status,
            'parent_id' => $parent_id,
            'user_id' => $this->user->id,
            'sort' => $sort,
            'showpage' => isset($showpage) && !empty($showpage) ? true : false,
            'showtab' => isset($showtab) && !empty($showtab) ? true : false,

        ];

        $saveData = Categoryproduct::create($requestData);
        if ($saveData instanceof Categoryproduct) {


            /* start insert image */
            if ($images && !empty($images) && count($images) > 0) {
                /* first all delete image */
                ImageServices::delete_images($saveData);

                foreach ($images as $item) {
                    if ($item != null) {
//                        if ($this->is_formatValid($item, config('whiteList.validImage'))) {
//                            if ($item != null) {
                        ImageServices::arrayCreate_images($saveData, $item, $this->user->id);
//                            }
//                        } else {
//                            return back()->with(['error' => 'لطفا تصویر مناسب را وارد نمایید']);
//                        }
                    }
                }

            } else {
                ImageServices::delete_images($saveData);
            }
            /* end insert image */


            $syncAttributeForCategory = $saveData->attributes()->sync($attributes);
            if ($syncAttributeForCategory) {
                toastr()->success(Message::successMessageCreate, 'موفقیت آمیز!');
                return redirect()->route('panel.categoryProduct.index');
            } else {
                toastr()->error(Message::errorMessageCreate, 'خطا');
                return redirect()->route('panel.categoryProduct.index');
            }

        } else {
            toastr()->error(Message::errorMessageCreate, 'خطا');
            return back();
        }

    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = Lang::get('cms.category-product-edit');
            /* get all attributes */
            $allAttributeGroup = $this->allAttributeGroup;
            /* get all categoryProducts */
            $allCategoryProducts = $this->allCategoryProducts;
            $findCategoryProductId = Categoryproduct::with(['attributes', 'image'])->findOrFail($id);
            /* array select of attributes for this category Product */
            $arrayOfAttributes = $findCategoryProductId->attributes->pluck('id')->toArray();

            return view('panel.category-products.create', compact('arrayOfAttributes', 'allAttributeGroup', 'allCategoryProducts', 'findCategoryProductId', 'title'));
        } else {
            toastr()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $title = $request->input('title');
            $description = $request->input('description');
            $status = $request->input('status');
            $parent_id = $request->input('parent_id');
            $sort = $request->input('sort');
            $attributes = $request->input('attributes');
            $images = $request->input('filepath');
            $showpage = $request->input('showpage');
            $showtab = $request->input('showtab');

            $this->validate($request, [
                'title' => "required",
                'parent_id' => 'required',
                'status' => 'required|integer|between:0,1',
                'sort' => 'required|integer|min:0',
                'attributes' => 'required'
            ]);

            $requestData = [
                'title' => $title,
                'description' => $description,
                'status' => $status,
                'parent_id' => $parent_id,
                'user_id' => $this->user->id,
                'sort' => $sort,
                'showpage' => isset($showpage) && !empty($showpage) ? true : false,
                'showtab' => isset($showtab) && !empty($showtab) ? true : false,

            ];

            $findId = Categoryproduct::findOrFail($id);
            $updateData = $findId->update($requestData);
            if ($updateData) {

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


                $syncAttributeForCategory = $findId->attributes()->sync($attributes);
                if ($syncAttributeForCategory) {
                    toastr()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                    return redirect()->route('panel.categoryProduct.index');
                } else {
                    toastr()->error(Message::errorMessageEdit, 'خطا');
                    return redirect()->route('panel.categoryProduct.index');
                }

            } else {
                toastr()->error(Message::errorMessageEdit, 'خطا');
                return redirect()->route('panel.categoryProduct.index');
            }
        } else {
            toastr()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Categoryproduct::findOrFail($id);
//            $find->subCategory()->get()->each->delete();
            $deleteData = $find->delete();
            if ($deleteData) {
                toastr()->success(Message::successMessageDelete, 'موفقیت آمیز!');
                return back();
            } else {
                toastr()->error(Message::errorMessageDelete, 'خطا');
                return back();
            }
        } else {
            toastr()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function status($id)
    {
        if (is_numeric($id)) {
            $find = Categoryproduct::findOrFail($id);
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
                toastr()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                return back();
            } else {
                toastr()->error(Message::errorMessageEdit, 'خطا');
                return back();
            }
        } else {
            toastr()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function isSearchFilter(Request $request)
    {
        $filterAble = $request->input('is_filterable');
        $searchAble = $request->input('is_searchable');
        $category_id = $request->input('category_id');

        /* validation Filterable */
        $this->validFiltrable($filterAble, $searchAble);
        if (is_numeric($category_id)) {
            $findCategory = Categoryproduct::with('attributes')->whereStatus(1)->findOrFail($category_id);

            foreach ($findCategory->attributes as $itemCategory) {
                //dd($itemCategory->attributes);

                if (in_array($itemCategory->pivot->attribute_id, (array)$filterAble)) {
                    $findCategory->attributes()->where('attribute_id', $itemCategory->pivot->attribute_id)->update([
                        'is_filterable' => 1
                    ]);
                } else {
                    $findCategory->attributes()->where('attribute_id', $itemCategory->pivot->attribute_id)->update([
                        'is_filterable' => 0
                    ]);
                }

                if (in_array($itemCategory->pivot->attribute_id, (array)$searchAble)) {
                    $findCategory->attributes()->where('attribute_id', $itemCategory->pivot->attribute_id)->update([
                        'is_searchable' => 1
                    ]);
                } else {
                    $findCategory->attributes()->where('attribute_id', $itemCategory->pivot->attribute_id)->update([
                        'is_searchable' => 0
                    ]);
                }
            }

            toastr()->success(Message::successMessageEdit, 'success!');
            return back();
            /*foreach ($findCategory->attributes as $key => $itemCategory){
               $update=$findCategory->attributes()->updateExistingPivot($category_id,$searchAble);
            }*/

        } else {
            return back()->with(['error' => Message::illegalError]);
        }
    }
    //================================= extra function===================================

    /* valid filterable and search able */
    private function validFiltrable($filterAble, $searchAble)
    {
        if (isset($filterAble) && !empty($filterAble)) {
            foreach ($filterAble as $itemFilter) {
                $findAttributeTypeValue = Attribute::whereStatus(1)->findOrFail($itemFilter);
            }
        }

        if (isset($searchAble) && !empty($searchAble)) {
            foreach ($searchAble as $itemSearch) {
                $findAttributeTypeValue = Attribute::whereStatus(1)->findOrFail($itemSearch);
            }
        }
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

//                        if (!file_exists(trim(base_path() . $file))) {
//
//                            return false;
//                        }

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
//                            if (!file_exists(trim(base_path() . $item))) {
//                                return $notFountMessage;
//                            }
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
}
