<?php

namespace App\Http\Controllers\Admin;

use App\Events\sendMultipleEmailEvent;
use App\Model\NewsLatters;
use App\User;
use App\Utility\Message;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use SEO;

class NewsLetterController extends Controller
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = "مدیریت |‌ خبرنامه";
        SEO::setTitle('مدیریت |‌ خبرنامه');
        $newsletter = NewsLatters::latest()->paginate(20);
        return view('panel.newsletter.index', compact('newsletter', 'title'));
    }

    public function show()
    {
        $title = Lang::get('cms.send-email');
        SEO::setTitle(Lang::get('cms.send-email'));
        $public_user = User::where('id', '!=', $this->user->id)->get();
        return view('panel.newsletter.send', compact('public_user', 'title'));
    }

    public function sends(Request $request)
    {
//        dd($request->all());
//        if (($request->input('send') && !empty($request->input('send'))) && ($request->input('search_email') && !empty($request->input('search_email')))) {
//            return back()->with(['error' => ' لطفا یکی از دسته ها را انتخاب کنید']);
//        }




        $search_email = request('search_email');
        $send = request('send');
        $title = request('title');
        $body = request('body');
        $this->validate(\request(), [
            'title' => "required",
            'body' => "required",
        ]);

        $newsletter = 0;
// dd($request->all());

        /** check validation */
        if (isset($search_email) && !empty($search_email)) {

            /** check State and City */
            $ids = $search_email;
        } elseif (isset($send) && !empty($send)) {

            if ($send == "newsletters") {
                $ids = NewsLatters::get()->pluck('id')->toArray();
                $newsletter = 1;
            } else {
                $ids = User::where('level', request('send'))->get()->pluck('id')->toArray();
            }
        } else {
            return back()->with(['error' => 'لطفا یکی از دسته ها را انتخاب کنید']);
        }

        if (!empty($ids)) {
            event(new sendMultipleEmailEvent($ids, $title, $body, $newsletter));
            toast()->success(Message::successSendEmailPanel, 'موفقیت آمیز !');
            return back();
        } else {
            return back()->with(['error' => 'لطفا یکی از دسته ها را انتخاب کنید']);
        }

    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = NewsLatters::findOrFail($id);
            DB::beginTransaction();
            $deleteNewsLatters = $find->delete();
            if ($deleteNewsLatters) {
                DB::commit();
                toast()->success(Message::successMessageDelete, Lang::get('cms.success'));
                return redirect()->back();
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageDelete, Lang::get('cms.error'));
                return redirect()->back();
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }
}
