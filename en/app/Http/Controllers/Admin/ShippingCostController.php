<?php

namespace App\Http\Controllers\Admin;

use App\Model\ShippingCost;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class ShippingCostController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = "فهرست هزینه های ارسال";
        $shippingCosts = ShippingCost::latest()->get();
        return view('panel.shipping-cost.index', compact('shippingCosts', 'title'));
    }

    public function create()
    {
        $title = "افزودن تعرفه پست";
        return view('panel.shipping-cost.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validate = $this->validate($request, [
            '*' => "required",
        ]);

        $create = ShippingCost::create($validate);

        if ($create instanceof ShippingCost) {
            toastr()->success(Message::successMessageCreate, Lang::get('cms.success'));
            return back();
        } else {
            toastr()->error(Message::errorMessageCreate, Lang::get('cms.error'));
            return back();
        }

    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = "ویرایش هزینه پست";
            $find = ShippingCost::findOrFail($id);
            return view('panel.shipping-cost.create', compact('title', 'find'));
        } else {
            toastr()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $validate = $this->validate($request, [
                '*' => "required",
            ]);
            $find = ShippingCost::findOrFail($id);
            if ($find->count() > 0) {
                $updateData = $find->update($validate);
                if ($updateData) {
                    toastr()->success(Message::successMessageEdit, Lang::get('cms.success'));
                    return redirect()->route('panel.shippingCost.index');
                } else {
                    toastr()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                    return redirect()->route('panel.shippingCost.index');
                }
            } else {
                toastr()->error(Message::systemError, Lang::get('cms.error'));
                return redirect()->route('panel.shippingCost.index');
            }
        } else {
            toastr()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = ShippingCost::findOrFail($id);
            $deleteData = $find->delete();
            if ($deleteData) {
                toastr()->success(Message::successMessageDelete, Lang::get('cms.success'));
                return redirect()->route('panel.shippingCost.index');
            } else {
                toastr()->error(Message::errorMessageDelete, Lang::get('cms.error'));
                return redirect()->route('panel.shippingCost.index');
            }
        } else {
            toastr()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }
}
