<?php

namespace App\Http\Controllers\Admin;

use App\Model\Article;
use App\Model\Comment;
use App\Traits\HasComment;
use App\Utility\CommentStatus;
use App\Utility\Message;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Session;
use SEO;

class CommentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = "مدیریت | لیست دیدگاه ها";
        SEO::setTitle('مدیریت | لیست دیدگاه ها');
        $comments = Comment::with('user')->latest()->get();
        return view('panel.comment.index', compact('comments', 'title'));
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $findComment = Comment::findOrFail($id);
            $class = get_class($findComment->commentable);
            $commentable = $class::find($findComment->commentable->id);
            if ($commentable->commentCount >= 1) {
                $commentable->decrement('commentCount');
            }
            DB::beginTransaction();
            $deleteComment = $findComment->delete();
            if ($deleteComment) {
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

    public function status($id)
    {
        if (is_numeric($id)) {
            $comment = Comment::findOrFail($id);
            $class = get_class($comment->commentable);
            $commentable = $class::find($comment->commentable->id);

            if ($comment->status == CommentStatus::NOT_ACCEPTET) {
                $data = [
                    'status' => CommentStatus::ACCEPTET
                ];
                $commentable->increment('commentCount');

            } elseif ($comment->status == CommentStatus::ACCEPTET) {
                $data = [
                    'status' => CommentStatus::FAILED
                ];
                $commentable->decrement('commentCount');

            }elseif ($comment->status == CommentStatus::FAILED) {
                $data = [
                    'status' => CommentStatus::NOT_ACCEPTET
                ];
            }

            DB::beginTransaction();
            $update = $comment->update($data);
            if ($update) {
                DB::commit();
                toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                return back();
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageEdit, 'خطا');
                return back();
            }

        } else {
            toast()->error(Message::errorMessageCreate, 'خطا');
            return back();
        }
    }

}
