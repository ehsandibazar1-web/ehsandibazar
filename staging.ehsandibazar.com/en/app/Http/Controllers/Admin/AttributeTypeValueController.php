<?php

namespace App\Http\Controllers\Admin;

use App\Model\AttributeType;
use App\Model\AttributeTypeValue;
use App\Utility\Message;
use App\Utility\ProductType;
use App\Utility\Variation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class AttributeTypeValueController extends Controller
{

    protected $user;
    public const countOfRender = 9;
    protected $allAttributeType;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
        $this->allAttributeType = AttributeType::whereStatus(1)->get();
    }

    public function index()
    {
        $attributeTypeValue = AttributeTypeValue::latest()->paginate(self::countOfRender);
        return view('panel.attribute-type-value.index', compact('attributeTypeValue'));
    }

    public function create()
    {
        $allAttributeType = $this->allAttributeType;
        return view('panel.attribute-type-value.create', compact('allAttributeType'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'lang' => "required",
            'value' => "required",
            'attribute_type_id' => 'required',
            'status' => 'required|integer|between:0,1',
        ]);

        $value = $request->input('value');
        $status = $request->input('status');
        $attribute_type_id = $request->input('attribute_type_id');
        /*$label = $request->input('label');*/
        $lang = $request->input('lang');
        $color = $request->input('color');

        /* validation color */
        if ($attribute_type_id == Variation::COLOR) {
            $isValid = $this->isValidColor($color);
            if ($isValid == false) {
                return back()->with(['error' => 'لطفا رنگ خود را به درستی انتخاب نمایید.']);
            }
        }

        /* validation lang */
        if (isset($lang)) {
            if (!in_array($lang, config('whiteList.lang'))) {
                return back()->with(['error' => Lang::get('cms.alert-choose-lang')]);
            }
        }


        /* validation */
        AttributeType::findOrFail($attribute_type_id);

        $requestData = [
            'value' => $value,
            /*'label' => $label,*/
            'lang' => $lang,
            'status' => $status,
            'user_id' => $this->user->id,
            'attribute_type_id' => $attribute_type_id
        ];

        if (isset($attribute_type_id) && $attribute_type_id == Variation::COLOR) {
            if (isset($color) && !empty($color)) {
                $requestData['color'] = $color;
            } else {
                $requestData['color'] = "";
                return back()->with(['error' => 'لطفا رنگ خود را انتخاب نمایید.']);
            }
        }else{
            $requestData['color'] = "";
        }


        $saveData = AttributeTypeValue::create($requestData);

        if ($saveData instanceof AttributeTypeValue) {
            toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
            return redirect()->route('panel.attribute-type-value.index');
        } else {
            toast()->error(Message::errorMessageCreate, 'خطا');
            return back();
        }
    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $findAttributeTypeValue = AttributeTypeValue::owner()->findOrFail($id);
            $allAttributeType = $this->allAttributeType;
            return view('panel.attribute-type-value.create', compact('findAttributeTypeValue', 'allAttributeType'));
        } else {
            toastr()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {

            $this->validate($request, [
                'value' => "required",
                'attribute_type_id' => 'required',
                'status' => 'required|integer|between:0,1',
                'lang' => 'required'
            ]);
            $value = $request->input('value');
            $status = $request->input('status');
            $color = $request->input('color');
            /*$label = $request->input('label');*/
            $lang = $request->input('lang');
            $attribute_type_id = $request->input('attribute_type_id');


            /* validation color */
            if ($attribute_type_id == Variation::COLOR) {
                $isValid = $this->isValidColor($color);
                if ($isValid == false) {
                    return back()->with(['error' => 'لطفا رنگ خود را به درستی انتخاب نمایید.']);
                }
            }

            /* validation lang */
            if (isset($lang)) {
                if (!in_array($lang, config('whiteList.lang'))) {
                    return back()->with(['error' => Lang::get('cms.alert-choose-lang')]);
                }
            }


            /* validation */
            AttributeType::findOrFail($attribute_type_id);

            $requestData = [
                'value' => $value,
                /* 'label' => $label,*/
                'lang' => $lang,
                'status' => $status,
                'user_id' => $this->user->id,
                'attribute_type_id' => $attribute_type_id
            ];

            if (isset($attribute_type_id) && $attribute_type_id == Variation::COLOR) {

                if (isset($color) && !empty($color)) {
                    $requestData['color'] = $color;
                } else {

                    $requestData['color'] = "";
                    return back()->with(['error' => 'لطفا رنگ خود را انتخاب نمایید.']);
                }
            }else{
                $requestData['color'] = "";
            }

            $findId = AttributeTypeValue::owner()->findOrFail($id);
            $updateData = $findId->update($requestData);
            if ($updateData) {
                toastr()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                return redirect()->route('panel.attribute-type-value.index');
            } else {
                toastr()->error(Message::errorMessageEdit, 'خطا');
                return redirect()->route('panel.attribute-type-value.index');
            }

        } else {
            toastr()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = AttributeTypeValue::owner()->findOrFail($id);
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
            $find = AttributeTypeValue::owner()->findOrFail($id);
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

    /* ====================================  extra function ==============================*/

    private function isValidColor($color)
    {
        if (isset($color) && !empty($color)) {
            $firstCharColor = substr($color, 0, 1);
            if ($firstCharColor == "#") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
