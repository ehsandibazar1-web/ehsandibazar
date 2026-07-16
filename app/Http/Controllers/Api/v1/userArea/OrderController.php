<?php

namespace App\Http\Controllers\Api\v1\userArea;

use App\Events\CheckPaymentStatusPending;
use App\Model\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $user;

    public const countOfRender = 9;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });

    }

    public function index()
    {
        event(new CheckPaymentStatusPending($this->user));
        $orders = Order::owner()->with(['orderItem' => function($q){
            $q->withTrashed()->with(['product' => function($query){
                $query->with('image')->withTrashed()->get();
            }]);

        }])->latest()->get();
        return response([
            'status' => 200,
            'data' => [
                'orders' => $orders,
            ],
            'message' => 'success',
        ]);
    }
}
