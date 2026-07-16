<?php

namespace App\Http\Controllers\Admin;

use App\Model\Permission;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use SEO;

class PermissionController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $countNewRoute = $this->UpdateCountNewRout(1);
        $permissionCount = Permission::where('method', '!=', 'NOTHING')->count();
        SEO::setTitle('مدیریت | لیست سطوح دسترسی');
        $title = "مدیریت | لیست سطوح دسترسی";
        $arrayRoute = Permission::permissionAdmin()->paginate(20);
        return view('panel.permissions.index', compact('title', 'arrayRoute', 'permissionCount', 'countNewRoute'));
    }

    public function create()
    {
        SEO::setTitle('مدیریت | ایجاد سطوح دسترسی جدید');
        $title = "مدیریت | ایجاد سطوح دسترسی جدید";
        return view('panel.permissions.create', compact('title'));
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $label = $request->input('label');
        $method = $request->input('method');
        $this->validate($request, [
            'name' => 'required|max:190',
            'method' => 'required|'
        ]);
        $requestData = [
            'name' => $name,
            'label' => $label,
            'method' => $method
        ];
        DB::beginTransaction();
        $saveData = Permission::create($requestData);
        if ($saveData instanceof Permission) {
            DB::commit();
            toast()->success(Message::successMessageCreate, Lang::get('cms.success'));
            return redirect()->route('panel.permission.index');
        } else {
            DB::rollBack();
            toast()->error(Message::errorMessageCreate, Lang::get('cms.error'));
            return redirect()->route('panel.permission.index');
        }
    }


    /* updateToDate routes */
    public function updateToDate()
    {
        $whiteList = ["panel"];
        $arrayAllRoute = [];
        $allPermission = Permission::withTrashed()->select('id', 'name', 'method','deleted_at')->get();
        $all = [];
        foreach ($allPermission as $itemPermission) {
            $all['name'] [] = $itemPermission->name;
            $all['method'] [] = $itemPermission->method;
        }
        $routeCollection = Route::getRoutes();
        if (isset($routeCollection) && !empty($routeCollection) && count($routeCollection) > 0) {
            foreach ($routeCollection as $value) {
                if ($value->getName() != null) {
                    $explod = explode("::", $value->getName());
                    if (isset($explod) && count($explod) == 1) {
                        $explods = explode(".", $value->getName());
                        if (isset($explods) && isset($explods[0])) {
                            if (in_array($explods[0], $whiteList)) {
                                $arrayAllRoute [] = $value->getName();
                                if (count($allPermission) > 0) {
                                    if (!in_array($value->getName(), $all['name'])) {
                                        Permission::create([
                                            'name' => $value->getName(),
                                            'method' => $value->methods[0]
                                        ]);
                                    } else {
                                        $findTrashed =  Permission::withTrashed()->where('name',$value->getName())->whereNotNull('deleted_at')->first();
                                        if($findTrashed){
                                            $findTrashed->update([
                                                'deleted_at' => null
                                            ]);
                                        }
                                        continue;
                                    }
                                } else {

                                    Permission::create([
                                        'name' => $value->getName(),
                                        'method' => $value->methods[0]
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            /* agar routi dakhele web pak beshavad in check mikone ke aya kolee routhaye tahte sharayete foreache bala
            dakhele permission hast ya na agar nabud az dakhele permission pak mishavad
             */
            if(isset($arrayAllRoute) && count($arrayAllRoute) > 0 ){
                foreach ($allPermission as $itemAllPermission){
                    if($itemAllPermission->method != "NOTHING" ){
                        if(!in_array($itemAllPermission->name , $arrayAllRoute)){
                            $find =  Permission::findOrFail($itemAllPermission->id);
                            DB::beginTransaction();
                            $delete = $find->delete();
                            if($delete){
                                DB::commit();
                            }else{
                                DB::rollBack();
                            }
                        }
                    }
                }
            }

        }
        toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
        return redirect()->route('panel.permission.index');
    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = "مدیریت | ویرایش سطوح دسترسی";
            SEO::setTitle('مدیریت | ویرایش سطوح دسترسی');
            $find = Permission::findOrFail($id);
            return view('panel.permissions.create', compact('title', 'find'));
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return redirect()->route('panel.permission.index');
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $find = Permission::findOrFail($id);
            $label = $request->input('label');
            $requestData = [
                'label' => $label,
            ];

            DB::beginTransaction();
            $updateData = $find->update($requestData);
            if ($updateData) {
                DB::commit();
                toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return redirect()->route('panel.permission.index');
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return redirect()->route('panel.permission.index');
            }

        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return redirect()->route('panel.permission.index');
        }
    }


    /* ===================== extra function ====================== */
    public function UpdateCountNewRout($count = null)
    {
        $whiteList = ["panel"];
        $counts = 0;
        $allPermission = Permission::select('name', 'method')->get();
        $all = [];
        foreach ($allPermission as $itemPermission) {
            $all['name'] [] = $itemPermission->name;
            $all['method'] [] = $itemPermission->method;
        }
        $routeCollection = Route::getRoutes();
        if (isset($routeCollection) && !empty($routeCollection) && count($routeCollection) > 0) {
            foreach ($routeCollection as $value) {
                if ($value->getName() != null) {
                    $explod = explode("::", $value->getName());
                    if (isset($explod) && count($explod) == 1) {
                        $explods = explode(".", $value->getName());
                        if (isset($explods) && isset($explods[0])) {
                            if (in_array($explods[0], $whiteList)) {
                                if ($count != null) {
                                    $counts += 1;
                                } else {
                                    Permission::create([
                                        'name' => $value->getName(),
                                        'method' => $value->methods[0]
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($count != null) {
            return $counts;
        }
    }
}
