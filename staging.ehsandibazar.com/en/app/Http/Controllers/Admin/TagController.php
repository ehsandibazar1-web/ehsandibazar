<?php

namespace App\Http\Controllers\Admin;

use App\Model\Tag;
use App\Repositories\Repository;
use App\Utility\Message;
use App\Utility\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use phpDocumentor\Reflection\Types\Parent_;
use SEO;
use Alert;

class TagController extends Controller
{
    public $tags;
    public function __construct(Tag $tag)
    {
        parent::__construct();
        $this->tags = new Repository($tag);
    }

    public function index()
    {
        $title = "مدیرت | لیست برچسب ها";
        SEO::setTitle($title);
        $tags = $this->tags->all();
        return view('panel.tags.index', compact('tags', 'title'));
    }

    public function create()
    {
        $title = "مدیریت | ایجاد برچسب جدید";
        SEO::setTitle($title);
        return view('panel.tags.create', compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => "required",
        ]);

        $requestData = [
            'title' => $request->input('title'),
            'status' => $request->input('status')
        ];

        DB::beginTransaction();
        $createTag = Tag::create($requestData);
        if ($createTag instanceof Tag) {
            DB::commit();
            toast()->success(Message::successMessageCreate, Lang::get('cms.success'));
            return back();
        } else {
            DB::rollBack();
            toast()->error(Message::errorMessageCreate, Lang::get('cms.error'));
            return back();
        }

    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = "مدیریت | ویرایش برچسب";
            SEO::setTitle($title);
            $find = Tag::findOrFail($id);
            return view('panel.tags.create', compact('title', 'find'));
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $this->validate($request, [
                'title' => "required",
            ]);
            $requestData = [
                'title' => $request->input('title'),
                'status' => $request->input('status')
            ];
            $find = Tag::findOrFail($id);

            DB::beginTransaction();
            /* $find->slug = null;*/
            $updateData = $find->update($requestData);
            if ($updateData) {
                DB::commit();
                toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return redirect()->route('panel.tag.index');
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return redirect()->route('panel.tag.index');
            }

        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Tag::findOrFail($id);
            DB::beginTransaction();
            $deleteData = $find->delete();
            if ($deleteData) {
                DB::commit();
                toast()->success(Message::successMessageDelete, Lang::get('cms.success'));
                return redirect()->route('panel.tag.index');
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageDelete, Lang::get('cms.error'));
                return redirect()->route('panel.tag.index');
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function status($id)
    {
        if (is_numeric($id)) {
            $find = Tag::findOrFail($id);

            $data = $find->status == Status::deActive ? Status::active : Status::deActive;
            DB::beginTransaction();
            $update = $find->update(['status' => $data]);
            if ($update) {
                DB::commit();
                toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return back();
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return back();
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }
}
