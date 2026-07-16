<?php

namespace App\Console\Commands;

use App\Model\Discount;
use App\Model\Variation;
use App\Services\discountServices\DiscountServices;
use App\Utility\DiscountType;
use Carbon\Carbon;
use Illuminate\Console\Command;

class startAmazingDiscount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:discount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start amazing Discount base On Start Date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $discounts = Discount::whereHas('discountTime', function ($q) {
            $q->where('start_date', '<=', Carbon::now()->timestamp);
        })->whereType(DiscountType::amazing)->whereStatus(1)->get();
        foreach ($discounts as $discount) {
            foreach ($discount->disable as $item) {
                $find = Variation::with('discount')->findOrfail($item->discountable_id);
                DiscountServices::update_discountPriceVariation($find, $discount->baseon, $discount->cent, DiscountType::product);
            }

        }
    }
}
