<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Article;
use App\Model\Category;
use App\Model\Categoryarticle;
use App\Model\Tag;
use App\Repositories\Repository;
use App\Services\ImageServices\ImageServices;
use App\Utility\Message;
use App\Utility\Status;
use App\Utility\tools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Session;
use SEO;
use function GuzzleHttp\Promise\all;

class ArticleController extends Controller
{
    public $tags;
    public $repository;

    public function __construct(Article $article)
    {
        parent::__construct();
        $this->tags = Tag::whereStatus(Status::active)->get();
        $this->repository = new Repository($article);
    }

    public function index()
    {
        $title = Lang::get('cms.article');
        SEO::setTitle($title);
        $articles = $this->repository->getOwner();
        return view('panel.articles.index', compact('articles', 'title'));
    }

    public function create()
    {
        SEO::setTitle('ایجاد مقاله');
        $category =$this->repository->setModel(new Category())->get([['type', Article::class], ['status', 1]]);
        $tags = $this->tags;
        return view('panel.articles.create', compact('category', 'tags'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => "required",
            'body' => "required",
            'cat_id' => "required",
            'filepath' => "required",
        ]);

        $image = $request->input('filepath');
        $description = $request->input('body');
        $categoryId =$request->input('cat_id');

        $readTime = tools::getEstimateReadingTime($description);
        $requestData = [
            'user_id' => Auth::user()->id,
            'title' => $request->input('title'),
            'body' => $description,
            'study_time' => $readTime,
            'extra_meta' => $request->input('extra_meta'),
            'status' => $request->input('status')
        ];
        DB::beginTransaction();
        $saveArticle = $this->repository->create($requestData);
        if ($saveArticle instanceof Article) {

            DB::commit();
            /* upload image */
            if ($image && !empty($image)) {
                ImageServices::create_images($saveArticle, $request, auth()->user()->id);
            }


            $tags = AdminController::createTags($request);
            $this->repository->sync($saveArticle, 'tags', $tags);
            $this->repository->sync($saveArticle, 'categories', [$categoryId]);


            toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
            return redirect()->route('panel.article.index');
        } else {
            DB::rollBack();
            toast()->error(Message::errorMessageCreate, 'خطا');
            return back();
        }
    }

    public function edit($id)
    {
        SEO::setTitle('ویرایش مقاله');
        if (is_numeric($id)) {
            $title = Lang::get('cms.edit');
            $find = $this->repository->showOwner($id);
            $category = $this->repository->setModel(new Category())->get([['type', Article::class], ['status', 1]]);
            $tags = $this->tags;
            return view('panel.articles.create', compact('category', 'find', 'title', 'tags'));
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $this->validate($request, [
                'title' => "required",
                'body' => "required",
                'cat_id' => "required",
                'filepath' => "required",
            ]);
            $image = $request->input('filepath');
            $description = $request->input('body');
            $categoryId = $request->input('cat_id');
            $readTime = tools::getEstimateReadingTime($description);

            $requestData = [
                'user_id' => Auth::user()->id,
                'title' => $request->input('title'),
                'body' => $description,
                'study_time' => $readTime,
                'extra_meta' => $request->input('extra_meta'),
                'status' => $request->input('status')
            ];
            $find = $this->repository->showOwner($id);
            DB::beginTransaction();
            if ($find->count() > 0) {

                $updateData = $find->update($requestData);
                if ($updateData) {
                    DB::commit();
                    if ($image && !empty($image)) {
                        $updateImage = ImageServices::update_images($find, $request, auth()->user()->id);
                        if ($updateImage <= 0) {
                            ImageServices::create_images($find, $request, auth()->user()->id);
                        }
                    } else {
                        ImageServices::delete_images($find);
                    }

                    $tags = AdminController::createTags($request);
                    $this->repository->sync($find, 'tags', $tags);
                    $this->repository->sync($find, 'categories', [$categoryId]);


                    toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                    return redirect()->route('panel.article.index');
                } else {
                    DB::rollBack();
                    toast()->error(Message::errorMessageEdit, 'خطا');
                    return back();
                }
            } else {
                toast()->error(Message::systemError, 'خطا');
                return redirect()->route('panel.article.index');
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            DB::beginTransaction();
            $deleteData = $this->repository->delete($id);
            if ($deleteData) {
                DB::commit();
                toast()->success(Message::successMessageDelete, 'موفقیت آمیز!');
                return back();
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageDelete, 'خطا');
                return back();
            }
        } else {
            toast()->error(Message::systemError, 'خطا');
            return back();
        }

    }

    public function status($id)
    {
        if (is_numeric($id)) {
            $advertise = $this->repository->showOwner($id);
            if ($advertise->count() > 0) {

                if ($advertise->status == 0) {
                    $data = [
                        'status' => 1
                    ];

                } elseif ($advertise->status == 1) {
                    $data = [
                        'status' => 0
                    ];
                }

                $update = $advertise->update($data);

                if ($update) {
                    toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                    return back();
                } else {
                    toast()->error(Message::errorMessageEdit, 'خطا');
                    return back();
                }

            } else {
                toast()->error(Message::systemError, 'خطا');
                return back();
            }
        } else {
            toast()->error(Message::errorMessageCreate, 'خطا');
            return back();
        }
    }

}
