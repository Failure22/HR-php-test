<?php

namespace App\Http\Resources;

use App\Order;
use App\OrderProduct;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderListResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $orders = [];

        /** @var Order $order */
        foreach ($this->collection as $order)
        {
            $orders[] = [
                'id'       => $order->id,
                'partner'  => $order->partner->name,
                'sum'      => $order->getSum(),
                'products' => $order->products->map(function(OrderProduct $orderProduct)
                {
                    return [
                        'name'     => $orderProduct->product->name,
                        'quantity' => $orderProduct->quantity
                    ];
                }),
                'status'   => $order->getStatusName()
            ];
        }

        return $orders;
    }
}
