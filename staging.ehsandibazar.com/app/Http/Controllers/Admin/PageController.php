<?php

namespace App\Http\Controllers\Admin;

use App\Model\Page;
use App\Repositories\Repository;
use App\Services\ImageServices\ImageServices;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use SEO;

class PageController extends Controller
{
    public $tags;
    public $repository;

    public function __construct(Page $page)
    {
        parent::__construct();
        $this->repository = new Repository($page);
    }

    public function index()
    {
        SEO::setTitle('مدیریت | صفحه ساز');
        $pages = Page::owner()->latest()->get();
        return view('panel.page.index', compact('pages'));
    }

    public function create()
    {
        SEO::setTitle('مدیریت |ایجاد صفحه');
        $tags = $this->tags;
        return view('panel.page.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => "required",
            'slug' => "required",
            'body' => 'required',
            'status' => 'required',
        ]);

        $image = $request->input('filepath');


        $requestData = [
            'user_id' => $this->user->id,
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'body' => $request->input('body'),
            'extra_meta' => $request->input('extra_meta'),
            'status' => $request->input('status'),
        ];

        $save = Page::create($requestData);

        if ($save instanceof Page) {

            $tags = AdminController::createTags($request);
            $this->repository->sync($save, 'tags', $tags);

            /* upload image */
            if ($image && !empty($image)) {
                ImageServices::create_images($save, $request, $this->user->id);
            }
            AdminController::createSeo($request,$save);

            toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
            return redirect()->route('panel.page.index');
        } else {
            toast()->error(Message::errorMessageCreate, 'خطا');
            return back();
        }
    }

    public function edit($id)
    {
        SEO::setTitle('مدیریت | ویرایش صفحه');
        $find = Page::findOrFail($id);
        $tags = $this->tags;
        return view('panel.page.create', compact('find', 'tags'));
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $find = Page::owner()->findOrFail($id);
            $this->validate($request, [
                'title' => "required",
                'slug' => "required",
                'body' => 'required',
                'status' => 'required',
            ]);
            $image = $request->input('filepath');

            $requestData = [
                'user_id' => $this->user->id,
                'title' => $request->input('title'),
                'slug' => $request->input('slug'),
                'body' => $request->input('body'),
                'extra_meta' => $request->input('extra_meta'),
                'status' => $request->input('status'),
            ];

            $save = $find->update($requestData);
            if ($save) {
                if ($image && !empty($image)) {
                    $updateImage = ImageServices::update_images($find, $request, $this->user->id);
                    if ($updateImage <= 0) {
                        ImageServices::create_images($find, $request, $this->user->id);
                    }
                } else {
                    ImageServices::delete_images($find);
                }


                $tags = AdminController::createTags($request);
                $this->repository->sync($find, 'tags', $tags);
                AdminController::createSeo($request,$find);

                toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                return redirect()->route('panel.page.index');
            } else {
                toast()->error(Message::errorMessageEdit, 'خطا');
                return back();
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Page::owner()->findOrFail($id);
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

    public function status($id)
    {
        DB::beginTransaction();
        try {
            $page = Page::findOrFail($id);
            $page->status == 1 ? $page->update(['status' => 0]) : $page->update(['status' => 1]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            toast()->error(Message::systemError, 'خطا!');
            return back();
        };
        toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
        return back();
    }
}
