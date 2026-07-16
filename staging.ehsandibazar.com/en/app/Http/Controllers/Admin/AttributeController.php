<?php

namespace App\Http\Controllers\Admin;

use App\Model\Attribute;
use App\Model\AttributeGroup;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use SEO;

class AttributeController extends Controller
{
    protected $user;
    protected $attributeAllGroup;
    public const countOfRender = 9;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
        $this->attributeAllGroup = AttributeGroup::where('status', 1)->get();
    }

    public function index()
    {
        $title = Lang::get('cms.attribute');
        SEO::setTitle($title);
        $attribute = Attribute::with('attributeGroup')->latest()->paginate(self::countOfRender);
        return view('panel.attributes.index', compact('attribute', 'title'));
    }

    public function create()
    {
        $title = Lang::get('cms.create-attr');
        SEO::setTitle($title);
        $attributeAllGroup = $this->attributeAllGroup;
        return view('panel.attributes.create', compact('attributeAllGroup', 'title'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => "required",
            'status' => 'required|integer|between:0,1',
            'attribute_group_id' => 'required|integer'
        ]);

        $title = $request->input('name');
        $status = $request->input('status');
        $label = $request->input('label');
        $attribute_group_id = $request->input('attribute_group_id');
        $filter = $request->input("filterCheck");

        $requestData = [
            'name' => $title,
            'label' => $label,
            'status' => $status,
            'user_id' => $this->user->id,
            'attribute_group_id' => $attribute_group_id ,
            'is_filter' => isset($filter) ? 1 : 0
        ];

        $saveData = Attribute::create($requestData);

        if ($saveData instanceof Attribute) {
            toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
            return redirect()->route('panel.attribute.index');
        } else {
            toast()->error(Message::errorMessageCreate, 'خطا');
            return redirect()->route('panel.attribute.index');
        }
    }

    public function edit($id)
    {
        $title = Lang::get('cms.edit-attr');
        SEO::setTitle($title);
        if (is_numeric($id)) {
            $find = Attribute::owner()->findOrFail($id);
            $attributeAllGroup = $this->attributeAllGroup;
            return view('panel.attributes.create', compact('find', 'attributeAllGroup', 'title'));
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
                'attribute_group_id' => 'required|integer'
            ]);
            $title = $request->input('name');
            $label = $request->input('label');
            $status = $request->input('status');
            $attribute_group_id = $request->input('attribute_group_id');
            $filter = $request->input("filterCheck");

            $requestData = [
                'name' => $title,
                'label' => $label,
                'status' => $status,
                'user' => $this->user->id,
                'attribute_group_id' => $attribute_group_id,
                'is_filter' => isset($filter) ? 1 : 0
            ];
            $findId = Attribute::owner()->findOrFail($id);
            $updateData = $findId->update($requestData);
            if ($updateData) {
                toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                return redirect()->route('panel.attribute.index');
            } else {
                toast()->error(Message::errorMessageEdit, 'خطا');
                return redirect()->route('panel.attribute.index');
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Attribute::owner()->findOrFail($id);
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
            $find = Attribute::owner()->findOrFail($id);
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
}
