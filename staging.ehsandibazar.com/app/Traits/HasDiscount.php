<?php


namespace App\Traits;
use App\Model\Discountable;

trait HasDiscount
{
    public function discount()
    {
        return $this->morphMany(Discountable::class, 'discountable')->
        whereHas('discount',function ($q){
            $q->where('status',1);
        });
    }
}
