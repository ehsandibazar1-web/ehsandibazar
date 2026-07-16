<?php

namespace App\Http\Controllers\Users;

use App\Events\CheckPaymentStatusPending;
use App\Model\Order;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SEO;

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
        SEO::setTitle('ناحیه کاربری | سفارشات');
        event(new CheckPaymentStatusPending($this->user));
        $orders = Order::owner()->with(['orderItem', 'user'])->latest()->paginate(self::countOfRender);
        return view('users.orders', compact('orders'));
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Order::owner()->findOrFail($id);
            $deleteData = $find->delete();
            if ($deleteData) {
                alert()->success("سفارش به درستی حذف گردید", 'موفقیت آمیز')->showConfirmButton('بستن');
                return redirect()->back();
            } else {
                alert()->error("مشکلی در حذف سفارش پیش آمد", 'ناموفق')->showConfirmButton('بستن');
                return redirect()->back();
            }
        } else {
            alert()->error("مشکلی در حذف سفارش پیش آمده", 'ناموفق')->showConfirmButton('بستن');
            return redirect()->back();
        }
    }

}
