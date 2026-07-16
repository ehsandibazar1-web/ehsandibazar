<?php

namespace App\Http\Controllers\Admin;

use App\Model\Auction;
use App\Model\AuctionResult;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuctionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $auctions = AuctionResult::winner()->paginate(10);
        return view('panel.auction.index', compact('auctions'));
    }
}
