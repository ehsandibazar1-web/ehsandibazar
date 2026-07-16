<?php

namespace App\Http\Controllers\Admin;


use App\Model\AttributeGroup;
use App\Model\Category;
use App\Repositories\Repository;
use App\Services\ImageServices\ImageServices;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use SEO;

class CategoryController extends Controller
{
    public $category;
    public $attributeGroup;

    public function __construct(Category $category)
    {
        $this->category = new Repository($category);
        $this->attributeGroup = AttributeGroup::whereHas('attributes', function ($q) {
            $q->whereStatus(1);
        })->with('attributes')->whereStatus(1)->get();
        parent::__construct();
    }

    public function index()
    {
        $title = " لیست دسته بندی ها";
        SEO::setTitle($title);
        $categories = Category::where(['parent_id' => 0])->orderBy('sorting', 'ASC')->get();
        return view("panel.categories.index", compact('title', 'categories'));
    }

    public function create()
    {
        $title = " ایجاد دسته بندی جدید";
        SEO::setTitle($title);
        return view("panel.categories.create", compact('title'));
    }

    public function store(Request $request)
    {
        $title = $request->input('title');
        $status = $request->input('status');
        $type = $request->input('modelName');
        $parent_id = $request->input('parent_id');
        $description = $request->input('description');
        $isAttributable = $request->input('is-attribute', false);
        $images = $request->input('filepath');


        $this->validate($request, [
            'title' => 'required|max:180',
            'slug' => 'required|max:200',
            'modelName' => 'required',
            'status' => 'required|integer|between:0,1,2',
        ]);

        $requestData = [
            'title' => $title,
            'slug' => $request->input('slug'),
            'parent_id' => $parent_id,
            'description' => $description,
            'type' => $type,
            'is_attributable' => isset($isAttributable) && !empty($isAttributable) ? true : false,
            'status' => $status
        ];
        $saveData = $this->user->categories()->create($requestData);
        DB::beginTransaction();
        if ($saveData instanceof Category) {
            DB::commit();
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
            AdminController::createSeo($request,$saveData);

            toast()->success(Message::successMessageCreate, Lang::get('cms.success'))->showConfirmButton('بستن');
            return redirect()->route('panel.category.index');
        } else {
            DB::rollBack();
            toast()->error(Message::errorMessageCreate, Lang::get('cms.error'))->showConfirmButton('بستن');
            return redirect()->route('panel.category.index');
        }

    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            SEO::setTitle('مدیریت | ویرایش دسته بندی مورد نظر');
            $title = "مدیریت | ویرایش دسته بندی مورد نظر";
            $find = $this->category->show($id);
            return view('panel.categories.create', compact('title', 'find'));
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'))->showConfirmButton('بستن');
            return redirect()->route("panel.category.index");
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $findCategory = $this->category->show($id);
            $title = $request->input('title');
            $status = $request->input('status');
            $type = $request->input('modelName');
            $description = $request->input('description');
            $parentId = $request->input('parent_id');
            $isAttributable = $request->input('is-attribute', false);
            $images = $request->input('filepath');


            $this->validate($request, [
                'title' => 'required|max:180',
                'slug' => 'required|max:180',
                'modelName' => 'required',
                'status' => 'required|integer',
            ]);

            $requestData = [
                'title' => $title,
                'slug' => $request->input('slug'),
                'parent_id' => $parentId,
                'type' => $type,
                'description' => $description,
                'is_attributable' => isset($isAttributable) && !empty($isAttributable) ? true : false,
                'status' => $status
            ];

            $findCategory->fill($requestData);
            $updateData = $this->user->categories()->save($findCategory);

            DB::beginTransaction();
            if ($updateData) {
                DB::commit();
                /* start insert image */
                if ($images && !empty($images) && count($images) > 0) {
                    /* first all delete image */
                    ImageServices::delete_images($findCategory);

                    foreach ($images as $item) {
                        if ($item != null) {
                            ImageServices::arrayCreate_images($findCategory, $item, $this->user->id);
                        }
                    }
                } else {
                    ImageServices::delete_images($findCategory);
                }
                /* end insert image */
                AdminController::createSeo($request,$findCategory);

                toast()->success(Message::successMessageEdit, Lang::get('cms.success'))->showConfirmButton('بستن');
                return redirect()->route('panel.category.index');
            }

        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'))->showConfirmButton('بستن');
            return redirect()->route('panel.category.index');
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $deleteData = $this->category->delete($id);
            DB::beginTransaction();
            if ($deleteData) {
                DB::commit();
                toast()->success(Message::successMessageDelete, Lang::get('cms.success'))->showConfirmButton('بستن');
                return back();
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'))->showConfirmButton('بستن');
            return back();
        }
    }

    public function getOtherCategories(Request $request)
    {
        $type = $request->input('type');
        $find = $request->input('find');
        $categories = Category::whereStatus(1)->whereType($type)->get();

        if (isset($find) && !empty($find)) {
            $find = Category::find($find);
            $view = view('panel.categories.partials.get-other-categories', compact('categories', 'find'))->render();
        } else {
            $view = view('panel.categories.partials.get-other-categories', compact('categories'))->render();
        }
        return response()->json([
            'status' => 200,
            'html' => $view,
        ]);


    }

    public function attributedForm($id)
    {
        $title = "دسته بندی | انتخاب ویژگی دسته بندی";
        SEO::setTitle($title);
        $attributeGroups = $this->attributeGroup;
        $find = $this->category->show($id);
        return view('panel.categories.attributed', compact('find', 'title', 'attributeGroups'));
    }

    public function attributed($id, Request $request)
    {
        $attributes = $request->input('attributes', array());
        $category = $this->category->show($id);
        $category->attributes()->sync($attributes);
        toast()->success(Lang::get('cms.success'),"ویژگی های منتخب به دسته بندی متصل گردید")->showConfirmButton('بستن');
        return back();
    }

    public function saveNestedCategories(Request $request)
    {
        $json = $request->nested_category_array;
        $decoded_json = json_decode($json, TRUE);
        $simplified_list = [];
        $this->recur1($decoded_json, $simplified_list);

        DB::beginTransaction();
        try {
            $info = [
                "success" => FALSE,
            ];


            foreach ($simplified_list as $k => $v) {
                $category = Category::find($v['category_id']);
                $category->update([
                    'parent_id' => $v['parent_id'],
                    'sorting' => $v['sorting'],
                ]);
            }


            DB::commit();
            $info['success'] = TRUE;
        } catch (\Exception $e) {
            DB::rollback();
            $info['success'] = FALSE;
        }

        if ($info['success']) {
            toast()->success("همه دسته ها به روز شدن", Lang::get('cms.success'))->showConfirmButton('بستن');
        } else {
            toast()->error("هنگام بروزرسانی مشکلی پیش آمد ...", Lang::get('cms.error'))->showConfirmButton('بستن');
        }
        return redirect()->route('panel.category.index');

    }

    public function recur1($nested_array = [], &$simplified_list = [])
    {

        static $counter = 0;

        foreach ($nested_array as $k => $v) {

            $sort_order = $k + 1;
            $simplified_list[] = [
                "category_id" => $v['id'],
                "parent_id" => 0,
                "sorting" => $sort_order
            ];

            if (!empty($v["children"])) {
                $counter += 1;
                $this->recur2($v['children'], $simplified_list, $v['id']);
            }

        }
    }

    public function recur2($sub_nested_array = [], &$simplified_list = [], $parent_id = 0)
    {

        static $counter = 0;

        foreach ($sub_nested_array as $k => $v) {

            $sort_order = $k + 1;
            $simplified_list[] = [
                "category_id" => $v['id'],
                "parent_id" => $parent_id,
                "sorting" => $sort_order
            ];

            if (!empty($v["children"])) {
                $counter += 1;
                return $this->recur2($v['children'], $simplified_list, $v['id']);
            }
        }
    }
}
