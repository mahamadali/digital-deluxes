<?php

namespace Models;

use Models\Base\Model;
use Models\Product;

class Cart extends Model
{
    protected $table = 'user_cart';

	protected $with = ['product'];

    public function product() 
	{
		return $this->parallelTo(Product::class, 'product_id');
	}

}