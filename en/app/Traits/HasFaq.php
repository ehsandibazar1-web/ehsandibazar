<?php


namespace App\Traits;


use App\Model\Faq;

trait HasFaq
{
    public function faqs()
    {
        return $this->morphMany(Faq::class, 'faqable');
    }
}
