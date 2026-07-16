<?php

namespace App\Http\Controllers\Admin;

use App\Model\AttributeGroup;
use App\Model\Category;
use App\Model\Product;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use SEO;

class AttributeGroupController extends Controller
{

    protected $user;
    public const countOfRender = 9;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        $title = Lang::get('cms.attribute-product');
        SEO::setTitle($title);
        $attributeGroups = AttributeGroup::latest()->paginate(self::countOfRender);
        $category = Category::with('attributes')->whereType(Product::class)->get();
        $getAttributeGroupId = $this->getAttributeGroupFromCategory($category);
        return view('panel.attribute-group.index', compact('category',
            'getAttributeGroupId','attributeGroups', 'title'));
    }

    public function create()
    {
        $title = Lang::get('cms.attribute-product-create');
        SEO::setTitle($title);
        return view('panel.attribute-group.create', compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => "required",
            'status' => 'required|integer|between:0,1',
        ]);

        $title = $request->input('name');
        $status = $request->input('status');
        $label = $request->input('label');

        $requestData = [
            'name' => $title,
            'label' => $label,
            'status' => $status,
            'user_id' => $this->user->id
        ];

        $saveData = AttributeGroup::create($requestData);

        if ($saveData instanceof AttributeGroup) {
            toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
            return redirect()->route('panel.attributeGroup.index');
        } else {
            toast()->error(Message::errorMessageCreate, 'خطا');
            return redirect()->route('panel.attributeGroup.index');
        }

    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = Lang::get('cms.attribute-product-edit');
            SEO::setTitle($title);
            $find = AttributeGroup::owner()->findOrFail($id);
            return view('panel.attribute-group.create', compact('find', 'title'));
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $this->validate($request, [
                'name' => "required",
                'status' => 'required|integer|between:0,1',
            ]);

            $title = $request->input('name');
            $label = $request->input('label');
            $status = $request->input('status');

            $requestData = [
                'name' => $title,
                'label' => $label,
                'status' => $status,
                'user' => $this->user->id
            ];

            $findId = AttributeGroup::owner()->findOrFail($id);
            $updateData = $findId->update($requestData);
            if ($updateData) {
                toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                return redirect()->route('panel.attributeGroup.index');
            } else {
                toast()->error(Message::errorMessageEdit, 'خطا');
                return redirect()->route('panel.attributeGroup.index');
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = AttributeGroup::owner()->findOrFail($id);
            $deleteData = $find->delete();
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
            $find = AttributeGroup::owner()->findOrFail($id);
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

    /* =============== extra function ============== */

    private function getAttributeGroupFromCategory($category)
    {
        $arrayAttributeGroupId = [];
        if (isset($category) && !empty($category)) {
            foreach ($category as $itemCategory) {
                if (isset($itemCategory->attributes) && !empty($itemCategory->attributes)) {
                    foreach ($itemCategory->attributes as $itemAttribute) {
                        if (isset($itemAttribute)) {
                            $arrayAttributeGroupId  [] =  $itemAttribute->attribute_group_id;
                        } else {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        return array_unique($arrayAttributeGroupId);
    }
}
