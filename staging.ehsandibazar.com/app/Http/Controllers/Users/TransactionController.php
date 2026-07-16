<?php

namespace App\Http\Controllers\Users;

use App\Model\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SEO;

class TransactionController extends Controller
{
    protected $user;

    protected const countOfRender = 9;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });

    }

    public function index()
    {
        SEO::setTitle('dashboard | Transactions');
        $payment = Payment::owner()->latest()->paginate(self::countOfRender);
        return view('users.payments', compact('payment'));
    }
}
