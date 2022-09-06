<?php

namespace Models;

use Models\Base\Model;


class Order extends Model
{
    protected $table = 'orders';
    
    protected $with = ['user', 'keys', 'items'];
    
    public function user() {
        return $this->parallelTo(User::class, 'user_id')->without('orders');
    }
    
    public function keys() {
        return $this->hasMany(GameKey::class, 'order_id');
    }

    public function items() {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function order_total() {
        $items = $this->items()->get();
        $order_total = 0;
        foreach($items as $item) {
            $order_total += $item->product_price;
        }
        return $order_total;
    }

}