<?php

namespace App\Http\Controllers\Admin;

use App\Model\Systeminf;
use App\Model\Systeminfmanage;
use App\Services\ImageServices\ImageServices;
use App\Utility\Message;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use SEO;

class ManageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id)
    {
        if (is_numeric($id)) {
            SEO::setTitle('مدیریت تنظیمات');
            $title = "مدیریت تنظیمات";
            $systeminfmanage = Systeminfmanage::with('image')->where('systeminf_id', $id)->latest()->get();
            return view('panel.setting-site.manage.index', compact('id', 'title', 'systeminfmanage'));
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return redirect()->route('panel.setting.index');
        }
    }

    public function create($id)
    {
        $title = "ایجاد تنظیم";
        SEOMeta::setTitle($title);
        $manage = Systeminf::find($id);
        return view('panel.setting-site.manage.create',compact('title','manage'));
    }

    public function store(Request $request)
    {
        $image = $request->input('code5');
        $this->validate($request, [
//            'name' => "required",
            'syshidden' => 'required'
        ]);

        $data = [
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'code2' => $request->input('code2'),
            'code3' => $request->input('code3'),
            'code4' => $request->input('code4'),
            'code5' => $request->input('filepath'),
            'systeminf_id' => $request->input('syshidden'),
            'lang' => $request->input('lang'),
            'status' => 0
        ];

        $result = Systeminfmanage::create($data);
        if ($result) {
            if ($image && !empty($image)) {
                ImageServices::create_images($result, $request, auth()->user()->id);
            }
            toast()->success(Message::successMessageCreate, Lang::get('cms.success'));
            return redirect()->route('panel.manage.index',$result->systeminf_id);
        } else {
            toast()->error(Message::errorMessageCreate, Lang::get('cms.error'));
            return back();
        }
    }

    public function edit($id)
    {
        $val = Systeminfmanage::find($id);
        return view('panel.setting-site.manage.edit',compact('val'));
    }


    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $image = $request->input('code5');
//            $this->validate($request, [
//                'name' => "required",
//            ]);
            $data = [
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'code2' => $request->input('code2'),
                'code3' => $request->input('code3'),
                'code4' => $request->input('code4'),
                'code5' => $request->input('filepath'),
                'lang' => $request->input('lang'),
                'status' => 0
            ];
            if (is_numeric($id)) {
                //$find = Systeminfmanage::find($id);
                $find = Systeminfmanage::findOrFail($id);
                $update = $find->update($data);
                if ($update) {

                    if ($image && !empty($image)) {
                        $updateImage = ImageServices::update_images($find, $request, auth()->user()->id);
                        if ($updateImage <= 0) {
                            ImageServices::create_images($find, $request, auth()->user()->id);
                        }
                    } else {
                        ImageServices::delete_images($find);
                    }
                    toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                    return redirect()->route('panel.manage.index',$find->systeminf_id);
                } else {
                    toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                    return back();
                }

            } else {
                toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return back();
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {

            $find = Systeminfmanage::findOrFail($id);

            if ($find) {
                $result = $find->delete();
                if ($result) {
                    toast()->success(Message::successMessageDelete, Lang::get('cms.success'));
                    return back();
                } else {
                    toast()->error(Message::errorMessageDelete, Lang::get('cms.error'));
                    return back();
                }
            }
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return redirect()->route('panel.setting.index');
        }
    }

    public function status($id)
    {
        $manage = Systeminfmanage::find($id);
        if ($manage) {
            if ($manage->status == 0) {
                $data = [
                    'status' => 1
                ];
            } else {
                $data = [
                    'status' => 0
                ];
            }
            $update = $manage->update($data);
            if ($update) {
                toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return back();
            } else {
                toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return back();
            }
        } else {
            toast()->error(Message::systemError, Lang::get('cms.error'));
            return back();
        }
    }
}
