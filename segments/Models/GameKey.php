<?php

namespace Models;

use Models\Base\Model;
use Models\Product;

class GameKey extends Model
{
    protected $table = 'game_keys';

    public function product() 
	{
		return $this->parallelTo(Product::class, 'product_id');
	}

}