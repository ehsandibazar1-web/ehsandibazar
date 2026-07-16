<?php


namespace App\Traits;



use App\Model\Payment;

trait HasPayment
{
    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
}