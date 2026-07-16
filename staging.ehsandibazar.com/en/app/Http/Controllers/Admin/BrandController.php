<?php

namespace App\Http\Controllers\Admin;

use App\Model\Brand;
use App\Services\ImageServices\ImageServices;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use SEO;


class BrandController extends Controller
{
    protected $user;
    public const countOfRender = 20;
    protected $allBrand;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
        $this->allBrand = Brand::with('image')->orderBy('sorting', 'ASC')->paginate(self::countOfRender);
    }

    public function index()
    {
        $title = Lang::get('cms.brand');
        SEO::setTitle($title);
        $brands = $this->allBrand;
        return view('panel.brand.index', compact('title', 'brands'));
    }

    public function create()
    {
        $title = Lang::get('cms.create-brand');
        SEO::setTitle($title);
        return view('panel.brand.create', compact('title'));
    }

    public function store(Request $request)
    {
        $title = $request->input('title');
        $latinTitle = $request->input('latin_title');
        $description = $request->input('description');
        $status = $request->input('status');
        $sort = $request->input('sort');
        $images = $request->input('filepath');
        $top = $request->input('top');
        $new = $request->input('new');
        $special = $request->input('special');

        $this->validate($request, [
            'title' => "required",
            'slug' => "required",
            'latin_title' => "required",
            'status' => 'required|integer|between:0,1',
        ]);

        $requestData = [
            'title' => $title,
            'slug' => $request->input('slug'),
            'latin_title' => $latinTitle,
            'description' => $description,
            'status' => $status,
            'top' => isset($top) && !empty($top) ? true : false,
            'new' => isset($new) && !empty($new) ? true : false,
            'special' => isset($special) && !empty($special) ? true : false,
            'user_id' => $this->user->id,
            'sort' => $sort
        ];

        $saveData = Brand::create($requestData);
        if ($saveData instanceof Brand) {

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

            toast()->success(Lang::get('cms.success'),Message::successMessageCreate);
            return redirect()->route('panel.brand.index');

        } else {
            toast()->error(Message::errorMessageCreate, Lang::get('cms.error'));
            return redirect()->route('panel.brand.index');
        }

    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = Lang::get('cms.header-brand-edit');
            SEO::setTitle($title);
            $find = Brand::owner()->findOrFail($id);
            return view('panel.brand.create', compact('title', 'find'));
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $title = $request->input('title');
            $latinTitle = $request->input('latin_title');
            $description = $request->input('description');
            $status = $request->input('status');
            $sort = $request->input('sort');
            $images = $request->input('filepath');
            $top = $request->input('top');
            $new = $request->input('new');
            $special = $request->input('special');

            $this->validate($request, [
                'title' => "required",
                'slug' => "required",
                'latin_title' => "required",
                'status' => 'required|integer|between:0,1',
            ]);

            $requestData = [
                'title' => $title,
                'slug' => $request->input('slug'),
                'latin_title' => $latinTitle,
                'description' => $description,
                'status' => $status,
                'top' => isset($top) && !empty($top) ? true : false,
                'new' => isset($new) && !empty($new) ? true : false,
                'special' => isset($special) && !empty($special) ? true : false,
                'user_id' => $this->user->id,
                'sort' => $sort,
            ];

            $findId = Brand::findOrFail($id);
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
                AdminController::createSeo($request,$findId);

                toast()->success(Message::successMessageEdit, Lang::get('cmd.success'));
                return redirect()->route('panel.brand.index');

            } else {
                toast()->error(Message::errorMessageEdit, Lang::get('cmd.error'));
                return redirect()->route('panel.brand.index');
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Brand::owner()->findOrFail($id);
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

    public function status($id)
    {
        if (is_numeric($id)) {
            $find = Brand::owner()->findOrFail($id);
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
                toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return back();
            } else {
                toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return back();
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function saveNested(Request $request)
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
                $brand = Brand::find($v['category_id']);
                $brand->update([
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
            toast()->success(Lang::get('cms.success'),"همه برند ها به روز شدن")->showConfirmButton('بستن');
        } else {
            toast()->error("هنگام بروزرسانی مشکلی پیش آمد ...", Lang::get('cms.error'))->showConfirmButton('بستن');
        }
        return redirect()->route('panel.brand.index');

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

    // =====================  extra function ======================
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
}
