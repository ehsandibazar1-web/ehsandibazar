<?php

namespace App\Http\Controllers\Admin;

use App\Model\AttributeType;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class AttributeTypeController extends Controller
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
        $title = Lang::get('cms.multi-attribute');
        $attributeType = AttributeType::latest()->paginate(self::countOfRender);
        return view('panel.attribute-type.index', compact('attributeType','title'));
    }

    public function create()
    {
        $title = Lang::get('cms.multi-attribute-create');
        return view('panel.attribute-type.create',compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => "required",
            'status' => 'required|integer|between:0,1',
        ]);
        $title = $request->input('name');
        $status = $request->input('status');
        /*$label = $request->input('label');*/
       /* $lang = $request->input('lang');*/

        $requestData = [
            'name' => $title,
           /* 'label' => $label,*/
           /* 'lang' => $lang,*/
            'status' => $status,
            'user_id' => $this->user->id
        ];

        $saveData = AttributeType::create($requestData);

        if ($saveData instanceof AttributeType) {
            toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
            return redirect()->route('panel.attribute-type.index');
        } else {
            toast()->error(Message::errorMessageCreate, 'خطا');
            return back();
        }

    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = Lang::get('cms.multi-attribute-edit');
            $findAttributeType = AttributeType::owner()->findOrFail($id);
            return view('panel.attribute-type.create', compact('findAttributeType','title'));
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
            $status = $request->input('status');
            /*$label = $request->input('label');*/

            $requestData = [
                'name' => $title,
               /* 'label' => $label,*/
                'status' => $status,
                'user_id' => $this->user->id
            ];

            $findId = AttributeType::owner()->findOrFail($id);
            $updateData = $findId->update($requestData);
            if ($updateData) {
                toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                return redirect()->route('panel.attribute-type.index');
            } else {
                toast()->error(Message::errorMessageEdit, 'خطا');
                return redirect()->route('panel.attribute-type.index');
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = AttributeType::owner()->findOrFail($id);
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
            $find = AttributeType::owner()->findOrFail($id);
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
