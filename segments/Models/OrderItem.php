<?php

namespace Models;

use Models\Base\Model;


class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $with = ['product', 'order_info'];

    public function product() {
        return $this->parallelTo(Product::class, 'product_id');
    }

    public function order_info() {
        return $this->parallelTo(Order::class, 'order_id')->without('items');
    }

}