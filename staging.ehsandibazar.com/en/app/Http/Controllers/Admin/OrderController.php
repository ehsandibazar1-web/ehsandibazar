<?php

namespace App\Http\Controllers\Admin;


use App\Model\Order;
use App\Utility\Level;
use App\Utility\Message;
use App\Utility\SendSms;
use App\Utility\Status;
use App\Utility\Variation;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;
use PDF;
use SEO;

class OrderController extends Controller
{

    protected $user;

    public const countOfRender = 10;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });

    }

    public function index(Request $request)
    {
        $title = "سفارشات";
        SEO::setTitle($title);
        $number = self::countOfRender;
        if ($request->has('number')) {
            $number = $request->number;
        }
        $orders = Order::owner()->with(['orderItem', 'user'])->
        whereNotIn('status',[Status::SENDING,Status::CANCELED,Status::PENDING_DISPLAYED,Status::UNPAID_DISPLAYED])->
        latest()->latest()->paginate($number);
        return view('panel.order.index', compact('orders','title'));
    }

    public function sendingOrder(Request $request)
    {
        $title = "سفارشات ارسالی";
        SEO::setTitle($title);
        $number = self::countOfRender;
        if ($request->has('number')) {
            $number = $request->number;
        }
        $orders = Order::owner()->whereHas('user')->with(['orderItem', 'user'])->where('status', Status::SENDING)->latest()->paginate($number);
        return view('panel.order.index', compact('orders','title'));
    }

    public function canceledOrder(Request $request)
    {
        $title = "سفارشات مرجوعی";
        SEO::setTitle($title);
        $number = self::countOfRender;
        if ($request->has('number')) {
            $number = $request->number;
        }
        $orders = Order::owner()->whereHas('user')->with(['orderItem', 'user'])->where('status', Status::CANCELED)->latest()->paginate($number);
        return view('panel.order.index', compact('orders','title'));
    }

    public function unpaidOrder(Request $request)
    {
        $title = "سفارشات پرداخت نشده";
        SEO::setTitle($title);
        $number = self::countOfRender;
        if ($request->has('number')) {
            $number = $request->number;
        }
        $orders = Order::owner()->whereHas('user')->with(['orderItem', 'user'])->where('status', Status::UNPAID_DISPLAYED)->latest()->paginate($number);
        return view('panel.order.index', compact('orders','title'));
    }

    public function pendingOrder(Request $request)
    {
        $title = "سفارشات درحال پردازش";
        SEO::setTitle($title);
        $number = self::countOfRender;
        if ($request->has('number')) {
            $number = $request->number;
        }
        $orders = Order::owner()->whereHas('user')->with(['orderItem', 'user'])->where('status', Status::PENDING_DISPLAYED)->latest()->paginate($number);
        return view('panel.order.index', compact('orders','title'));
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Order::owner()->findOrFail($id);
            $deleteData = $find->delete();
            if ($deleteData) {
                toast()->success(Message::successMessageDelete, 'موفقیت آمیز!');
                return back();
            } else {
                toast()->error(Message::errorMessageDelete, 'خطا');
                return back();
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function status($status,$id)
    {
        if (isset($id) && isset($status) && is_numeric($id) && is_numeric($status) && !empty($id) && !empty($status)) {
            /* validation order */
            $findOrder = Order::findOrFail($id);

            if ($this->user->level == Level::USER) {
                if ($status == Status::CANCELED) {
                    $status = 44;
                }
            }

            if ($findOrder->status == $status) {

                toast()->error('وضعیت سفارش شما تغییر کرده است لطفا مجددا تکرار نفرمایید.', Lang::get('cms.error'));
                return back();
            }

            $findOrderItem = $findOrder->orderItem;

            if (isset($findOrderItem)) {
                foreach ($findOrderItem as $itemOrderItem) {
                    $details = $itemOrderItem->details;
                    $unserialize = unserialize($details);
                    $variation = Variation::findVariation($unserialize['item']->variation_id);
                    $this->changeStatusSendSms($status, $variation, $this->user, $findOrder->tracking_code, $unserialize['qty']);
                }
            }

            $updateOrder = $findOrder->update([
                'status' => $status
            ]);

            $recievers = $findOrder->user->mobile;
            $statusMessage = Status::getOrderStatus($status, 1);

            if ($updateOrder) {
                /*$message = Lang::get('cms.your-status-order-sms')." ". $statusMessage . " " . Lang::get('cms.change-to');
                 SendSms::sms($message, $recievers);*/

                toast()->success(Lang::get('cms.msg-success-change-status'), Lang::get('cms.success'));
                return back();

            } else {
                toast()->error(Lang::get('cms.msg-error-change-status'), Lang::get('cms.error'));
                return back();
            }

        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function print(Request $request)
    {
        $data = array_diff($request->input('data'), array(null));
        $type = $request->input('type');

        if ($type == 0) {
            $orders = Order::with(['user' => function ($q) {
                $q->withTrashed();
            }])->whereIn('id', $data)->orderBy('item_count', 'asc')->get();
            // $pdf = PDF::loadView('panel.order.export.multi-order', ['orders' => $orders], [], ['format' => 'A4']);
            $view = view('panel.order.export.multi-order', ['orders' => $orders])->render();

        } else {
            $orders = Order::with(['user' => function ($q) {
                $q->withTrashed();
            }])->whereIn('id', $data)->orderBy('item_count', 'asc')->get();
            $view = view('panel.order.export.single-order', ['orders' => $orders])->render();

            // $pdf = PDF::loadView('panel.order.export.single-order', ['orders' => $orders], [], ['format' => 'A5']);
        }

        // $fileName = time() . '.pdf';
        // $pdf->save($fileName);
        return [
            'status' => 200,
            'result' => $view
        ];

    }

    public function orderSearch(Request $request)
    {

        $number = self::countOfRender;
        if ($request->has('number')) {
            $number = $request->number;
        }

        $tracking_code = $request->input('tracking_code');
        $status = $request->input('status');
        $name = $request->input('name');
        $dateEnable = $request->input('date-enable');
        $startDate = $this->convertToMiladi($request->input('start_date')) . " 00:00:00";
        $endDate = $this->convertToMiladi($request->input('end_date')) . " 23:59:59";

        $orders = Order::whereHas('user')->with(['user' => function ($q) {
            $q->withTrashed();
        }])->latest()->
        when($dateEnable, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->
        when($tracking_code, function ($query) use ($tracking_code) {
            $query->where('tracking_code', $tracking_code);
        })->
        when($name, function ($query) use ($name) {
            $query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
                $q->orWhere('family', 'like', '%' . $name . '%');
            });
            $query->with(['user' => function ($q) {
                $q->withTrashed();
            }]);
        })->
        when($status, function ($query) use ($status) {
            $query->where('status', $status);
        });


        $title = "جستجوی سفارش ";
        SEO::setTitle($title);

        $orders = $orders->paginate($number);

        return view('panel.order.index', compact('orders','title'));
    }

    public function shippingCode(Request $request, $id)
    {
        $this->validate($request, [
            'code' => 'nullable',
            'tracking_code' => [
                'nullable',
                Rule::unique('orders', 'tracking_code')->where(function ($query) {
                    $query->where('deleted_at', null);
                })->ignore($id),

            ],

        ]);

        $order = Order::findOrFail($id);
        $code = $request->input('code', null);
        $trackingCode = $request->input('tracking_code', $order->tracking_code);

        $updateOrder = $order->update(['shipping_code' => $code, 'tracking_code' => $trackingCode]);
        if ($updateOrder) {
//            SendSms::sms(['code' => $code], 40073, $order->user->mobile);
            toast()->success("کد مرسوله ارسال شد", "موفق");
            return back();
        } else {
            toast()->error("خطای رخ داده", Lang::get('cms.error'));
            return back();
        }

    }

    public function changeStatus(Request $request)
    {
        $data = array_diff($request->input('data'), array(null));
        $status = $request->input('status');
        if ($status != 999) {
            Order::whereIn('id', $data)->update(['status' => $status]);
            $message = "وضعیت سفارشات با موفقیت تغییر یافت";
        } else {
            Order::whereIn('id', $data)->delete();
            $message = " سفارشات با موفقیت حذف شدند";
        }
        return response([
            'status' => 100,
            'message' => $message
        ]);
    }

    /* ===================================  extra function ============================ */
    private function changeStatusSendSms($status_id, $variation, $user, $tracking_code, $qty)
    {
        switch ($status_id) {
            case Status::WAITING:
//                event(new changeStatusSendSms($user, Status::WAITING, $tracking_code));
                break;
            case Status::PENDING:
//                $variation->update([
//                    'sold' => $variation->sold + $qty
//                ]);
                $updateVariation = $variation->update([
                    'count' => $variation->count - $qty,
                    'sold' => $variation->sold + $qty
                ]);
//                event(new changeStatusSendSms($user, Status::PENDING, $tracking_code));
                break;
            case Status::SENDING:
//                event(new changeStatusSendSms($user, Status::PAID, $tracking_code));
                break;
            case Status::PAID:
//                event(new changeStatusSendSms($user, Status::PAID, $tracking_code));
                break;
            case Status::UNPAID:
                $updateVariation = $variation->update([
                    'count' => $variation->count + $qty,
                    'sold' => $variation->sold - $qty
                ]);
//                event(new changeStatusSendSms($user, Status::PAID, $tracking_code));
                break;
            case Status::CANCELED:
                $updateVariation = $variation->update([
                    'count' => $variation->count + $qty,
                    'sold' => $variation->sold - $qty
                ]);
//                event(new changeStatusSendSms($user, Status::CANCELED, $tracking_code));
                break;
            case Status::RETURNED:
                $updateVariation = $variation->update([
                    'count' => $variation->count + $qty,
                    'sold' => $variation->sold - $qty
                ]);
//                event(new changeStatusSendSms($user, Status::RETURNED, $tracking_code));
                break;
        }
    }

    private function convertToMiladi($date)
    {
        if (isset($date) && !empty($date)) {
            $explodeDate = explode("/", $date);
            if (count($explodeDate) == 3) {
                $times = explode(" ", $explodeDate[2]);
                $year = $this->convert2english($explodeDate[0]);
                $month = $this->convert2english($explodeDate[1]);
                $day = $this->convert2english($times[0]);

                $miladi = Verta::getGregorian($year, $month, $day); // [2015,12,25]

                $stringMiladi = $miladi[0] . "-" . $miladi[1] . "-" . $miladi[2];
                return date($stringMiladi);
            } else {
                return false;
            }
        }
    }

    public function convert2english($string)
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $string = str_replace($persianDecimal, $newNumbers, $string);
        $string = str_replace($arabicDecimal, $newNumbers, $string);
        $string = str_replace($arabic, $newNumbers, $string);
        return str_replace($persian, $newNumbers, $string);
    }

}
