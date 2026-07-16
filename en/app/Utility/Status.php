<?php
/**
 * Created by PhpStorm.
 * User: rezakia
 * Date: 29/12/2018
 * Time: 12:14 PM
 */

namespace App\Utility;

use App\Model\Order;

class Status
{
    const deActive = 0;
    const active = 1;
    const UNPAID = 2;
    const UNPAID_DISPLAYED = 22;
    const PAID = 3;
    const CANCELED = 4;
    const PENDING = 5;
    const PENDING_DISPLAYED = 55;
    const RETURNED = 6;
    const WAITING = 7;
    const SENDING = 8;

    const BLOCK = 1;
    const UNBLOCK = 0;

    public static function Status()
    {
        return [
            self::active => "فعال",
            self::deActive => "غیر فعال",
        ];
    }

    public static function getStatus($status, $write = 1)
    {
        if ($write == 1) {
            switch ($status) {
                case self::active:
                    echo "<button class='btn btn-xs btn-success'>فعال</button>";
                    break;
                case self::deActive:
                    echo "<button class='btn btn-xs btn-danger'>غیر فعال</button>";
                    break;
            }
        } else {
            switch ($status) {
                case self::active:
                    echo "<button class='btn btn-xs btn-success'>تایید شده</button>";
                    break;
                case self::deActive:
                    echo "<button class='btn btn-xs btn-danger'>در انتظار تایید</button>";
                    break;
            }
        }

    }

    public static function getBlock($status)
    {

        switch ($status) {
            case self::UNBLOCK:
                echo "<button class='btn btn-xs btn-success'>تایید / فعال</button>";
                break;
            case self::BLOCK:
                echo "<button class='btn btn-xs btn-danger'>بلاک / غیر فعال</button>";
                break;
        }


    }


    public static function getStatusComment($status)
    {
        switch ($status) {
            case self::active:
                echo "<button class='btn btn-xs btn-success'>تایید  شده</button>";
                break;
            case self::deActive:
                echo "<button class='btn btn-xs btn-danger'>در انتظار تایید</button>";
                break;
        }
    }

    public static function getStatusPayment($status)
    {
        switch ($status) {
            case self::active:
                echo "<button class='btn btn-xs btn-success'>تسویه شده</button>";
                break;
            case self::deActive:
                echo "<button class='btn btn-xs btn-danger'>پرداخت نشده</button>";
                break;
        }
    }

    public static function eachChangewStatusOrder()
    {
        return [
            self::SENDING => 'ارسال شد',
            self::CANCELED => 'لغو شده',
            self::RETURNED => 'مرجوعی',
            self::UNPAID_DISPLAYED => 'پرداخت نشده بررسی شده',
            self::PENDING_DISPLAYED => 'در حال پردازش بررسی شده',
        ];
    }

    public static function eachStatusOrder()
    {
        return [
            self::WAITING => 'در انتظار تایید',
            self::PENDING => 'در حال پردازش',
            self::PAID => ' تسویه شده ',
            self::UNPAID => 'پرداخت نشده',
            self::SENDING => 'ارسال شد',
            self::CANCELED => 'لغو شده',
            self::RETURNED => 'مرجوعی',
        ];
    }

    /* status payment */
    public static function getOrderStatus($status = null, $flag = null)
    {
        if ($status == null) {
            return [
                self::UNPAID => 'پرداخت نشده',
                self::UNPAID_DISPLAYED => 'پرداخت نشده بررسی شده',
                self::PAID => 'تسویه شده ',
                self::CANCELED => 'لغو شده',
                self::SENDING => 'ارسال شد',
                self::PENDING => 'در حال پردازش',
                self::PENDING_DISPLAYED => 'در حال پردازش بررسی شده',
                self::RETURNED => 'مرجوعی',
                self::WAITING => 'در انتظار تایید'
            ];
        } elseif ($status && $flag == null) {
            switch ($status) {
                case self::UNPAID:
                    echo "<button class='btn btn-xs btn-danger'> پرداخت نشده </button>";
                    break;
                case self::UNPAID_DISPLAYED:
                    echo "<button class='btn btn-xs btn-danger'> پرداخت نشده بررسی شده </button>";
                    break;
                case self::PAID:
                    echo "<button class='btn btn-xs btn-success'> تسویه شده </button>";
                    break;
                case self::SENDING:
                    echo "<button class='btn btn-xs btn-sending'> ارسال شد </button>";
                    break;
                case self::CANCELED:
                    echo "<button class='btn btn-xs btn-warning'>سفارش لغو شده</button>";
                    break;
                case self::PENDING:
                    echo "<button class='btn btn-xs btn-info'>درحال پردازش</button>";
                    break;
                case self::PENDING_DISPLAYED:
                    echo "<button class='btn btn-xs btn-info'>درحال پردازش بررسی شده</button>";
                    break;
                case self::RETURNED:
                    echo "<button class='btn btn-xs btn-default'>مرجوعی</button>";
                    break;
                case self::WAITING:
                    echo "<button class='btn btn-xs btn-waiting'>در انتظار تایید</button>";
                    break;
            }
        } elseif (isset($status) && $flag != null) {

            switch ($status) {
                case self::UNPAID:
                    return "پرداخت نشده";
                    break;
                case self::PAID:
                    return " تسویه شده ";
                    break;
                case self::SENDING:
                    return "درحال ارسال";
                    break;
                case self::CANCELED:
                    return "سفارش لغو شده";
                    break;
                case self::PENDING:
                    return "درحال پردازش";
                    break;
                case self::RETURNED:
                    return "مرجوعی";
                    break;
                case self::WAITING:
                    return "در انتظار تایید";
                    break;
            }
        }
    }

    /* change status */
    public static function getOrderStatusShow($status_id, $order_id)
    {
//        self::validationOrderId($order_id);
//        $array = [self::SENDING => 'sending', self::PENDING => 'info', self::WAITING => 'waiting', self::RETURNED => 'default', self::CANCELED => 'warning', self::UNPAID => 'danger', self::PAID => 'success'];
        $array = [self::SENDING => 'sending', self::PENDING => 'info', self::PENDING_DISPLAYED => 'info', self::WAITING => 'waiting',
            self::RETURNED => 'default', self::CANCELED => 'warning', self::UNPAID => 'danger',self::UNPAID_DISPLAYED => 'danger',
            self::PAID => 'success'];

        if (isset($status_id)) {

//            if ($status_id == self::SENDING) {
//                unset($array[self::WAITING], $array[self::PENDING]);
//            }
//
//            if ($status_id == self::PENDING) {
//                unset($array[self::WAITING]);
//            }
//
//            if ($status_id == self::CANCELED || $status_id == self::RETURNED || $status_id == self::WAITING || $status_id == self::UNPAID) {
//                $array = [self::PENDING => 'info'];
//            }
//
//            if ($status_id == self::PAID) {
//                $array = [self::CANCELED => 'warning', self::RETURNED => 'default', self::SENDING => 'sending'];
//            }

            foreach (self::getOrderStatus() as $key => $itemStatus) {
                foreach ($array as $keys => $itemArray) {
                    if ($key != $status_id && $keys == $key) {
                        $alert = "btn btn-" . $itemArray . " btn-status ";
                        echo "<span> $itemStatus :  </span>" . "<a class='" . $alert . "'   href='" . route('panel.order.status', ['status' => $key, 'id' => $order_id]) . "'>$itemStatus</a>" . "</br></br>";
                    }
                }

            }

        }
    }

    /* validation order id */
    public static function validationOrderId($order_id)
    {
        if (isset($order_id) && !empty($order_id)) {
            Order::findOrFail($order_id);
        }
    }

}
