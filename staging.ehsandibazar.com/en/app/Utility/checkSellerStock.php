<?php


namespace App\Utility;


use App\Model\Basket;
use Illuminate\Support\Facades\Session;

class checkSellerStock
{
    public static function checkSellerStock ($variationId)
    {
        $session = \Illuminate\Support\Facades\Session::get('basket');
        if (isset($session) && !empty($session) && isset($session->items) && !empty($session->items)) {
            foreach ($session->items as $itemSession) {
                if ($itemSession['item']->variation_id == $variationId) {
                    $inBasket = $itemSession['qty'];
                    $seller_id = $itemSession['item']->seller;
                    $variation_id = $itemSession['item']->variation_id;
                    $variationStock = \App\Model\Variation::whereStatus('1')->where(
                        [
                            'user_id' => $seller_id,
                            'id' => $variation_id
                        ]
                    )->first();
                    /* stock for variation */
                    $stock = $variationStock->count;
                        if ($inBasket > $stock) {
                            $baskets = new \App\Model\Basket($session);
                            $baskets->deleteVariationBasket($variation_id);
                            return false;
                        }

                    return true;
                }
            }
        }
        return true;
    }
}
