<?php

namespace Models;

use Models\Base\Model;


class HomeSliderProduct extends Model
{
    protected $table = 'home_slider_products';

    protected $with = ['product_info'];

    public function product_info() {
    	return $this->parallelTo(Product::class, 'product_id');
    }
}