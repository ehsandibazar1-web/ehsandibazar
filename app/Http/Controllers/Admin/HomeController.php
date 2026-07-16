<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Order;
use App\User;
use App\Utility\paymentMethods;
use App\Utility\Status;


class HomeController extends Controller
{
    public function index()
    {
        $paidPayment = Order::where('status', Status::PAID)->count();
        $pendingPayment = Order::where('status', Status::PENDING)->count();
        $unPaidPayment = Order::where('status', Status::UNPAID)->count();
        $canceledPayment = Order::where('status', Status::CANCELED)->count();
        $returnPayment = Order::where('status', Status::RETURNED)->count();
        $waitingPayment = Order::where('status', Status::WAITING)->count();
        $onlinePayment = Order::where('payment_method_id', paymentMethods::ONLINE)->count();
        $users = User::count();

        return view('panel.index', compact('paidPayment', 'pendingPayment', 'unPaidPayment',
            'canceledPayment', 'returnPayment', 'waitingPayment', 'onlinePayment', 'users'));
    }
}
