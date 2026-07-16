<?php

namespace App\Http\Controllers\Users;

use App\Model\AuctionResult;
use App\Model\favorite;
use App\Model\Product;
use App\Model\Video;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SEO;

class HomeController extends Controller
{
    public function index()
    {
        SEO::setTitle('ناحیه کاربری');
        $user = auth()->user();
        $favorites = favorite::owner()->latest()->take(4)->get();
        return view('users.index', compact('favorites', 'user'));
    }

    public function myAuctions()
    {
        $auctions = AuctionResult::winner()->paginate(10);
        return view('users.auctions', compact('auctions'));
    }

    public function myBook()
    {

        SEO::setTitle('ناحیه کاربری |‌ کتاب های من');
        $books = Auth::user()->production;
        return view('users.books', compact('books'));
    }

    public function showBook(Product $product)
    {
        SEO::setTitle("ناحیه کاربری | ".$product->title);
        return view('users.show-book', ['product' => $product]);
    }

    public function playVoice($id)
    {
        $voice = Video::findOrFail($id);
        return view('users.play-voice', ['voice' => $voice]);
    }
}
