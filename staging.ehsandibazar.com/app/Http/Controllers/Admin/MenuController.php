<?php

namespace App\Http\Controllers\Admin;

use App\Model\Menu;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use SEO;
Use Alert;


class MenuController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        SEO::setTitle('مدیریت |منو');
        $menus = Menu::where(['parent_id' => 0])->orderBy('sorting', 'ASC')->get();
        return view('panel.menu.index', compact('menus'));
    }

    public function create()
    {
        SEO::setTitle('مدیریت |ایجاد منو');
        $menu = Menu::whereStatus(1)->get();
        return view('panel.menu.create',compact('menu'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => "required",
            'src' => 'required',
            'status' => 'required',
        ]);

        $requestData = [
            'title' => $request->input('title'),
            'src' => $request->input('src'),
            'parent_id' => $request->input('parent'),
            'status' => $request->input('status'),
        ];

        $save = Menu::create($requestData);

        if ($save instanceof Menu) {
            toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
            return redirect()->route('panel.menu.index');
        } else {
            toast()->error(Message::errorMessageCreate, 'خطا');
            return back();
        }
    }

    public function edit($id)
    {
        SEO::setTitle('مدیریت | ویرایش منو');
        $find = Menu::findOrFail($id);
        $menu = Menu::whereStatus(1)->where('id','!=',$find->id)->get();
        return view('panel.menu.create',compact('find','menu'));
    }

    public function update(Request $request, $id)
    {
        if(is_numeric($id)){
            $find = Menu::findOrFail($id);
            $this->validate($request, [
                'title' => "required",
                'src' => 'required',
                'status' => 'required',
            ]);
            $requestData = [
                'title' => $request->input('title'),
                'src' => $request->input('src'),
                'parent_id' => $request->input('parent'),
                'status' => $request->input('status'),
            ];

            $save = $find->update($requestData);
            if ($save) {
                toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                return redirect()->route('panel.menu.index');
            } else {
                toast()->error(Message::errorMessageEdit, 'خطا');
                return back();
            }
        }else{
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Menu::findOrFail($id);
            $delete = $find->delete();
            if ($delete) {
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

    public function saveNestedMenus(Request $request)
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
                $menu = Menu::find($v['category_id']);
                $menu->update([
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
            toast()->success("همه منو ها به روز شدن", Lang::get('cms.success'))->persistent('بستن');
        } else {
            toast()->error("هنگام بروزرسانی مشکلی پیش آمد ...", Lang::get('cms.error'))->persistent('بستن');
        }
        return redirect()->route('panel.menu.index');

    }

    public function recur1($nested_array=[], &$simplified_list=[]){

        static $counter = 0;

        foreach($nested_array as $k => $v){

            $sort_order = $k+1;
            $simplified_list[] = [
                "category_id" => $v['id'],
                "parent_id" => 0,
                "sorting" => $sort_order
            ];

            if(!empty($v["children"])){
                $counter+=1;
                $this->recur2($v['children'], $simplified_list, $v['id']);
            }

        }
    }

    public function recur2($sub_nested_array=[], &$simplified_list=[], $parent_id = 0){

        static $counter = 0;

        foreach($sub_nested_array as $k => $v){

            $sort_order = $k+1;
            $simplified_list[] = [
                "category_id" => $v['id'],
                "parent_id" => $parent_id,
                "sorting" => $sort_order
            ];

            if(!empty($v["children"])){
                $counter+=1;
                return $this->recur2($v['children'], $simplified_list, $v['id']);
            }
        }
    }
}
