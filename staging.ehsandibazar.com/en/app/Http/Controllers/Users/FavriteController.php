<?php

namespace App\Http\Controllers\Users;

use App\Model\favorite;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use SEO;

class FavriteController extends Controller
{
    public function index()
    {
        SEO::setTitle('ناحیه کاربری | علاقه مندی ها');
        $favorites=favorite::owner()->paginate(9);
        return view('users.favorite',compact('favorites'));
    }

    public function delete($id)
    {
        favorite::owner()->find($id)->delete();
        toast()->success(Message::successMassageFavoriteDelete,Lang::get('cms.success'));
        return back();
    }
}
