<?php

namespace App\Http\Controllers\Api\v1\userArea;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $countOrder = $user->order->count();
        $countCustomOrder = $user->productRequest->count();
        return response([
            'status' => 200,
            'data' => [
                'countOrder' => $countOrder,
                'countCustomOrder' => $countCustomOrder,
            ],
            'message' => 'success',
        ]);
    }
}
