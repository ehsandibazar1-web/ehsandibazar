<?php

namespace App\Http\Controllers\Api\v1\userArea;

use App\Model\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        $payment = Payment::with(['user','paymentable'])->owner()->latest()->get();
        return response([
            'status' => 200,
            'data' => [
                'payment' => $payment,
            ],
            'message' => 'success',
        ]);

    }
}
