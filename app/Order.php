<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $status
 * @property string $client_email
 * @property int $partner_id
 * @property Partner $partner
 * @property Collection|OrderProduct[] $products
 * @property Carbon $delivery_dt
 */
class Order extends Model
{
    const STATUSES = [
        0  => 'Новый',
        10 => 'Подтвержден',
        20 => 'Завершен'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function partner()
    {
        return $this->hasOne(Partner::class, 'id', 'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return static::STATUSES[$this->status] ?? '';
    }

    /**
     * @return int
     */
    public function getSum()
    {
        $sum = 0;

        $this->products->map(function(OrderProduct $orderProduct) use(&$sum)
        {
            $sum += $orderProduct->quantity * $orderProduct->price;
        });

        return $sum;
    }
}
