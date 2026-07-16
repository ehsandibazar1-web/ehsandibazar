<?php

namespace App\Http\Controllers\Admin;

use App\Model\Systeminf;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SEO;

class SettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = "مدیریت تنظیمات عمومی";
        SEO::setTitle($title);
        $systmeinf = Systeminf::latest()->get();
        return view('panel.setting-site.index', compact('title', 'systmeinf'));
    }

    public function create()
    {
        $title = "ایجاد تنظیمات جدید";
        SEO::setTitle($title);
        return view('panel.setting-site.create', compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ];
        $result = Systeminf::create($data);
        if ($result instanceof Systeminf) {
            AdminController::createSeo($request,$result);
            toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
            return back();
        } else {
            toast()->error(Message::errorMessageCreate, 'خطا');
            return back();
        }
    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = "ویرایش تنظیمات جدید";
            SEO::setTitle($title);
            $find = Systeminf::findOrFail($id);
            if ($find) {
                return view('panel.setting-site.create', compact('find', 'title'));
            } else {
                toast()->error(Message::illegalError, 'خطا');
                return back();
            }
        } else {
            toast()->error(Message::errorMessageCreate, 'خطا');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $this->validate($request, [
                'name' => 'required',
            ]);
            $data = [
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ];

            if (is_numeric($id)) {
                $find = Systeminf::findOrFail($id);
                if ($find) {
                    $result = $find->update($data);
                    if ($result) {
                        AdminController::createSeo($request,$find);
                        toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                        return redirect()->route('panel.setting.index');
                    } else {
                        toast()->error(Message::errorMessageEdit, 'خطا');
                        return redirect()->route('panel.setting.index');
                    }
                } else {
                    toast()->error(Message::illegalError, 'خطا');
                    return redirect()->route('panel.setting.index');
                }
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function delete($id)
    {

        if (is_numeric($id)) {
            $find = Systeminf::findOrFail($id);
            if ($find) {
                $deleteData = $find->delete();
                if ($deleteData) {
                    toast()->success(Message::successMessageDelete, 'موفقیت آمیز!');
                    return redirect()->route('panel.setting.index');
                } else {
                    toast()->error(Message::errorMessageDelete, 'خطا');
                    return redirect()->route('panel.setting.index');
                }
            } else {
                toast()->error(Message::systemError, 'خطا');
                return redirect()->route('panel.setting.index');
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return redirect()->back();
        }
    }

    public function status($id)
    {
        if (is_numeric($id)) {
            $article = Systeminf::findOrFail($id);
            if ($article) {

                if ($article->status == 0) {
                    $data = [
                        'status' => 1
                    ];

                } elseif ($article->status == 1) {
                    $data = [
                        'status' => 0
                    ];
                }

                $update = $article->update($data);

                if ($update) {
                    toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                    return back();
                } else {
                    toast()->error(Message::errorMessageEdit, 'خطا');
                    return back();
                }

            } else {
                toast()->error(Message::systemError, 'خطا');
                return redirect()->route('panel.setting.index');
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }
}
