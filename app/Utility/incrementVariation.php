<?php


namespace App\Utility;


use App\Model\Order;
use App\Model\OrderItem;

class incrementVariation
{
    public static function incrementVariations($order_id, $credit = null)
    {
        $validation = 1;
        $findOrder = Order::where('id', $order_id)->first();
        if (isset($findOrder) && !empty($findOrder)) {
            $findOrderItem = OrderItem::where('order_id', $findOrder->id)->get();
            if (isset($findOrderItem) && !empty($findOrderItem)) {
                foreach ($findOrderItem as $itemOrderItem) {
                    //dd(unserialize($itemOrderItem->details));
                    $unSerialize = unserialize($itemOrderItem->details);
                    $qty = $unSerialize['qty'];
                    $variationId = $unSerialize['item']->variation_id;
                    $findVariationID = \App\Model\Variation::where('id', $variationId)->first();
                    if ($findVariationID && !empty($findVariationID)) {
                        $updateVariation = $findVariationID->update([
                            'count' => $findVariationID->count = $findVariationID->count + $qty
                        ]);
                    } else {
                        $validation = 0;
                    }
                    /* age update nakard  ? */
                }
            } else {
                $validation = 0;
            }
        } else {
            $validation = 0;
        }
        return $validation;
    }
}
