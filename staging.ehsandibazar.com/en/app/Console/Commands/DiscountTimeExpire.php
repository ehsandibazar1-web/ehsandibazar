<?php

namespace App\Console\Commands;

use App\Model\Discount;
use App\Utility\DiscountType;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DiscountTimeExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkDiscountTime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command check discount time';

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
        $discounts = Discount::whereIn('type', [DiscountType::amazing, DiscountType::coupon, DiscountType::discountTime])
            ->whereStatus(1)
            ->orWhere([
                ['count_user','>',0],
                ['count_user','=',null],
            ])
            ->latest()->get();

        foreach ($discounts as $discount) {
            switch ($discount->type) {
                case DiscountType::discountTime:
                case DiscountType::amazing:
                $find = $discount->discountTime;
                self::checkExpireDiscount($find, $discount);
                    break;
                case DiscountType::coupon:
                    $find = $discount->coupon;
                    self::checkExpireDiscount($find, $discount,1);
                    break;
            }

            if ($discount->type != DiscountType::amazing){
                $discount->update(['status' => 0]);
            }
        }
    }


    private static function checkExpireDiscount($relationDiscount, $discount , $coupon = null)
    {
        $expire = $relationDiscount[0]->expire_date;
        if ($expire < Carbon::now()->timestamp) {
            if(is_null($coupon)){
                self::findRelatedDiscountVariation($discount->disable);
            }else{
                $discount->update([
                    'count_user' => 0
                ]);
            }
        }
        if ($discount->type == DiscountType::amazing && $expire < Carbon::now()->timestamp){
            $discount->update(['status' => 0]);
        }

    }

    private static function findRelatedDiscountVariation($discountable)
    {
        foreach ($discountable as $itemDiscountable) {
            if (isset($itemDiscountable->discountable) && !empty($itemDiscountable->discountable)){
                $products = $itemDiscountable->discountable->products;
                //If isNull => set null Variation
                if (is_null($products)) {
                    $products = $itemDiscountable->discountable;
                    $products->update([
                        'discountPrice' => null,
                        'discountActive' => null
                    ]);

                }
                //else => set null Variation For Category OR Brand
                else {
                    foreach ($products as $product) {
                        foreach ($product->variations as $itemVariation) {
                            $itemVariation->update([
                                'discountPrice' => null,
                                'discountActive' => null
                            ]);
                        }
                    }
                }
            }
        }
    }

}
