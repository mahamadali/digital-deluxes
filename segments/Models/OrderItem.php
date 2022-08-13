<?php

namespace Models;

use Models\Base\Model;


class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $with = ['product'];

    public function product() {
        return $this->parallelTo(Product::class, 'product_id');
    }

}