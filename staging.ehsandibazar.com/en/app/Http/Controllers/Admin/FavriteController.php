<?php

namespace App\Http\Controllers\Admin;

use App\Model\favorite;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavriteController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $favorites=favorite::owner()->paginate(9);
        return view('panel.favorite.index',compact('favorites'));
    }

    public function delete($id)
    {
        favorite::owner()->find($id)->delete();
        toast()->success(Message::successMassageFavoriteDelete, 'موفقیت آمیز!');
        return back();
    }
}
