<?php

namespace App\Http\Controllers\Admin;

use App\Model\Permission;
use App\Model\Role;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use phpDocumentor\Reflection\Types\Parent_;
use SEO;

class RoleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        SEO::setTitle("مدیریت | ایجاد نقش کاربری");
        $title = "مدیریت | ایجاد نقش کاربری";
        $allRole = Role::adminRole()->get();
        $allPermission = Permission::permissionAdmin()->get();
        return view('panel.roles.index', compact('title', 'allRole', 'allPermission'));
    }

    public function create()
    {
        SEO::setTitle("مدیریت | ایجاد نقش کاربری");
        $title = "مدیریت | ایجاد نقش کاربری";
        $allPermission = Permission::permissionAdmin()->get();
        return view('panel.roles.create', compact('title', 'allPermission'));
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $label = $request->input('label');
        $permissionID = $request->input('permission_id');

        $this->validate($request, [
            'name' => "required|max:190",
            'permission_id' => 'required'
        ]);

        /* validation role and permission */
        $this->checkPermission($permissionID);
        /* validation role and permission */

        $requestData = [
            'name' => $name,
            'label' => $label
        ];

        DB::beginTransaction();
        $saveData = Role::create($requestData);
        if ($saveData instanceof Role) {
            DB::commit();
            $saveData->permissions()->sync($permissionID);
            toast()->success(Message::successMessageCreate, Lang::get('cms.success'));
            return redirect()->route('panel.role.index');
        } else {
            DB::rollBack();
            toast()->error(Message::errorMessageCreate, Lang::get('cms.error'));
            return redirect()->route('panel.role.index');
        }

    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            SEO::setTitle("مدیریت | ویرایش نقش کاربری");
            $title = "مدیریت | ویرایش نقش کاربری";
            $find = Role::findOrFail($id);
            $allPermission = Permission::permissionAdmin()->get();
            return view('panel.roles.create', compact('title', 'find', 'allPermission'));
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return redirect()->route('panel.role.index');
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $find = Role::findOrFail($id);
            $permissionID = $request->input('permission_id');
            $name = $request->input('name');
            $label = $request->input('label');
            $this->validate($request, [
                'name' => "required|max:190",
                'permission_id' => 'required'
            ]);

            /* validation role and permission */
            $this->checkPermission($permissionID);
            /* validation role and permission */


            $requestData = [
                'name' => $name,
                'label' => $label
            ];

            DB::beginTransaction();
            $updateData = $find->update($requestData);
            if ($updateData) {
                DB::commit();
                $find->permissions()->sync($permissionID);
                toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return redirect()->route('panel.role.index');
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return redirect()->route('panel.role.index');
            }

        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return redirect()->route('panel.role.index');
        }
    }

    public function delete($id)
    {
        if (in_array($id, [1, 2, 3])) {
            return redirect()->back()->with(['error' => 'شما نمی توانید این  نقش کاربری را حذف کنید , برای این کار با پشتیبانی تماس بگیرید']);
        }

        if (is_numeric($id)) {
            $find = Role::owner()->findOrFail($id);
            $findUser = $find->users;
            if (count($findUser) > 0) {
                return redirect()->back()->with(['error' => 'شما مجاز به حذف این نقش کاربری نیستید , کاربرانی با این نقش کاربری در سایت وجود دارند , ابتدا آنها را به نقش کاربری دیگر ویرایش کنید.']);
            }
            DB::beginTransaction();
            $deleteData = $find->delete();
            if ($deleteData) {
                DB::commit();
                toast()->success(Message::successMessageDelete, Lang::get('cms.success'));
                return back();
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageDelete, Lang::get('cms.error'));
                return back();
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    /*==========================    extra function ==================*/
    /* checkPermission */
    public function checkPermission($allPermission)
    {
        if (isset($allPermission) && count($allPermission) > 0) {
            foreach ($allPermission as $itemPermission) {
                $findPermission = Permission::findOrFail($itemPermission);
            }
        }
    }
}
